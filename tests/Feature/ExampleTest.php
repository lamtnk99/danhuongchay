<?php

namespace Tests\Feature;

use App\Models\ChatSession;
use App\Mail\ReservationAdminNotificationMail;
use App\Mail\ReservationCustomerConfirmationMail;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Dish;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_public_pages_are_available(): void
    {
        foreach (['/', '/gioi-thieu', '/khong-gian', '/thuc-don', '/blog', '/dat-ban', '/lien-he', '/sitemap.xml', '/robots.txt', '/trang/chinh-sach-dat-ban'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_admin_area_requires_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_login_redirects_admin_to_dashboard(): void
    {
        $this->post(route('login.store'), [
            'email' => 'admin@danhuongchay.vn',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_chat_widget_can_start_and_send_message(): void
    {
        $startResponse = $this->postJson(route('chat.start'), [
            'visitor_name' => 'Mai Lan',
            'phone' => '0912000000',
            'message' => 'Tôi muốn tư vấn đặt bàn tối nay.',
        ])->assertOk();

        $sessionId = $startResponse->json('session_id');

        $this->withSession(['chat_session_id' => $sessionId])
            ->postJson(route('chat.send', $sessionId), [
                'message' => 'Nhà hàng còn bàn cho 4 người không?',
            ])
            ->assertOk()
            ->assertJsonCount(2, 'messages');

        $this->assertDatabaseHas(ChatSession::class, [
            'public_id' => $sessionId,
            'visitor_name' => 'Mai Lan',
        ]);
    }

    public function test_admin_notifications_return_pending_counts(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        $this->postJson(route('chat.start'), [
            'visitor_name' => 'Mai Lan',
            'phone' => '0912000000',
            'message' => 'Tôi muốn tư vấn đặt bàn tối nay.',
        ])->assertOk();

        Reservation::create([
            'name' => 'Khách đặt bàn',
            'phone' => '0909000000',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);

        Contact::create([
            'name' => 'Khách liên hệ',
            'message' => 'Tôi cần hỏi thông tin thực đơn.',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.notifications.index'))
            ->assertOk()
            ->assertJsonPath('counts.chat', 1)
            ->assertJsonPath('counts.reservations', 1)
            ->assertJsonPath('counts.contacts', 1);
    }

    public function test_dish_and_post_detail_pages_are_available(): void
    {
        $dish = Dish::firstOrFail();
        $post = Post::published()->firstOrFail();

        $this->get(route('menu.show', $dish))
            ->assertOk()
            ->assertSee($dish->name);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee($post->title);
    }

    public function test_contact_form_stores_message(): void
    {
        $payload = [
            'name' => 'Nguyễn An',
            'phone' => '0912345678',
            'message' => 'Tôi muốn hỏi thêm về thực đơn tiệc chay cuối tuần.',
        ];

        $this->post(route('contact.store'), $payload)
            ->assertRedirect(route('contact'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas(Contact::class, [
            'name' => 'Nguyễn An',
            'phone' => '0912345678',
        ]);
    }

    public function test_reservation_form_stores_reservation(): void
    {
        $payload = [
            'name' => 'Trần Bình',
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'note' => 'Ưu tiên bàn gần cửa sổ.',
        ];

        $this->post(route('reservations.store'), $payload)
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas(Reservation::class, [
            'name' => 'Trần Bình',
            'phone' => '0987654321',
            'status' => 'pending',
        ]);
    }

    public function test_admin_branch_form_saves_reservation_notification_email(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();
        $branch = Branch::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.branches.edit', $branch))
            ->assertOk()
            ->assertSee('Email nhận thông báo đặt bàn');

        $this->actingAs($admin)
            ->put(route('admin.branches.update', $branch), [
                'name' => $branch->name,
                'email' => $branch->email,
                'reservation_notification_email' => 'reservations-branch@example.com',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.branches.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas(Branch::class, [
            'id' => $branch->id,
            'reservation_notification_email' => 'reservations-branch@example.com',
        ]);
    }

    public function test_reservation_sends_admin_mail_to_branch_notification_email(): void
    {
        Mail::fake();

        $branch = Branch::firstOrFail();
        $branch->update([
            'email' => 'public-branch@example.com',
            'reservation_notification_email' => 'reservations-branch@example.com',
        ]);

        $this->post(route('reservations.store'), [
            'name' => 'Khách báo mail cơ sở',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
        ])->assertRedirect(route('reservations.create'));

        Mail::assertQueued(ReservationAdminNotificationMail::class, fn ($mail): bool => $mail->hasTo('reservations-branch@example.com'));
        Mail::assertNotQueued(ReservationCustomerConfirmationMail::class);
    }

    public function test_reservation_admin_mail_falls_back_to_branch_email(): void
    {
        Mail::fake();

        $branch = Branch::firstOrFail();
        $branch->update([
            'email' => 'public-branch@example.com',
            'reservation_notification_email' => null,
        ]);

        $this->post(route('reservations.store'), [
            'name' => 'Khách fallback mail',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
        ])->assertRedirect(route('reservations.create'));

        Mail::assertQueued(ReservationAdminNotificationMail::class, fn ($mail): bool => $mail->hasTo('public-branch@example.com'));
    }

    public function test_reservation_sends_customer_confirmation_when_email_is_provided(): void
    {
        Mail::fake();

        $branch = Branch::firstOrFail();
        $branch->update(['reservation_notification_email' => 'reservations-branch@example.com']);

        $this->post(route('reservations.store'), [
            'name' => 'Khách nhận mail',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'email' => 'customer@gmail.com',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
        ])->assertRedirect(route('reservations.create'));

        Mail::assertQueued(ReservationAdminNotificationMail::class, fn ($mail): bool => $mail->hasTo('reservations-branch@example.com'));
        Mail::assertQueued(ReservationCustomerConfirmationMail::class, fn ($mail): bool => $mail->hasTo('customer@gmail.com'));
    }

    public function test_customer_confirmation_mail_shows_branch_hotline_and_phone(): void
    {
        $branch = Branch::firstOrFail();
        $branch->update([
            'hotline' => '0999 111 222',
            'phone' => '0888 333 444',
        ]);
        $reservation = Reservation::create([
            'name' => 'Khách hotline cơ sở',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'email' => 'customer@gmail.com',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'status' => 'pending',
        ])->load('branch');

        $html = (new ReservationCustomerConfirmationMail($reservation))->render();

        $this->assertStringContainsString('0999 111 222', $html);
        $this->assertStringContainsString('0888 333 444', $html);
    }

    public function test_customer_confirmation_mail_falls_back_to_branch_phone(): void
    {
        $branch = Branch::firstOrFail();
        $branch->update([
            'hotline' => null,
            'phone' => '0888 333 444',
        ]);
        $reservation = Reservation::create([
            'name' => 'Khách phone cơ sở',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'email' => 'customer@gmail.com',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'status' => 'pending',
        ])->load('branch');

        $html = (new ReservationCustomerConfirmationMail($reservation))->render();

        $this->assertStringContainsString('0888 333 444', $html);
    }

    public function test_public_english_pages_use_translated_client_copy(): void
    {
        foreach (['/en/reservation', '/en/contact', '/en/vegetarian-catering', '/en/vegetarian-restaurant-buon-ma-thuot'] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertDontSee('Gửi yêu cầu giữ bàn', false)
                ->assertDontSee('Cơ sở cần liên hệ', false)
                ->assertDontSee('Thông tin cơ sở', false);
        }

        $this->get('/en/reservation')
            ->assertSee('Reserve a calm vegetarian table')
            ->assertSee('Send request');

        $this->get('/en/contact')
            ->assertSee('Branches')
            ->assertSee('General contact');
    }

    public function test_public_vietnamese_reservation_page_keeps_vietnamese_copy(): void
    {
        $this->get(route('reservations.create'))
            ->assertOk()
            ->assertSee('Giữ chỗ cho một bữa chay an yên')
            ->assertSee('Gửi yêu cầu giữ bàn');
    }

    public function test_english_reservation_submit_uses_english_flash_and_validation(): void
    {
        Mail::fake();

        $branch = Branch::firstOrFail();

        $this->post(route('localized.reservations.store'), [
            'name' => 'English Guest',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 2,
        ])
            ->assertRedirect(route('localized.reservations.create'))
            ->assertSessionHas('success', 'Your reservation request has been sent. Dan Huong Chay will contact you to confirm.');

        $this->followingRedirects()
            ->from(route('localized.reservations.create'))
            ->post(route('localized.reservations.store'), [
                'phone' => 'bad',
            ])
            ->assertOk()
            ->assertSee('Please enter your full name.')
            ->assertSee('The phone number format is invalid.');
    }

    public function test_english_contact_submit_uses_english_flash_and_validation(): void
    {
        $this->post(route('localized.contact.store'), [
            'name' => 'English Contact',
            'email' => 'contact@gmail.com',
            'message' => 'I would like to ask about vegetarian catering this weekend.',
        ])
            ->assertRedirect(route('localized.contact'))
            ->assertSessionHas('success', 'Thank you for contacting us. Dan Huong Chay will respond as soon as possible.');

        $this->followingRedirects()
            ->from(route('localized.contact'))
            ->post(route('localized.contact.store'), [
                'email' => 'not-an-email',
                'message' => 'short',
            ])
            ->assertOk()
            ->assertSee('Please enter your full name.')
            ->assertSee('The email format is invalid.')
            ->assertSee('The message must be at least 10 characters.');
    }

    public function test_customer_confirmation_mail_renders_by_locale(): void
    {
        $branch = Branch::firstOrFail();
        $branch->update([
            'hotline' => '0999 111 222',
            'phone' => '0888 333 444',
        ]);

        $reservation = Reservation::create([
            'name' => 'Locale Guest',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'email' => 'customer@gmail.com',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'status' => 'pending',
        ])->load('branch');

        app()->setLocale('en');
        $englishHtml = (new ReservationCustomerConfirmationMail($reservation))->render();

        app()->setLocale('vi');
        $vietnameseHtml = (new ReservationCustomerConfirmationMail($reservation))->render();

        $this->assertStringContainsString('Thank you for sending your reservation request', $englishHtml);
        $this->assertStringContainsString('If you need to adjust your reservation details', $englishHtml);
        $this->assertStringNotContainsString('Cần điều chỉnh thông tin đặt bàn', $englishHtml);
        $this->assertStringContainsString('Cảm ơn bạn đã gửi yêu cầu đặt bàn', $vietnameseHtml);
        $this->assertStringContainsString('Cần điều chỉnh thông tin đặt bàn', $vietnameseHtml);
    }

    public function test_public_views_do_not_use_text_ternary_translation_pattern(): void
    {
        $violations = [];
        $root = resource_path('views');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));

        foreach ($files as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $path = str_replace('\\', '/', $file->getPathname());

            if (str_contains($path, '/views/admin/') || str_contains($path, '/views/auth/') || str_contains($path, '/views/emails/reservations/admin-notification.blade.php')) {
                continue;
            }

            if (str_contains(file_get_contents($path), 'is_english() ?')) {
                $violations[] = str_replace(str_replace('\\', '/', base_path()).'/', '', $path);
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_admin_settings_and_seo_english_tabs_have_auto_translate_controls(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('data-deepl-translate', false)
            ->assertSee('data-copy-translation', false)
            ->assertSee('data-translation-field="restaurant_name"', false);

        $this->actingAs($admin)
            ->get(route('admin.seo.edit'))
            ->assertOk()
            ->assertSee('data-deepl-translate', false)
            ->assertSee('data-copy-translation', false)
            ->assertSee('data-translation-field="default_meta_title"', false);
    }

    public function test_admin_seo_submit_still_saves_vietnamese_and_english_settings(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        $this->actingAs($admin)
            ->from(route('admin.seo.edit'))
            ->put(route('admin.seo.update'), [
                'default_meta_title' => 'Meta tiếng Việt',
                'default_meta_description' => 'Mô tả SEO tiếng Việt',
                'default_meta_keywords' => 'quán chay Hải Phòng',
                'default_meta_title_en' => 'English meta title',
                'default_meta_description_en' => 'English SEO description',
                'default_meta_keywords_en' => 'vegetarian restaurant Hai Phong',
                'google_analytics_code' => '<script>window.gaTest = true;</script>',
                'google_search_console' => '<meta name="google-site-verification" content="test-token">',
                'facebook_pixel_code' => '<script>window.fbTest = true;</script>',
                'robots_txt_content' => "User-agent: *\nAllow: /\n",
            ])
            ->assertRedirect(route('admin.seo.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame('Meta tiếng Việt', setting('default_meta_title'));
        $this->assertSame('English meta title', setting('default_meta_title_en'));
        $this->assertSame('<script>window.gaTest = true;</script>', setting('google_analytics_code'));
    }

    public function test_admin_gallery_form_saves_english_translation_and_frontend_uses_it(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();
        $image = GalleryImage::query()->where('location', 'space')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.gallery.edit', $image))
            ->assertOk()
            ->assertSee('data-deepl-translate', false)
            ->assertSee('Image title');

        $this->actingAs($admin)
            ->put(route('admin.gallery.update', $image), [
                'title' => $image->title,
                'slug' => $image->slug,
                'description' => $image->description,
                'alt_text' => $image->alt_text,
                'location' => 'space',
                'branch_id' => $image->branch_id,
                'sort_order' => $image->sort_order,
                'is_featured' => '1',
                'is_active' => '1',
                'meta_title' => $image->meta_title,
                'meta_description' => $image->meta_description,
                'translations' => [
                    'en' => [
                        'title' => 'Peaceful English Space',
                        'slug' => 'peaceful-english-space',
                        'description' => 'A calm vegetarian dining corner in English.',
                        'alt_text' => 'Peaceful English alt',
                        'meta_title' => 'Peaceful English meta',
                        'meta_description' => 'Peaceful English meta description.',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.gallery.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('gallery_image_translations', [
            'gallery_image_id' => $image->id,
            'locale' => 'en',
            'title' => 'Peaceful English Space',
            'slug' => 'peaceful-english-space',
            'alt_text' => 'Peaceful English alt',
        ]);

        $this->get(route('localized.gallery.index'))
            ->assertOk()
            ->assertSee('Peaceful English Space')
            ->assertSee('A calm vegetarian dining corner in English.')
            ->assertSee('alt="Peaceful English alt"', false);
    }

    public function test_admin_gallery_english_slug_must_be_unique(): void
    {
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();
        $images = GalleryImage::query()->where('location', 'space')->limit(2)->get();
        $this->assertCount(2, $images);

        [$first, $second] = [$images[0], $images[1]];

        $first->translations()->updateOrCreate(['locale' => 'en'], [
            'title' => 'First English Space',
            'slug' => 'duplicate-english-space',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.gallery.update', $second), [
                'title' => $second->title,
                'slug' => $second->slug,
                'description' => $second->description,
                'alt_text' => $second->alt_text,
                'location' => 'space',
                'branch_id' => $second->branch_id,
                'sort_order' => $second->sort_order,
                'is_featured' => '1',
                'is_active' => '1',
                'meta_title' => $second->meta_title,
                'meta_description' => $second->meta_description,
                'translations' => [
                    'en' => [
                        'title' => 'Second English Space',
                        'slug' => 'duplicate-english-space',
                    ],
                ],
            ])
            ->assertSessionHasErrors('translations.en.slug');
    }

    public function test_admin_english_tab_forms_have_auto_translate_controls_where_expected(): void
    {
        $root = resource_path('views/admin');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        $missing = [];

        foreach ($files as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $contents = file_get_contents($file->getPathname());

            if (! str_contains($contents, 'data-admin-tab="en"')) {
                continue;
            }

            if (! str_contains($contents, 'translation-fields') && ! str_contains($contents, 'setting-translation-actions')) {
                $missing[] = str_replace(str_replace('\\', '/', base_path()).'/', '', str_replace('\\', '/', $file->getPathname()));
            }
        }

        $this->assertSame([], $missing);
    }

    public function test_public_pages_render_core_seo_meta_and_hreflang(): void
    {
        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical" href="'.route('menu.index').'">', false)
            ->assertSee('<link rel="alternate" hreflang="vi" href="'.route('menu.index').'">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.route('localized.menu.index').'">', false)
            ->assertSee('<meta property="og:title"', false)
            ->assertSee('<meta property="og:image:alt"', false)
            ->assertSee('<meta name="twitter:image:alt"', false);

        $this->get(route('localized.menu.index'))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('localized.menu.index').'">', false)
            ->assertSee('<link rel="alternate" hreflang="vi" href="'.route('menu.index').'">', false);
    }

    public function test_detail_pages_render_locale_aware_alternates(): void
    {
        $dish = Dish::with('translations')->firstOrFail();
        $post = Post::published()->with('translations')->firstOrFail();

        $this->get(route('menu.show', $dish))
            ->assertOk()
            ->assertSee('<link rel="alternate" hreflang="en" href="'.route('localized.menu.show', ['slug' => $dish->localizedSlug('en')]).'">', false);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('<link rel="alternate" hreflang="en" href="'.route('localized.blog.show', ['slug' => $post->localizedSlug('en')]).'">', false)
            ->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_language_switcher_keeps_current_static_page(): void
    {
        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('<a href="'.route('localized.menu.index'), false);

        $this->get(route('localized.menu.index'))
            ->assertOk()
            ->assertSee('<a href="'.route('menu.index'), false);
    }

    public function test_language_switcher_keeps_current_detail_page_with_translated_slug(): void
    {
        $dish = Dish::with('translations')->firstOrFail();
        $englishDishUrl = route('localized.menu.show', ['slug' => $dish->localizedSlug('en')]);

        $this->get(route('menu.show', $dish))
            ->assertOk()
            ->assertSee('<a href="'.$englishDishUrl, false);

        $this->get($englishDishUrl)
            ->assertOk()
            ->assertSee('<a href="'.route('menu.show', $dish), false);
    }

    public function test_sitemap_contains_public_localized_urls_and_excludes_private_or_filter_urls(): void
    {
        $dish = Dish::with('translations')->firstOrFail();
        $post = Post::published()->with('translations')->firstOrFail();

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('xmlns:xhtml="http://www.w3.org/1999/xhtml"', false)
            ->assertSee(route('home'), false)
            ->assertSee(route('localized.home'), false)
            ->assertSee(route('localized.menu.index'), false)
            ->assertSee(route('localized.menu.show', ['slug' => $dish->localizedSlug('en')]), false)
            ->assertSee(route('localized.blog.show', ['slug' => $post->localizedSlug('en')]), false)
            ->assertDontSee('/admin', false)
            ->assertDontSee('/login', false)
            ->assertDontSee('?category=', false);
    }

    public function test_robots_fallback_points_to_sitemap_and_blocks_private_paths(): void
    {
        SiteSetting::where('key', 'robots_txt_content')->delete();

        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('User-agent: *')
            ->assertSee('Allow: /')
            ->assertSee('Disallow: /admin')
            ->assertSee('Disallow: /login')
            ->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_restaurant_schema_is_multi_branch_and_dish_schema_has_product_offer(): void
    {
        $dish = Dish::with(['category.translations', 'translations'])->firstOrFail();
        $branch = Branch::firstOrFail();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('"@graph"', false)
            ->assertSee('"LocalBusiness"', false)
            ->assertSee($branch->name, false);

        $this->get(route('menu.show', $dish))
            ->assertOk()
            ->assertSee('"@type": "MenuItem"', false)
            ->assertSee('"@type": "Product"', false)
            ->assertSee('"@type": "Offer"', false)
            ->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_google_tracking_uses_admin_settings_before_env_fallback(): void
    {
        config([
            'services.google.analytics_id' => 'G-FALLBACK123',
            'services.google.site_verification' => 'fallback-verification-token',
        ]);

        SiteSetting::set('google_analytics_code', null, 'text', 'seo');
        SiteSetting::set('google_search_console', null, 'text', 'seo');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('gtag/js?id=G-FALLBACK123', false)
            ->assertSee('content="fallback-verification-token"', false);

        SiteSetting::set('google_analytics_code', '<script>window.adminGa = true;</script>', 'text', 'seo');
        SiteSetting::set('google_search_console', '<meta name="google-site-verification" content="admin-token">', 'text', 'seo');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('window.adminGa = true;', false)
            ->assertSee('content="admin-token"', false)
            ->assertDontSee('G-FALLBACK123', false)
            ->assertDontSee('fallback-verification-token', false);
    }

    public function test_public_pages_keep_a_single_h1(): void
    {
        $dish = Dish::firstOrFail();
        $post = Post::published()->firstOrFail();

        foreach ([
            route('home'),
            route('about'),
            route('gallery.index'),
            route('menu.index'),
            route('menu.show', $dish),
            route('blog.index'),
            route('blog.show', $post),
            route('reservations.create'),
            route('contact'),
            route('local.vegetarian-restaurant-hai-phong'),
            route('local.vegetarian-catering-hai-phong'),
            route('localized.home'),
            route('localized.menu.index'),
            route('localized.reservations.create'),
        ] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            preg_match_all('/<h1\b/i', $html, $matches);

            $this->assertCount(1, $matches[0], "Expected exactly one H1 on {$url}.");
        }
    }

    public function test_reservation_still_succeeds_when_mail_fails(): void
    {
        $branch = Branch::firstOrFail();
        $branch->update(['reservation_notification_email' => 'reservations-branch@example.com']);

        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new \RuntimeException('SMTP down'));

        $this->post(route('reservations.store'), [
            'name' => 'Khách lỗi mail',
            'branch_id' => $branch->id,
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
        ])
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas(Reservation::class, [
            'name' => 'Khách lỗi mail',
            'phone' => '0987654321',
            'status' => 'pending',
        ]);
    }

    public function test_reservation_rejects_time_outside_opening_hours(): void
    {
        SiteSetting::set('opening_hours', '09:00 - 21:30 hằng ngày', 'text', 'general');

        $this->post(route('reservations.store'), [
            'name' => 'Khách đặt sớm',
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '08:30',
            'guests' => 2,
        ])->assertSessionHasErrors('reservation_time');
    }

    public function test_reservation_rejects_past_time_today(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 15, 0, 0, config('app.timezone')));
        SiteSetting::set('opening_hours', '09:00 - 21:30 hằng ngày', 'text', 'general');

        $this->post(route('reservations.store'), [
            'name' => 'Khách đặt hôm nay',
            'phone' => '0987654321',
            'reservation_date' => today()->toDateString(),
            'reservation_time' => '14:30',
            'guests' => 2,
        ])->assertSessionHasErrors('reservation_time');

        Carbon::setTestNow();
    }

    public function test_reservation_respects_split_slots_and_last_booking_time(): void
    {
        SiteSetting::set('reservation_time_slots', '09:00-14:00,16:00-21:00', 'text', 'general');
        SiteSetting::set('reservation_last_booking_time', '20:30', 'text', 'general');
        SiteSetting::set('reservation_last_order_buffer_minutes', '30', 'text', 'general');

        $payload = [
            'name' => 'Khách kiểm tra khung giờ',
            'phone' => '0987654321',
            'reservation_date' => now()->addDay()->toDateString(),
            'guests' => 2,
        ];

        $this->post(route('reservations.store'), $payload + ['reservation_time' => '15:00'])
            ->assertSessionHasErrors('reservation_time');

        $this->post(route('reservations.store'), $payload + ['reservation_time' => '20:45'])
            ->assertSessionHasErrors('reservation_time');

        $this->post(route('reservations.store'), $payload + ['reservation_time' => '20:30'])
            ->assertSessionHasNoErrors();
    }

    public function test_admin_reservations_default_to_the_next_seven_days(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 10, 0, 0, config('app.timezone')));
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        Reservation::create([
            'name' => 'Default Today Guest',
            'phone' => '0900000001',
            'reservation_date' => now()->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);
        Reservation::create([
            'name' => 'Default Week Guest',
            'phone' => '0900000002',
            'reservation_date' => now()->addDays(7)->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'status' => 'confirmed',
        ]);
        Reservation::create([
            'name' => 'Default Outside Guest',
            'phone' => '0900000003',
            'reservation_date' => now()->addDays(8)->toDateString(),
            'reservation_time' => '19:00',
            'guests' => 3,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee('Default Today Guest')
            ->assertSee('Default Week Guest')
            ->assertDontSee('Default Outside Guest');

        Carbon::setTestNow();
    }

    public function test_admin_reservations_date_filter_only_shows_that_day(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 10, 0, 0, config('app.timezone')));
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        Reservation::create([
            'name' => 'Exact Date Guest',
            'phone' => '0900000004',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);
        Reservation::create([
            'name' => 'Other Date Guest',
            'phone' => '0900000005',
            'reservation_date' => now()->addDays(2)->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reservations.index', ['date' => now()->addDay()->toDateString(), 'status' => 'active']))
            ->assertOk()
            ->assertSee('Exact Date Guest')
            ->assertDontSee('Other Date Guest');

        Carbon::setTestNow();
    }

    public function test_admin_reservations_from_to_filter_shows_the_selected_range(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 10, 0, 0, config('app.timezone')));
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        Reservation::create([
            'name' => 'Range Inside Guest',
            'phone' => '0900000006',
            'reservation_date' => now()->addDays(4)->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);
        Reservation::create([
            'name' => 'Range Outside Guest',
            'phone' => '0900000007',
            'reservation_date' => now()->addDays(6)->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reservations.index', [
                'from' => now()->addDays(5)->toDateString(),
                'to' => now()->addDays(3)->toDateString(),
                'status' => 'active',
            ]))
            ->assertOk()
            ->assertSee('Range Inside Guest')
            ->assertDontSee('Range Outside Guest');

        Carbon::setTestNow();
    }

    public function test_admin_reservations_search_is_not_limited_to_the_default_week(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 10, 0, 0, config('app.timezone')));
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        Reservation::create([
            'name' => 'Far Search Guest',
            'phone' => '0900000008',
            'reservation_date' => now()->addDays(30)->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'completed',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reservations.index', ['q' => 'Far Search']))
            ->assertOk()
            ->assertSee('Far Search Guest');

        Carbon::setTestNow();
    }

    public function test_admin_dashboard_keeps_today_shift_and_shows_upcoming_week(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 5, 18, 10, 0, 0, config('app.timezone')));
        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();

        Reservation::create([
            'name' => 'Dashboard Today Guest',
            'phone' => '0900000009',
            'reservation_date' => now()->toDateString(),
            'reservation_time' => '18:00',
            'guests' => 2,
            'status' => 'pending',
        ]);
        Reservation::create([
            'name' => 'Dashboard Upcoming Guest',
            'phone' => '0900000010',
            'reservation_date' => now()->addDays(5)->toDateString(),
            'reservation_time' => '18:30',
            'guests' => 4,
            'status' => 'confirmed',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Today Guest')
            ->assertSee('Dashboard Upcoming Guest')
            ->assertSee('Đặt bàn 7 ngày tới');

        Carbon::setTestNow();
    }

    public function test_uploaded_images_are_resized_and_converted_to_webp(): void
    {
        Storage::fake('public');

        $path = app(UploadService::class)->uploadImage(
            UploadedFile::fake()->image('large-menu-photo.png', 2600, 1800)->size(9000),
            'dishes'
        );

        Storage::disk('public')->assertExists($path);

        [$width, $height] = getimagesize(Storage::disk('public')->path($path));
        $folderProfile = config('uploads.folder_profiles.dishes', 'default');
        $profile = config("uploads.profiles.{$folderProfile}", config('uploads.profiles.default'));

        $this->assertStringEndsWith('.webp', $path);
        $this->assertLessThanOrEqual((int) ($profile['width'] ?? config('uploads.resize_width')), $width);
        $this->assertLessThanOrEqual((int) ($profile['height'] ?? config('uploads.resize_height')), $height);
    }

    public function test_admin_user_avatar_accepts_large_png_upload(): void
    {
        Storage::fake('public');

        $admin = User::where('email', 'admin@danhuongchay.vn')->firstOrFail();
        $avatar = UploadedFile::fake()->image('avatar.png', 2390, 1792)->size(3700);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'avatar' => $avatar,
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasNoErrors();

        $admin->refresh();

        $this->assertStringEndsWith('.webp', $admin->avatar);
        Storage::disk('public')->assertExists($admin->avatar);
    }
}

<?php

namespace Tests\Feature;

use App\Models\ChatSession;
use App\Models\Contact;
use App\Models\Dish;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

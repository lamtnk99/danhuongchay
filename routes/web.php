<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DishController as AdminDishController;
use App\Http\Controllers\Admin\GalleryImageController as AdminGalleryImageController;
use App\Http\Controllers\Admin\NavigationMenuController as AdminNavigationMenuController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\TranslationController as AdminTranslationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalSeoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function (): void {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', AdminDashboardController::class)->middleware('permission:dashboard.view')->name('dashboard');
        Route::get('/notifications', AdminNotificationController::class)->name('notifications.index');

        Route::get('/settings', [AdminSettingController::class, 'edit'])->middleware('permission:settings.view')->name('settings.edit');
        Route::put('/settings', [AdminSettingController::class, 'update'])->middleware('permission:settings.update')->name('settings.update');
        Route::get('/identity', [AdminSettingController::class, 'identity'])->middleware('permission:identity.view')->name('identity.edit');
        Route::put('/identity', [AdminSettingController::class, 'updateIdentity'])->middleware('permission:identity.update')->name('identity.update');
        Route::get('/seo', [AdminSettingController::class, 'seo'])->middleware('permission:seo.view')->name('seo.edit');
        Route::put('/seo', [AdminSettingController::class, 'updateSeo'])->middleware('permission:seo.update')->name('seo.update');
        Route::get('/translations/settings', [AdminTranslationController::class, 'settings'])->middleware('permission:translations.view')->name('translations.settings');
        Route::put('/translations/settings', [AdminTranslationController::class, 'updateSettings'])->middleware('permission:translations.update')->name('translations.settings.update');
        Route::get('/translations/usage', [AdminTranslationController::class, 'usage'])->middleware('throttle:20,1')->name('translations.usage');
        Route::post('/translations/test', [AdminTranslationController::class, 'test'])->middleware('throttle:10,1')->name('translations.test');
        Route::post('/translations/translate', [AdminTranslationController::class, 'translate'])->middleware(['throttle:20,1', 'permission:translations.auto_translate'])->name('translations.translate');
        Route::get('/translations/deepl/usage', [AdminTranslationController::class, 'usage'])->middleware('throttle:20,1')->name('translations.deepl.usage');
        Route::post('/translations/deepl/test', [AdminTranslationController::class, 'test'])->middleware('throttle:10,1')->name('translations.deepl.test');
        Route::post('/translations/deepl', [AdminTranslationController::class, 'translate'])->middleware(['throttle:20,1', 'permission:translations.auto_translate'])->name('translations.deepl.translate');

        Route::patch('/banners/{banner}/toggle', [AdminBannerController::class, 'toggle'])->middleware('permission:banners.update')->name('banners.toggle');
        Route::resource('branches', AdminBranchController::class)->except('show')
            ->middlewareFor(['index'], 'permission:branches.view')
            ->middlewareFor(['create', 'store'], 'permission:branches.create')
            ->middlewareFor(['edit', 'update'], 'permission:branches.update')
            ->middlewareFor(['destroy'], 'permission:branches.delete');
        Route::resource('banners', AdminBannerController::class)->except('show')
            ->middlewareFor(['index'], 'permission:banners.view')
            ->middlewareFor(['create', 'store'], 'permission:banners.create')
            ->middlewareFor(['edit', 'update'], 'permission:banners.update')
            ->middlewareFor(['destroy'], 'permission:banners.delete');
        Route::resource('categories', AdminCategoryController::class)->except('show')
            ->middlewareFor(['index'], 'permission:categories.view')
            ->middlewareFor(['create', 'store'], 'permission:categories.create')
            ->middlewareFor(['edit', 'update'], 'permission:categories.update')
            ->middlewareFor(['destroy'], 'permission:categories.delete');
        Route::resource('dishes', AdminDishController::class)->except('show')
            ->middlewareFor(['index'], 'permission:dishes.view')
            ->middlewareFor(['create', 'store'], 'permission:dishes.create')
            ->middlewareFor(['edit', 'update'], 'permission:dishes.update')
            ->middlewareFor(['destroy'], 'permission:dishes.delete');
        Route::resource('posts', AdminPostController::class)->except('show')
            ->middlewareFor(['index'], 'permission:posts.view')
            ->middlewareFor(['create', 'store'], 'permission:posts.create')
            ->middlewareFor(['edit', 'update'], 'permission:posts.update')
            ->middlewareFor(['destroy'], 'permission:posts.delete');
        Route::resource('pages', AdminPageController::class)->except('show')
            ->middlewareFor(['index'], 'permission:pages.view')
            ->middlewareFor(['create', 'store'], 'permission:pages.create')
            ->middlewareFor(['edit', 'update'], 'permission:pages.update')
            ->middlewareFor(['destroy'], 'permission:pages.delete');
        Route::resource('menus', AdminNavigationMenuController::class)->except('show')
            ->middlewareFor(['index'], 'permission:menus.view')
            ->middlewareFor(['create', 'store'], 'permission:menus.create')
            ->middlewareFor(['edit', 'update'], 'permission:menus.update')
            ->middlewareFor(['destroy'], 'permission:menus.delete');
        Route::resource('testimonials', AdminTestimonialController::class)->except('show')
            ->middlewareFor(['index'], 'permission:testimonials.view')
            ->middlewareFor(['create', 'store'], 'permission:testimonials.create')
            ->middlewareFor(['edit', 'update'], 'permission:testimonials.update')
            ->middlewareFor(['destroy'], 'permission:testimonials.delete');
        Route::resource('promotions', AdminPromotionController::class)->except('show')
            ->middlewareFor(['index'], 'permission:promotions.view')
            ->middlewareFor(['create', 'store'], 'permission:promotions.create')
            ->middlewareFor(['edit', 'update'], 'permission:promotions.update')
            ->middlewareFor(['destroy'], 'permission:promotions.delete');
        Route::resource('gallery', AdminGalleryImageController::class)->except('show')
            ->middlewareFor(['index'], 'permission:gallery.view')
            ->middlewareFor(['create', 'store'], 'permission:gallery.create')
            ->middlewareFor(['edit', 'update'], 'permission:gallery.update')
            ->middlewareFor(['destroy'], 'permission:gallery.delete');
        Route::resource('reservations', AdminReservationController::class)->only(['index', 'show', 'update', 'destroy'])
            ->middlewareFor(['index', 'show'], 'permission:reservations.view')
            ->middlewareFor(['update'], 'permission:reservations.update')
            ->middlewareFor(['destroy'], 'permission:reservations.delete');
        Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'update', 'destroy'])
            ->middlewareFor(['index', 'show'], 'permission:contacts.view')
            ->middlewareFor(['update'], 'permission:contacts.update')
            ->middlewareFor(['destroy'], 'permission:contacts.delete');
        Route::get('/chats', [AdminChatController::class, 'index'])->middleware('permission:chats.view')->name('chats.index');
        Route::get('/chats/{chat}', [AdminChatController::class, 'show'])->middleware('permission:chats.view')->name('chats.show');
        Route::get('/chats/{chat}/messages', [AdminChatController::class, 'messages'])->middleware('permission:chats.view')->name('chats.messages');
        Route::post('/chats/{chat}/reply', [AdminChatController::class, 'reply'])->middleware('permission:chats.reply')->name('chats.reply');
        Route::patch('/chats/{chat}', [AdminChatController::class, 'update'])->middleware('permission:chats.update')->name('chats.update');
        Route::delete('/chats/{chat}', [AdminChatController::class, 'destroy'])->middleware('permission:chats.delete')->name('chats.destroy');
        Route::resource('users', AdminUserController::class)->except('show')
            ->middlewareFor(['index'], 'permission:users.view')
            ->middlewareFor(['create', 'store'], 'permission:users.create')
            ->middlewareFor(['edit', 'update'], 'permission:users.update')
            ->middlewareFor(['destroy'], 'permission:users.delete');
        Route::resource('roles', AdminRoleController::class)->except('show')
            ->middlewareFor(['index'], 'permission:roles.view')
            ->middlewareFor(['create', 'store'], 'permission:roles.create')
            ->middlewareFor(['edit', 'update'], 'permission:roles.update')
            ->middlewareFor(['destroy'], 'permission:roles.delete');
    });

Route::get('/', HomeController::class)->name('home');
Route::get('/quan-chay-hai-phong', [LocalSeoController::class, 'vegetarianRestaurantHaiPhong'])->name('local.vegetarian-restaurant-hai-phong');
Route::get('/quan-chay-buon-ma-thuot', [LocalSeoController::class, 'vegetarianRestaurantBuonMaThuot'])->name('local.vegetarian-restaurant-buon-ma-thuot');
Route::get('/dat-tiec-chay', [LocalSeoController::class, 'cateringHub'])->name('local.vegetarian-catering');
Route::get('/dat-tiec-chay-hai-phong', [LocalSeoController::class, 'vegetarianCateringHaiPhong'])->name('local.vegetarian-catering-hai-phong');
Route::get('/dat-tiec-chay-buon-ma-thuot', [LocalSeoController::class, 'vegetarianCateringBuonMaThuot'])->name('local.vegetarian-catering-buon-ma-thuot');
Route::redirect('/mam-cung-chay-hai-phong', '/dat-tiec-chay-hai-phong');
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('about');
Route::get('/khong-gian', GalleryController::class)->name('gallery.index');
Route::get('/trang/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/thuc-don', [MenuController::class, 'index'])->name('menu.index');
Route::get('/mon-an/{dish:slug}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/dat-ban', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/dat-ban', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/lien-he', [ContactController::class, 'create'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
Route::get('/chat/{chatSession}/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/{chatSession}/messages', [ChatController::class, 'send'])->name('chat.send');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    $content = setting('robots_txt_content') ?: "User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n";

    return response(
        $content,
        200,
        ['Content-Type' => 'text/plain']
    );
})->name('robots');

Route::prefix('en')
    ->as('localized.')
    ->middleware('locale:en')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/vegetarian-restaurant-hai-phong', [LocalSeoController::class, 'vegetarianRestaurantHaiPhong'])->name('local.vegetarian-restaurant-hai-phong');
        Route::get('/vegetarian-restaurant-buon-ma-thuot', [LocalSeoController::class, 'vegetarianRestaurantBuonMaThuot'])->name('local.vegetarian-restaurant-buon-ma-thuot');
        Route::get('/vegetarian-catering', [LocalSeoController::class, 'cateringHub'])->name('local.vegetarian-catering');
        Route::get('/vegetarian-catering-hai-phong', [LocalSeoController::class, 'vegetarianCateringHaiPhong'])->name('local.vegetarian-catering-hai-phong');
        Route::get('/vegetarian-catering-buon-ma-thuot', [LocalSeoController::class, 'vegetarianCateringBuonMaThuot'])->name('local.vegetarian-catering-buon-ma-thuot');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/space', GalleryController::class)->name('gallery.index');
        Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');

        Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/dishes/{slug}', [MenuController::class, 'show'])->name('menu.show');

        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

        Route::get('/reservation', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservation', [ReservationController::class, 'store'])->name('reservations.store');

        Route::get('/contact', [ContactController::class, 'create'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    });

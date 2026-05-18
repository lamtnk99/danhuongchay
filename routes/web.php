<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
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
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
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
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/notifications', AdminNotificationController::class)->name('notifications.index');

        Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/identity', [AdminSettingController::class, 'identity'])->name('identity.edit');
        Route::put('/identity', [AdminSettingController::class, 'updateIdentity'])->name('identity.update');
        Route::get('/seo', [AdminSettingController::class, 'seo'])->name('seo.edit');
        Route::put('/seo', [AdminSettingController::class, 'updateSeo'])->name('seo.update');

        Route::patch('/banners/{banner}/toggle', [AdminBannerController::class, 'toggle'])->name('banners.toggle');
        Route::resource('banners', AdminBannerController::class)->except('show');
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('dishes', AdminDishController::class)->except('show');
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::resource('pages', AdminPageController::class)->except('show');
        Route::resource('menus', AdminNavigationMenuController::class)->except('show');
        Route::resource('testimonials', AdminTestimonialController::class)->except('show');
        Route::resource('promotions', AdminPromotionController::class)->except('show');
        Route::resource('gallery', AdminGalleryImageController::class)->except('show');
        Route::resource('reservations', AdminReservationController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{chat}', [AdminChatController::class, 'show'])->name('chats.show');
        Route::get('/chats/{chat}/messages', [AdminChatController::class, 'messages'])->name('chats.messages');
        Route::post('/chats/{chat}/reply', [AdminChatController::class, 'reply'])->name('chats.reply');
        Route::patch('/chats/{chat}', [AdminChatController::class, 'update'])->name('chats.update');
        Route::delete('/chats/{chat}', [AdminChatController::class, 'destroy'])->name('chats.destroy');
        Route::resource('users', AdminUserController::class)->except('show');
    });

Route::get('/', HomeController::class)->name('home');
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

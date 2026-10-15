<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkIdMessageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\PublicShopController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', [LocaleController::class, 'setLocale'])
    ->where('locale', 'id|en')
    ->name('locale.set');

/*
|--------------------------------------------------------------------------
| Landing page
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Guest-only authentication routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated user dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [EditorController::class, 'index'])->name('index');
    Route::get('shop', [ProductController::class, 'index'])->name('shop');
    Route::get('marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
    Route::post('marketplace/products/{product}/chat', [MarketplaceController::class, 'startChat'])->name('marketplace.products.chat');
    Route::get('shop/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('shop/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    Route::post('header', [EditorController::class, 'header'])->name('header');
    Route::post('design', [EditorController::class, 'design'])->name('design');
    Route::post('enhance', [EditorController::class, 'enhance'])->name('enhance');

    Route::get('short-links', [ShortLinkController::class, 'index'])->name('short-links.index');
    Route::post('short-links', [ShortLinkController::class, 'store'])->name('short-links.store');
    Route::put('short-links/{shortLink}', [ShortLinkController::class, 'update'])->name('short-links.update');
    Route::delete('short-links/{shortLink}', [ShortLinkController::class, 'destroy'])->name('short-links.destroy');
    Route::patch('short-links/{shortLink}/toggle', [ShortLinkController::class, 'toggle'])->name('short-links.toggle');

    Route::get('links', [LinkController::class, 'index'])->name('links.index');
    Route::post('links', [LinkController::class, 'store'])->name('links.store');
    Route::put('links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::patch('links/{link}/toggle', [LinkController::class, 'toggle'])->name('links.toggle');
    Route::post('links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');

    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::post('products/reorder', [ProductController::class, 'reorder'])->name('products.reorder');
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
    Route::delete('products/{product}/reviews/{review}', [ReviewController::class, 'destroy'])->name('products.reviews.destroy');
    Route::patch('products/{product}/reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('products.reviews.toggle');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');

    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/{order}', [PurchaseController::class, 'show'])->name('purchases.show');

    Route::get('appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::post('appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('accounts/{user}/follow', [FollowController::class, 'toggle'])->name('accounts.follow.toggle');

    Route::prefix('linkid')->name('linkid.')->group(function () {
        Route::get('messages', [LinkIdMessageController::class, 'index'])->name('messages');
        Route::get('messages/unread', [LinkIdMessageController::class, 'unread'])->name('messages.unread');
        Route::get('messages/{conversation}', [LinkIdMessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{conversation}', [LinkIdMessageController::class, 'store'])->name('messages.store');
    });

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/chart-data', [AnalyticsController::class, 'chartData'])->name('analytics.chart-data');

    Route::get('settings', [ProfileController::class, 'edit'])->name('settings.index');
    Route::post('settings/account', [ProfileController::class, 'updateAccount'])->name('settings.account');
    Route::delete('settings/account', [ProfileController::class, 'destroyAccount'])->name('settings.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('users', [AdminController::class, 'users'])->name('users');
    Route::get('users/{user}', [AdminController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/toggle', [AdminController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Shopping cart & checkout (public, guest friendly)
|--------------------------------------------------------------------------
*/
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add/{product}', [CartController::class, 'add'])
    ->where('product', '[0-9]+')
    ->name('cart.add');
Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('public.checkout.success');

Route::middleware('auth')->group(function () {
    Route::post('addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});

Route::post('order/{order}/payment-proof', [PublicShopController::class, 'uploadProof'])
    ->where('order', '[0-9]+')
    ->middleware('signed')
    ->name('public.order.proof');

/*
|--------------------------------------------------------------------------
| Public link-in-bio pages
|--------------------------------------------------------------------------
| Kept at the very bottom so it never shadows the routes above. Those
| routes always win for their exact paths, so a reserved word below can
| never be claimed as a public username either (e.g. /admin stays the
| admin panel instead of silently rendering someone's profile).
*/
Route::get('{username}/l/{link}', [PublicProfileController::class, 'redirectLink'])
    ->where('link', '[0-9]+')
    ->where('username', PublicProfileController::USERNAME_PATTERN)
    ->name('public.link.redirect');

Route::get('{username}/connections/{connection}', [FollowController::class, 'connections'])
    ->where('connection', 'followers|following')
    ->where('username', PublicProfileController::USERNAME_PATTERN)
    ->name('public.connections');

Route::get('{username}/product/{product}', [PublicShopController::class, 'show'])
    ->where('product', '[0-9]+')
    ->where('username', PublicProfileController::USERNAME_PATTERN)
    ->name('public.product.show');

Route::get('{username}/order/{order}', [PublicShopController::class, 'confirmation'])
    ->where('order', '[0-9]+')
    ->where('username', PublicProfileController::USERNAME_PATTERN)
    ->middleware('signed')
    ->name('public.order.confirmation');

Route::get('{username}', [PublicProfileController::class, 'show'])
    ->where('username', PublicProfileController::USERNAME_PATTERN)
    ->name('public.profile');





use App\Http\Controllers\Payment\TreePayController;

// TreePay payment routes
Route::post('treepay/callback', [TreePayController::class, 'callback'])->name('treepay.callback');
Route::get('treepay/success', [TreePayController::class, 'success'])->name('treepay.success');
Route::get('treepay/failed', [TreePayController::class, 'failed'])->name('treepay.failed');

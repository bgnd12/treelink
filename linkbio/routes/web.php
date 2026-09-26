<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', [LocaleController::class, 'setLocale'])->name('locale.set');

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

    Route::post('header', [EditorController::class, 'header'])->name('header');
    Route::post('design', [EditorController::class, 'design'])->name('design');
    Route::post('enhance', [EditorController::class, 'enhance'])->name('enhance');

    Route::get('short-links', [App\Http\Controllers\ShortLinkController::class, 'index'])->name('short-links.index');
    Route::post('short-links', [App\Http\Controllers\ShortLinkController::class, 'store'])->name('short-links.store');
    Route::put('short-links/{shortLink}', [App\Http\Controllers\ShortLinkController::class, 'update'])->name('short-links.update');
    Route::delete('short-links/{shortLink}', [App\Http\Controllers\ShortLinkController::class, 'destroy'])->name('short-links.destroy');
    Route::patch('short-links/{shortLink}/toggle', [App\Http\Controllers\ShortLinkController::class, 'toggle'])->name('short-links.toggle');

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

    Route::get('appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::post('appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');

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
| Public link-in-bio pages
|--------------------------------------------------------------------------
| Kept at the very bottom so it never shadows the routes above.
*/
Route::get('{username}/l/{link}', [PublicProfileController::class, 'redirectLink'])
    ->where('username', '[A-Za-z0-9_.]+')
    ->where('link', '[0-9]+')
    ->name('public.link.redirect');

Route::get('{username}', [PublicProfileController::class, 'show'])
    ->where('username', '[A-Za-z0-9_.]+')
    ->name('public.profile');

    Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en']), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.set');

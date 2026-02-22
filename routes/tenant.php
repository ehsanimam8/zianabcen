<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [\App\Http\Controllers\FrontendController::class, 'index'])->name('home');
    \Livewire\Volt\Volt::route('/programs', 'frontend.programs.index')->name('frontend.programs.index');
    \Livewire\Volt\Volt::route('/programs/{id}', 'frontend.programs.show')->name('frontend.programs.show');
    \Livewire\Volt\Volt::route('/events', 'frontend.events.index')->name('frontend.events.index');
    \Livewire\Volt\Volt::route('/announcements', 'frontend.posts.index')->name('frontend.posts.index');
    \Livewire\Volt\Volt::route('/announcements/{slug}', 'frontend.posts.show')->name('frontend.posts.show');
    \Livewire\Volt\Volt::route('/cart', 'frontend.cart')->name('frontend.cart');

    Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('stripe.webhook');

    // Student Dashboard Routes
    Route::group(['prefix' => 'portal'], function () {
        \Livewire\Volt\Volt::route('/dashboard', 'student.dashboard')->name('student.dashboard');
        \Livewire\Volt\Volt::route('/calendar', 'student.calendar')->name('student.calendar');
        \Livewire\Volt\Volt::route('/messages', 'student.messages')->name('student.messages');
        \Livewire\Volt\Volt::route('/lms/{course}', 'student.lms.course-viewer')->name('student.course.viewer');
    });
});

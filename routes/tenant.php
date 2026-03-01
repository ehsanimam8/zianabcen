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
    \Livewire\Volt\Volt::route('/events/{slug}', 'frontend.events.show')->name('frontend.events.show');
    \Livewire\Volt\Volt::route('/events/{slug}/register', 'frontend.events.register')->name('frontend.events.register');
    \Livewire\Volt\Volt::route('/announcements', 'frontend.posts.index')->name('frontend.posts.index');
    \Livewire\Volt\Volt::route('/announcements/{slug}', 'frontend.posts.show')->name('frontend.posts.show');
    \Livewire\Volt\Volt::route('/about/{slug}', 'frontend.pages.show')->name('frontend.pages.show');
    \Livewire\Volt\Volt::route('/cart', 'frontend.cart')->name('frontend.cart');

    Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('stripe.webhook');

    // Auth & Registration Routes (with rate limiting built-in via 'throttle')
    Route::middleware(['throttle:6,1', 'guest'])->group(function () {
        \Livewire\Volt\Volt::route('/login', 'auth.login')->name('login');
        \Livewire\Volt\Volt::route('/register', 'auth.register')->name('student.register');
    });

    Route::post('/logout', function() {
        \Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home');
    })->name('student.logout')->middleware('auth');

    // Student Dashboard Routes
    Route::group(['prefix' => 'portal', 'middleware' => ['auth']], function () {
        \Livewire\Volt\Volt::route('/dashboard', 'student.dashboard')->name('student.dashboard');
        \Livewire\Volt\Volt::route('/calendar', 'student.calendar')->name('student.calendar');
        \Livewire\Volt\Volt::route('/grades', 'student.grades')->name('student.grades');
        \Livewire\Volt\Volt::route('/messages', 'student.messages')->name('student.messages');
        \Livewire\Volt\Volt::route('/lms/{course}', 'student.lms.course-viewer')->name('student.course.viewer');
        
        // Secure media access gating
        Route::get('/stream/{lesson}', [\App\Http\Controllers\MediaStreamController::class, 'stream'])->name('student.media.stream');
        
        // PDF Generators
        Route::get('/transcript/{student}', [\App\Http\Controllers\PdfController::class, 'transcript'])->name('student.transcript.download');
        Route::get('/certificate/{student}/{type}/{id}', [\App\Http\Controllers\PdfController::class, 'certificate'])->name('student.certificate.download');
    });
});

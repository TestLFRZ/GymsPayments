<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\TenantController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('members', function () {
        return Inertia::render('members/Index');
    })->name('members.index');

    Route::get('plans', function () {
        return Inertia::render('plans/Index');
    })->name('plans.index');

    Route::get('payments', function () {
        return Inertia::render('payments/Index');
    })->name('payments.index');

    Route::get('alerts', function () {
        return Inertia::render('alerts/Index');
    })->name('alerts.index');

    Route::prefix('admin')->group(function () {
        Route::resource('tenants', TenantController::class)->names('admin.tenants');
    });
});

require __DIR__ . '/settings.php';

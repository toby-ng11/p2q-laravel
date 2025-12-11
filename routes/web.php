<?php

use App\Http\Controllers\ArchitectController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('welcome', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    })->name('home');

    Route::get('preferences/{key}', [UserPreferenceController::class, 'show'])->name('user-preference.show');
    Route::post('preferences/{key}', [UserPreferenceController::class, 'update'])->name('user-preference.update');

    Route::resources([
        'architects' => ArchitectController::class,
    ]);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/dashboards.php';

<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::name('dashboard.')->group(function () {
                Route::get('/home', 'home')->name('home');

                Route::middleware('admin')->group(function () {
                    Route::get('/admin', 'admin')->name('admin');

                    Route::prefix('/admin')->group(function () {
                        Route::get('/projects', 'adminProjects');
                        Route::get('/quotes', 'adminQuotes');
                        Route::get('/users', 'adminUsers');
                    });
                });

                Route::get('/opportunity', 'opportunity')->name('opportunity');
                Route::get('/project', 'project')->name('project');
                Route::get('/architect', 'architect')->name('architect');
                Route::get('/quote', 'quote')->name('quote');
            });
        });
    });
});

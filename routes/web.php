<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\Auth\LandlordAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Landlord\LandlordController;
use App\Http\Controllers\Tenant\PropertyController;
use App\Http\Controllers\Tenant\MessageController;

// ─── HOME ───────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ─── TENANT AUTH ────────────────────────────────────
Route::get('/register', [TenantAuthController::class, 'showRegister'])->name('tenant.register');
Route::post('/register', [TenantAuthController::class, 'register']);
Route::get('/login', [TenantAuthController::class, 'showLogin'])->name('tenant.login');
Route::post('/login', [TenantAuthController::class, 'login']);
Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');

// ─── TENANT PUBLIC ROUTES ────────────────────────────
Route::get('/browse', [PropertyController::class, 'browse'])->name('browse');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('property.show');

Route::middleware(['isTenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('tenant.dashboard');
    })->name('tenant.dashboard');
    Route::get('/favourites', [PropertyController::class, 'favourites'])->name('tenant.favourites');
    Route::post('/favourites/{property}', [PropertyController::class, 'toggleFavourite'])->name('tenant.favourite.toggle');
    Route::get('/bookings', [PropertyController::class, 'bookings'])->name('tenant.bookings');
    Route::post('/bookings/{property}', [PropertyController::class, 'bookViewing'])->name('tenant.book');
    Route::get('/profile', function () {
        return view('tenant.profile');
    })->name('tenant.profile');

    // Messages 
    Route::get('/messages', [MessageController::class, 'index'])->name('tenant.messages');
    Route::get('/messages/{property}', [MessageController::class, 'show'])->name('tenant.conversation');
    Route::post('/messages/{property}', [MessageController::class, 'send'])->name('tenant.message.send');
});

// ─── LANDLORD AUTH ───────────────────────────────────
Route::get('/landlord/register', [LandlordAuthController::class, 'showRegister'])->name('landlord.register');
Route::post('/landlord/register', [LandlordAuthController::class, 'register']);
Route::get('/landlord/login', [LandlordAuthController::class, 'showLogin'])->name('landlord.login');
Route::post('/landlord/login', [LandlordAuthController::class, 'login']);
Route::post('/landlord/logout', [LandlordAuthController::class, 'logout'])->name('landlord.logout');

Route::middleware(['isLandlord'])->prefix('landlord')->group(function () {
    Route::get('/dashboard', [LandlordController::class, 'dashboard'])->name('landlord.dashboard');
    Route::get('/properties', [LandlordController::class, 'properties'])->name('landlord.properties');
    Route::get('/properties/create', [LandlordController::class, 'create'])->name('landlord.properties.create');
    Route::post('/properties', [LandlordController::class, 'store'])->name('landlord.properties.store');
    Route::get('/properties/{property}/edit', [LandlordController::class, 'edit'])->name('landlord.properties.edit');
    Route::put('/properties/{property}', [LandlordController::class, 'update'])->name('landlord.properties.update');
    Route::delete('/properties/{property}', [LandlordController::class, 'destroy'])->name('landlord.properties.destroy');
    Route::get('/bookings', [LandlordController::class, 'bookings'])->name('landlord.bookings');
    Route::post('/bookings/{booking}', [LandlordController::class, 'updateBooking'])->name('landlord.bookings.update');

    // Messages 
    Route::get('/messages', [LandlordController::class, 'messages'])->name('landlord.messages');
    Route::get('/messages/{property}', [LandlordController::class, 'conversation'])->name('landlord.conversation');
    Route::post('/messages/{property}/reply', [LandlordController::class, 'reply'])->name('landlord.reply');
});

// ─── ADMIN AUTH ──────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::middleware(['isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    Route::get('/landlords', [AdminController::class, 'landlords'])->name('admin.landlords');
    Route::post('/landlords/{landlord}/toggle', [AdminController::class, 'toggleLandlord'])->name('admin.landlords.toggle');
    Route::get('/verification', [AdminController::class, 'verification'])->name('admin.verification');
    Route::post('/properties/{property}/approve', [AdminController::class, 'approveProperty'])->name('admin.properties.approve');
    Route::post('/properties/{property}/reject', [AdminController::class, 'rejectProperty'])->name('admin.properties.reject');
    Route::get('/properties', [AdminController::class, 'properties'])->name('admin.properties');
    Route::delete('/properties/{property}', [AdminController::class, 'deleteProperty'])->name('admin.properties.delete');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/reports/{report}', [AdminController::class, 'updateReport'])->name('admin.reports.update');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
});
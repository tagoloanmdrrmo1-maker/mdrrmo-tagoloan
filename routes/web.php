<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RainfallController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// === Public routes (no auth) ===

// Arduino sends rain data
Route::get('/rain', [RainfallController::class, 'storeFromArduino'])->name('rain_data.ingest');

// Redirect root to login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Auth routes (login, register, logout, etc.)
require __DIR__.'/auth.php';

// Dashboard must be available to any authenticated user
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [RainfallController::class, 'dashboard'])->name('dashboard');
});

// === Admin-only routes ===
Route::middleware(['auth', 'admin'])->group(function () {

    // Chart data
    Route::get('/chart-data', [RainfallController::class, 'getChartData'])->name('chart.data');
    
    // Table data for dashboard refresh
    Route::get('/table-data', [RainfallController::class, 'getTableData'])->name('table.data');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/hourly-data', [HistoryController::class, 'getDetailedHourlyData'])->name('history.hourly_data');
    Route::get('/history/monthly-data', [HistoryController::class, 'getDetailedMonthlyData'])->name('history.monthly_data');
    Route::get('/history/export-pdf', [HistoryController::class, 'exportRainfallPdf'])->name('history.export_pdf');
    Route::get('/history/trend-analysis', [HistoryController::class, 'getTrendAnalysisData'])->name('history.trend_analysis');

    // Contacts
    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
        Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
        Route::get('/export-pdf', [ContactController::class, 'exportPdf'])->name('contacts.export_pdf');
        
        // Query routes - equivalent to SELECT * FROM contacts WHERE ... = '?' (must come before /{id} route)
        Route::get('/name/{name}', [ContactController::class, 'getContactsByName'])->name('contacts.by_name');
        Route::get('/location/{location}', [ContactController::class, 'getContactsByLocation'])->name('contacts.by_location');
        Route::get('/phone/{phone}', [ContactController::class, 'getContactsByPhone'])->name('contacts.by_phone');
        Route::get('/position/{position}', [ContactController::class, 'getContactsByPosition'])->name('contacts.by_position');
        Route::get('/id/{contactId}', [ContactController::class, 'getContactById'])->name('contacts.by_id');
        Route::get('/location/{location}/position/{position}', [ContactController::class, 'getContactsByLocationAndPosition'])->name('contacts.by_location_and_position');
        
        // CRUD routes (must come after specific query routes)
        Route::put('/{id}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
        Route::get('/{id}', [ContactController::class, 'show'])->name('contacts.show');
    });

    // Devices
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices.index');
        Route::post('/', [DeviceController::class, 'store'])->name('devices.store');
        Route::get('/export-pdf', [DeviceController::class, 'exportPdf'])->name('devices.export_pdf');
        Route::post('/check-location', [DeviceController::class, 'checkLocation'])->name('devices.check_location');
        Route::post('/check-serial', [DeviceController::class, 'checkSerialNumber'])->name('devices.check_serial');
        Route::get('/{id}', [DeviceController::class, 'show'])->name('devices.show');
        Route::put('/{id}', [DeviceController::class, 'update'])->name('devices.update');
        
        // Query routes - equivalent to SELECT * FROM devices WHERE ... = '?'
        Route::get('/location/{location}', [DeviceController::class, 'getDevicesByLocation'])->name('devices.by_location');
        Route::get('/id/{deviceId}', [DeviceController::class, 'getDeviceById'])->name('devices.by_id');
        Route::get('/status/{status}', [DeviceController::class, 'getDevicesByStatus'])->name('devices.by_status');
        Route::get('/location/{location}/status/{status}', [DeviceController::class, 'getDevicesByLocationAndStatus'])->name('devices.by_location_and_status');
    });

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/export-pdf', [MessageController::class, 'exportPdf'])->name('messages.export_pdf');

    // Users (legacy)
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::get('/users/export-pdf', [UsersController::class, 'exportPdf'])->name('users.export_pdf');
    Route::post('/users/check-username', [UsersController::class, 'checkUsername'])->name('users.check_username');
    Route::post('/users/check-email', [UsersController::class, 'checkEmail'])->name('users.check_email');

    // Admin user management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->only(['index','create','store']);
    });

    // Settings
    Route::view('/settings', 'settings')->name('settings');

   // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
        Route::post('/avatar', [ProfileController::class, 'setPresetAvatar'])->name('profile.avatar.set');
        Route::post('/info', [ProfileController::class, 'updateProfileInfo'])->name('profile.info.update');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
        Route::post('/', [\App\Http\Controllers\ReportsController::class, 'store'])->name('reports.store');
        Route::post('/generate', [\App\Http\Controllers\ReportsController::class, 'generateReport'])->name('reports.generate');
        Route::get('/download-pdf', [\App\Http\Controllers\ReportsController::class, 'downloadPdf'])->name('reports.downloadPdf');
        Route::get('/export-pdf', [\App\Http\Controllers\ReportsController::class, 'exportPdf'])->name('reports.export_pdf');
        Route::get('/{id}', [\App\Http\Controllers\ReportsController::class, 'show'])->name('reports.show');
        Route::put('/{id}', [\App\Http\Controllers\ReportsController::class, 'update'])->name('reports.update');
        Route::delete('/{id}', [\App\Http\Controllers\ReportsController::class, 'destroy'])->name('reports.destroy');
    });
    Route::get('/requestors', [\App\Http\Controllers\ReportsController::class, 'getRequestors'])->name('reports.requestors');
    // Route::post('/request-data', [\App\Http\Controllers\RequestDataController::class, 'store'])->name('request_data.store');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::delete('/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    });
});
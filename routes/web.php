<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function() {
    Route::get('/', function() {
        return redirect()->route('login');
    })->name('home');
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('login.post');
    // Route::get('/register', 'Auth\RegisterController@showRegisterForm')->name('register');
    // Route::post('/register', 'Auth\RegisterController@register')->name('register.post');
    Route::get('/forgot-password', 'Auth\ResetPasswordController@showRequestForm')->name('password.request');
    Route::post('/forgot-password', 'Auth\ResetPasswordController@sendResetLink')->name('password.reset.link');
    Route::get('/reset-password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset-password', 'Auth\ResetPasswordController@reset')->name('password.update');
});

// Auth Routes
Route::middleware('auth')->group(function() {
    Route::post('/logout', 'Auth\LogoutController@logout')->name('logout');
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->namespace('Admin')->name('admin.')->group(function() {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        
        // User Management
        Route::get('/users', 'UserController@index')->name('users.index');
        Route::post('/users', 'UserController@store')->name('users.store');
        Route::put('/users/{id}', 'UserController@update')->name('users.update');
        Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy');
        Route::post('/users/import', 'UserController@import')->name('users.import');
        
        // Berkas Management
        Route::get('/berkas', 'BerkasController@index')->name('berkas.index');
        Route::get('/berkas/{id}', 'BerkasController@show')->name('berkas.show');
        Route::post('/berkas/{id}/verify', 'BerkasController@verify')->name('berkas.verify');

        // Ujian Management
        Route::get('/ujian', 'UjianController@index')->name('ujian.index');
        Route::post('/ujian', 'UjianController@store')->name('ujian.store');
        Route::put('/ujian/{id}', 'UjianController@update')->name('ujian.update');
        Route::post('/ujian/{id}/activate', 'UjianController@setActive')->name('ujian.activate');
        Route::delete('/ujian/{id}', 'UjianController@destroy')->name('ujian.destroy');

        // Kelulusan Management
        Route::get('/kelulusan', 'KelulusanController@index')->name('kelulusan.index');
        Route::post('/kelulusan', 'KelulusanController@store')->name('kelulusan.store');

        // Student/Profile Management
        Route::get('/students', 'StudentController@index')->name('students.index');
        Route::get('/students/export', 'StudentController@export')->name('students.export');
        Route::get('/students/{id}', 'StudentController@show')->name('students.show');
        Route::get('/students/{id}/edit', 'StudentController@edit')->name('students.edit');
        Route::put('/students/{id}', 'StudentController@update')->name('students.update');
        Route::delete('/students/{id}', 'StudentController@destroy')->name('students.destroy');
    });
    
    // User Routes
    Route::middleware('role:user')->prefix('user')->namespace('User')->name('user.')->group(function() {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::post('/profile', 'ProfileController@update')->name('profile.update');
        Route::get('/berkas', 'BerkasController@index')->name('berkas');
        Route::post('/berkas/upload', 'BerkasController@upload')->name('berkas.upload');
        Route::get('/kartu-ujian', 'KartuUjianController@index')->name('kartu-ujian');
        Route::post('/kartu-ujian', 'KartuUjianController@generate')->name('kartu-ujian.generate');
        Route::get('/kartu-ujian/download', 'KartuUjianController@download')->name('kartu-ujian.download');
        Route::get('/pengumuman', 'PengumumanController@index')->name('pengumuman');
        // ... user routes lainnya
    });
});
// Artisan Helper Routes (Useful for Shared Hosting)
Route::prefix('artisan')->group(function() {
    // Clear All Cache
    Route::get('/optimize', function() {
        \Artisan::call('optimize:clear');
        \Artisan::call('optimize');
        return "Application Optimized: Cache cleared and config/routes cached!";
    });

    // Create Storage Link
    Route::get('/storage-link', function() {
        \Artisan::call('storage:link');
        return "Storage link created successfully!";
    });

    // Run Migrations
    Route::get('/migrate', function() {
        \Artisan::call('migrate', ['--force' => true]);
        return "Migrations ran successfully!";
    });

    // Clear Just View Cache
    Route::get('/view-clear', function() {
        \Artisan::call('view:clear');
        return "View cache cleared!";
    });
});

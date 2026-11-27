<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('register'));

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/kode', [AuthController::class, 'sendRegistrationCode'])->middleware('throttle:10,1')->name('register.code');
Route::post('/register/verifikasi', [AuthController::class, 'verifyRegistrationCode'])->middleware('throttle:10,1')->name('register.verify');
Route::get('/register/password', [AuthController::class, 'showPasswordSetup'])->name('register.password');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.store');
Route::get('/api/calendar', [CalendarController::class, 'getCalendarData']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/beranda', function () {
        return view('user.beranda');
    })->name('beranda');

    Route::get('/bookingkelas', [BookingController::class, 'create'])->name('bookingkelas');

    Route::get('/riwayatbooking', [BookingController::class, 'index'])->name('riwayatbooking');
    Route::post('/riwayatbooking/{booking}/setuju', [BookingController::class, 'acceptReschedule'])->name('riwayatbooking.setuju');
    Route::post('/riwayatbooking/{booking}/tolak', [BookingController::class, 'rejectReschedule'])->name('riwayatbooking.tolak');
    Route::post('/bookingkelas', [BookingController::class, 'store'])->name('bookingkelas.store');
});

Route::prefix('/admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/kelolabooking', [BookingController::class, 'adminIndex'])->name('admin.kelolabooking');
    Route::post('/kelolabooking/{booking}/konfirmasi', [BookingController::class, 'confirm'])->name('admin.booking.konfirmasi');
    Route::post('/kelolabooking/{booking}/jadwal-ulang', [BookingController::class, 'reschedule'])->name('admin.booking.reschedule');

    Route::get('/buatkelas', function () {
        return view('admin.buatkelas');
    })->name('admin.buatkelas');

    Route::get('/kelolapermohonan', function () {
        return view('admin.kelolapermohonan');
    })->name('admin.kelolapermohonan');

    Route::get('/jadwalharian', [BookingController::class, 'dailySchedule'])->name('admin.jadwalharian');
});

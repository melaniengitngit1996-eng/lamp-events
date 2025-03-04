<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

if (env('CLOSE_REGISTRATION') === true) {
    Route::get('/registration/new', [App\Http\Controllers\RegistrationController::class, 'new'])->name('registration');
}

Route::get('/registration', [App\Http\Controllers\RegistrationController::class, 'create'])->name('registration');
Route::get('/registration/all', [App\Http\Controllers\RegistrationController::class, 'index'])->name('registration.index');
Route::get('/registration/validate', [App\Http\Controllers\RegistrationController::class, 'validation'])->name('registration.validation');

// registration
Route::post('/registration', [App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');
Route::get('/registration/ticket', [App\Http\Controllers\RegistrationController::class, 'show'])->name('registration.show');
Route::get('/registration/{id}/edit', [App\Http\Controllers\RegistrationController::class, 'edit'])->name('registration.edit');
Route::post('/registration/{id}/update', [App\Http\Controllers\RegistrationController::class, 'update'])->name('registration.update');
Route::delete('/registration/{id}/delete', [App\Http\Controllers\RegistrationController::class, 'destroy'])->name('registration.delete');
Route::get('/registration/{id}/resend-mail', [App\Http\Controllers\RegistrationController::class, 'resend_mail'])->name('registration.resend_mail');

// ticket
Route::get('/ticket/{id}', [App\Http\Controllers\TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('ticket.edit');

// export
Route::get('/registrations/export', [App\Http\Controllers\RegistrationController::class, 'export'])->name('registration.export');
Route::get('/registrations/test_mail', [App\Http\Controllers\RegistrationController::class, 'test_mail'])->name('registration.test');

// lookup
Route::get('/lookup', [App\Http\Controllers\LookUpController::class, 'index'])->name('lookup.index');
Route::get('/lookup/create', [App\Http\Controllers\LookUpController::class, 'create'])->name('lookup.create');
Route::post('/lookup', [App\Http\Controllers\LookUpController::class, 'store'])->name('lookup.store');
Route::get('/lookup/validate', [App\Http\Controllers\LookUpController::class, 'validation'])->name('lookup.validation');
Route::get('/lookup/{id}', [App\Http\Controllers\LookUpController::class, 'show'])->name('lookup.show');
Route::get('/lookup/{id}/edit', [App\Http\Controllers\LookUpController::class, 'edit'])->name('lookup.edit');
Route::post('/lookup/{id}/update', [App\Http\Controllers\LookUpController::class, 'update'])->name('lookup.update');
Route::post('/lookup-upload', [App\Http\Controllers\LookUpController::class, 'upload'])->name('lookup.upload.func');
Route::get('/upload', [App\Http\Controllers\LookUpController::class, 'upload_view'])->name('lookup.upload.view');

// payments
Route::get('/payments/{id}/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
Route::delete('/payments/{id}/delete', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('payments.delete');
Route::post('/payments/{id}', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');

// activities
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities');

// attendance
Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/{id}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendances', [App\Http\Controllers\AttendanceController::class, 'all'])->name('attendance.all');
Route::get('/attendances/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

// booking
Route::get('/booking', [App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
Route::get('/booking/validate', [App\Http\Controllers\BookingController::class, 'validation'])->name('booking.validation');
Route::get('/booking/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/{id}/edit', [App\Http\Controllers\BookingController::class, 'edit'])->name('booking.edit');
Route::post('/booking/{id}/update', [App\Http\Controllers\BookingController::class, 'update'])->name('booking.update');

// config
Route::get('/config', [App\Http\Controllers\ConfigurationController::class, 'show'])->name('configurations');

// dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/attendance', [App\Http\Controllers\DashboardController::class, 'view_attendance_per_church'])->name('dashboard.attendance');
Route::get('/dashboard/received-hg', [App\Http\Controllers\DashboardController::class, 'view_received_hg_per_church'])->name('dashboard.hg');

Route::get('received-hg', [App\Http\Controllers\Api\ReceivedHGController::class, 'index'])->name('hg.index');
Route::get('received-hg/export', [App\Http\Controllers\Api\ReceivedHGController::class, 'export'])->name('hg.export');
Route::delete('received-hg/{id}/delete', [App\Http\Controllers\Api\ReceivedHGController::class, 'destroy'])->name('hg.record.delete');


// online check in
Route::get('/check-in', [App\Http\Controllers\CheckInController::class, 'index'])->name('check-in');
Route::get('/check-in/validate', [App\Http\Controllers\CheckInController::class, 'validation'])->name('check-in.validation');
Route::post('/check-in/{id}', [App\Http\Controllers\CheckInController::class, 'update'])->name('check-in.update');
Route::get('/check-in/passes', [App\Http\Controllers\CheckInController::class, 'show'])->name('check-in.attendance');

Route::post('/slots', [App\Http\Controllers\SlotsController::class, 'store'])->name('slots.store');

Route::get('/export/history', [App\Http\Controllers\ExportHistoryController::class, 'index'])->name('history.index');

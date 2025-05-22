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

Route::get('/{event:slug}/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/{event:slug}/home', [App\Http\Controllers\HomeController::class, 'show'])->name('home.index');
Route::get('/{event:slug}/registration', [App\Http\Controllers\Registration2Controller::class, 'create'])->name('register');
Route::get('/{event:slug}/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/{event:slug}/check-in', [App\Http\Controllers\CheckInController::class, 'index'])->name('check-in');


// wip new routes
Route::get('/lookup/{event:slug}/{id}/find', [App\Http\Controllers\LookUpController::class, 'show'])->name('lookup.show');

Route::get('/{event:slug}/registration/ticket', [App\Http\Controllers\Registration2Controller::class, 'show'])->name('registration.show');
Route::post('/registration/{event:slug}', [App\Http\Controllers\Registration2Controller::class, 'store'])->name('registration.store');
Route::get('/{event:slug}/registration/all', [App\Http\Controllers\Registration2Controller::class, 'index'])->name('registration.index');
Route::get('/registration/{id}/edit', [App\Http\Controllers\Registration2Controller::class, 'edit'])->name('registration.edit');
Route::delete('/{event:slug}/registration/{registration}/delete', [App\Http\Controllers\Registration2Controller::class, 'destroy'])->name('registration.delete');
Route::post('/registration/{registration}/update', [App\Http\Controllers\Registration2Controller::class, 'update'])->name('registration.update');
Route::get('/{event:slug}/registration/{id}/resend-mail', [App\Http\Controllers\Registration2Controller::class, 'resend_mail'])->name('registration.resend_mail');
Route::get('/registration/validate', [App\Http\Controllers\Registration2Controller::class, 'validation'])->name('registration.validation');

if (env('CLOSE_REGISTRATION') === true) {
    Route::get('/registration/new', [App\Http\Controllers\Registration2Controller::class, 'new'])->name('registration');
}

// payments
Route::get('/payments/{registration}/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
Route::delete('/payments/{payment}/delete', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('payments.delete');
Route::post('/payments/{registration}', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');

// booking
Route::get('/booking/{registration}/edit', [App\Http\Controllers\BookingController::class, 'edit'])->name('booking.edit');
Route::post('/booking/{id}/update', [App\Http\Controllers\BookingController::class, 'update'])->name('booking.update');
Route::get('/booking/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
// --------

// ticket
Route::get('/{event:slug}/ticket/{id}', [App\Http\Controllers\TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('ticket.edit');

// export
Route::get('/registrations/export', [App\Http\Controllers\RegistrationController::class, 'export'])->name('registration.export');
Route::get('/registrations/test_mail', [App\Http\Controllers\RegistrationController::class, 'test_mail'])->name('registration.test');

// lookup
Route::get('/{event:slug}/lookup', [App\Http\Controllers\LookUpController::class, 'index'])->name('lookup.index');
Route::get('/lookup/create', [App\Http\Controllers\LookUpController::class, 'create'])->name('lookup.create');
Route::post('/lookup', [App\Http\Controllers\LookUpController::class, 'store'])->name('lookup.store');
Route::get('/lookup/validate', [App\Http\Controllers\LookUpController::class, 'validation'])->name('lookup.validation');
Route::get('/lookup/{id}/edit', [App\Http\Controllers\LookUpController::class, 'edit'])->name('lookup.edit');
Route::post('/lookup/{id}/update', [App\Http\Controllers\LookUpController::class, 'update'])->name('lookup.update');
Route::post('/lookup-upload', [App\Http\Controllers\LookUpController::class, 'upload'])->name('lookup.upload.func');
Route::get('/upload', [App\Http\Controllers\LookUpController::class, 'upload_view'])->name('lookup.upload.view');

// activities
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities');

// attendance
Route::get('/attendance/{event}/{id}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/attendance/{event}', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/{event:slug}/attendances', [App\Http\Controllers\AttendanceController::class, 'all'])->name('attendance.all');
Route::get('/attendances/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

// booking
Route::get('/booking', [App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
Route::get('/booking/validate', [App\Http\Controllers\BookingController::class, 'validation'])->name('booking.validation');

// config
Route::get('/config', [App\Http\Controllers\ConfigurationController::class, 'show'])->name('configurations');

// dashboard
Route::get('/dashboard/{event:slug}/attendance', [App\Http\Controllers\DashboardController::class, 'view_attendance_per_church'])->name('dashboard.attendance');
Route::get('/dashboard/{event:slug}/received-hg', [App\Http\Controllers\DashboardController::class, 'view_received_hg_per_church'])->name('dashboard.hg');

Route::get('{event:slug}/received-hg', [App\Http\Controllers\Api\ReceivedHGController::class, 'index'])->name('hg.index');
Route::get('{event:slug}/received-hg/export', [App\Http\Controllers\Api\ReceivedHGController::class, 'export'])->name('hg.export');
Route::delete('received-hg/{id}/delete', [App\Http\Controllers\Api\ReceivedHGController::class, 'destroy'])->name('hg.record.delete');


// online check in
Route::get('/check-in/{event}/validate', [App\Http\Controllers\CheckInController::class, 'validation'])->name('check-in.validation');
Route::post('/check-in/{event}/{id}/edit', [App\Http\Controllers\CheckInController::class, 'update'])->name('check-in.update');
Route::get('/check-in/{event:slug}/passes', [App\Http\Controllers\CheckInController::class, 'show'])->name('check-in.attendance');

Route::post('/{event:slug}/slots', [App\Http\Controllers\SlotsController::class, 'store'])->name('slots.store');

Route::get('/{event:slug}/export/history', [App\Http\Controllers\ExportHistoryController::class, 'index'])->name('history.index');

Route::get('users/mobile/create', [App\Http\Controllers\MobileUserController::class, 'create'])->name('user.create');
Route::post('users/mobile', [App\Http\Controllers\MobileUserController::class, 'store'])->name('user.store');

Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');

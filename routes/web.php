<?php

use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminTournamentController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\FacilitiesController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TournamentMatchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoomStatusController; // Pastikan ini diimpor jika digunakan
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Dashboard\AdminDashboardController; // <--- HAPUS BARIS INI
use App\Http\Controllers\Admin\AdminDashboardController; // <--- PASTIKAN BARIS INI ADA DAN DIGUNAKAN
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\User\AboutUsController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\BracketController;
use App\Http\Controllers\User\CalendarController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\EventController;
use App\Http\Controllers\User\FacilitySubmissionController;
use App\Http\Controllers\User\TeamController;
use App\Http\Controllers\User\TournamentController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController; // Pastikan ini diimpor jika digunakan
use App\Http\Controllers\StatusController; // Pastikan ini diimpor jika digunakan


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/admin/check-in/{id}', [BookingsController::class, 'checkIn'])->name('admin.checkIn');
Route::post('/admin/check-out/{id}', [BookingsController::class, 'checkOut'])->name('admin.checkOut');

Route::post('/send-whatsapp/{booking}', [BookingsController::class, 'sendWhatsApp'])->name('admin.sendWhatsApp');


// Rute untuk menampilkan dashboard status ruangan
Route::get('/room-status', [StatusController::class, 'showRoomStatus'])->name('room.status');
Route::get('/room-status/update', [StatusController::class, 'update'])->name('room-status.update');

// Rute untuk cek-in
Route::post('/booking/{id}/check-in', [\App\Http\Controllers\User\BookingController::class, 'checkIn'])->name('checkIn');

// Rute untuk cek-out
Route::post('/booking/{id}/check-out', [\App\Http\Controllers\User\BookingController::class, 'checkOut'])->name('checkOut');

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk verifikasi email
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

// Rute untuk mengirim ulang link verifikasi
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');


//login Register Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerPost']);
    // Route::get('/verification/code', [RegisterController::class, 'showVerificationCodeForm'])->name('verification.code');
    Route::post('/verify', [RegisterController::class, 'verify'])->name('verify');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login');
});

//google login route
Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

//forget Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');


//Logout Route
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

//admin and Futsal Manager Route
Route::middleware(['auth', 'user_type:admin,manager'])->group(function () {
    Route::get('/admin_dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Notification Routes (Dipindahkan ke dalam grup admin)
    Route::get('/admin/notifications', [AdminDashboardController::class, 'notifications'])->name('admin.notifications.index');
    Route::patch('/admin/notifications/{notification}/mark-as-read', [AdminDashboardController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
    // Route ini akan diganti dengan yang lebih fleksibel di AdminDashboardController
    // Route::get('/admin/notifications/view-submission/{notification}', [AdminDashboardController::class, 'viewSubmission'])->name('admin.notifications.viewSubmission');
});

//Admin User Management Page Route
Route::middleware(['auth', 'user_type:admin', 'log.last.active'])->group(function () {
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin/users/update/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show'); // Sudah ada
    Route::post('admin/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('admin/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
});

//admin profile Route
Route::middleware(['auth', 'user_type:admin,manager', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile'); // Dihapus /admin/ dari prefix
    Route::post('/profile/update-details', [ProfileController::class, 'updateDetails'])->name('admin.profile.update.details');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('admin.profile.update.password');
});

// Facilities Routes
Route::middleware(['auth', 'user_type:admin,manager', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('facilities', [FacilitiesController::class, 'index'])->name('admin.facilities.index');
    Route::get('facilities/create', [FacilitiesController::class, 'create'])->name('admin.facilities.create');
    Route::post('facilities', [FacilitiesController::class, 'store'])->name('admin.facilities.store');
    Route::get('facilities/{facility}', [FacilitiesController::class, 'show'])->name('admin.facilities.show'); // Sudah ada
    Route::get('facilities/{facility}/edit', [FacilitiesController::class, 'edit'])->name('admin.facilities.edit');
    Route::put('facilities/{facility}', [FacilitiesController::class, 'update'])->name('admin.facilities.update');
    Route::delete('facilities/{facility}', [FacilitiesController::class, 'destroy'])->name('admin.facilities.destroy');
    Route::patch('facilities/{facility}/toggle-status', [\App\Http\Controllers\Admin\FacilitiesController::class, 'toggleStatus'])->name('admin.facilities.toggleStatus');
});

//user Route
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// User Profile Page
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/update-details', [UserProfileController::class, 'updateDetails'])->name('profile.update.details');
    Route::post('/profile/additional-details', [UserProfileController::class, 'additionalDetails'])->name('profile.update.additionaldetails');
    Route::post('/profile/update-password', [UserProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('/delete-account', [UserProfileController::class, 'deleteAccount'])->name('delete.account');
    Route::delete('/profile/delete-picture', [UserProfileController::class, 'deletePicture'])->name('profile.delete.picture');
});

//Calendar Route
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('user.calendar');
});

//Calendar for admin Route
Route::middleware(['auth', 'user_type:admin', 'log.last.active'])->group(function () {
    Route::get('/admin_calendar', [AdminCalendarController::class, 'index'])->name('admin.calendar');
});

// Facility Submission (Perhatikan: Anda mengatakan ini tidak digunakan di user, tapi routenya masih ada)
// Saya akan biarkan routenya, tapi tidak akan membuat notifikasi jika formnya memang tidak ada.
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('facility_submissions/create', [FacilitySubmissionController::class, 'create'])->name('user.facility_submissions.create');
    Route::post('facility_submissions/store', [FacilitySubmissionController::class, 'store'])->name('user.facility_submissions.store');
    Route::get('/admin/facility_submissions/{id}', [FacilitySubmissionController::class, 'viewSubmission'])->name('user.facility_submissions.view');
    Route::patch('facility_submissions/{id}/update-status', [FacilitySubmissionController::class, 'updateStatus'])->name('user.facility_submissions.updateStatus');
});

//Booking Route
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('user.booking.index');
    Route::get('/booking/show/{facilityId}', [BookingController::class, 'show'])->name('user.booking.show');
    Route::post('/booking/confirm/{facilityId}', [BookingController::class, 'confirm'])->name('user.booking.confirm');
    Route::get('/generate-receipt', [BookingController::class, 'generateReceipt'])->name('generate.receipt');
    Route::get('/payment-success', [BookingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/user/bookings', [BookingController::class, 'showBookings'])->name('user.bookings');
    Route::get('/user/bookings/search', [BookingController::class, 'search'])->name('user.booking.search');
    Route::post('/bookings/{booking}/store-review', [BookingController::class, 'storeReview'])
        ->name('user.bookings.storeReview');
    Route::delete('/user/bookings/{booking}', [BookingController::class, 'cancel'])->name('user.bookings.cancel');
    // Route::post('/user/bookings/{booking}', [BookingController::class, 'cancel'])->name('user.bookings.cancel');
    Route::post('/user/bookings/stripe/initiate', [BookingController::class, 'Stripe_initiate'])->name('user.bookings.stripe.payment');
    Route::get('/user/bookings/stripe/success', [BookingController::class, 'Stripe_success'])->name('user.bookings.stripe.success');
    Route::get('/user/bookings/stripe/cancel', [BookingController::class, 'Stripe_cancel'])->name('user.bookings.stripe.cancel');
    Route::get('/admin/bookings/create', [BookingsController::class, 'create'])->name('admin.bookings.create');
    Route::post('/admin/bookings', [BookingsController::class, 'store'])->name('admin.bookings.store');
    Route::post('/user/booking/{id}/extend', [BookingController::class, 'extendBooking'])->name('user.requestExtend');
});

//Bookmark
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::post('/facility/bookmark/{facility}', [BookingController::class, 'bookmark'])->name('user.facility.bookmark');
    Route::get('/bookmarks', [BookingController::class, 'bookmarks'])->name('user.bookmarks');
    Route::delete('/user/unbookmark/{facilityId}', [BookingController::class, 'unbookmark'])->name('user.unbookmark');
    Route::post('/user/unbookmark/{facilityId}', [BookingController::class, 'unbookmark']);
});

//Admin Booking History Route (Sudah ada, tidak perlu duplikasi)
Route::middleware(['auth', 'user_type:admin', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('bookings', [BookingsController::class, 'index'])->name('admin.bookings.index');
    Route::get('bookings/{booking}', [BookingsController::class, 'show'])->name('admin.bookings.show'); // Sudah ada
    Route::post('bookings/{id}/approve', [BookingsController::class, 'approve'])->name('admin.bookings.approve');
    Route::post('bookings/{id}/reject', [BookingsController::class, 'reject'])->name('admin.bookings.reject');
});

// Contact us Route
Route::get('/contactUs', [ContactUsController::class, 'showForm'])->name('contact.show');
Route::post('/contactUs', [ContactUsController::class, 'submitForm'])->name('user.contact.submit');

// Admin Contact us Route (Sudah ada, tidak perlu duplikasi)
Route::middleware(['auth', 'user_type:admin', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('/admin_contactUs', [ContactUsController::class, 'index'])->name('admin.contact.index');
    Route::delete('/admin_contactUs/{id}', [ContactUsController::class, 'destroy'])->name('admin.contact.destroy');
    // Tambahkan route show untuk contact form submission
    Route::get('contact/{contactFormSubmission}', [ContactUsController::class, 'show'])->name('admin.contact.show'); // Tambahkan ini
});

// About Us Route
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/aboutUs', [AboutUsController::class, 'showForm'])->name('about.show');
});

//Teams
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('teams', [TeamController::class, 'index'])->name('user.teams.index');
    Route::get('teams/create', [TeamController::class, 'create'])->name('user.teams.create');
    Route::post('teams/store', [TeamController::class, 'store'])->name('user.teams.store');
    Route::match(['get', 'post'], 'teams/{team}/join', [TeamController::class, 'joinTeam'])->name('user.teams.join');
    Route::match(['get', 'post'], 'teams/{team}/leave', [TeamController::class, 'leaveTeam'])->name('user.teams.leave');
    Route::post('teams/{team}/invite', [TeamController::class, 'inviteUser'])->name('user.teams.invite');
});

// User Tournaments
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('tournaments', [TournamentController::class, 'index'])->name('user.tournaments.index');
    Route::get('tournaments/{tournament}', [TournamentController::class, 'show'])->name('user.tournaments.show');
    Route::post('tournaments/{tournament}/join', [TournamentController::class, 'joinTournament'])->name('user.tournaments.join');
});

// Admin Tournaments
Route::middleware(['auth', 'user_type:admin,manager', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('tournaments', [AdminTournamentController::class, 'index'])->name('admin.tournaments.index'); // Dihapus /admin/ dari prefix
    Route::get('tournaments/create', [AdminTournamentController::class, 'create'])->name('admin.tournaments.create');
    Route::post('tournaments/store', [AdminTournamentController::class, 'store'])->name('admin.tournaments.store');
    Route::get('tournaments/{tournament}/edit', [AdminTournamentController::class, 'edit'])->name('admin.tournaments.edit');
    Route::put('tournaments/{tournament}/update', [AdminTournamentController::class, 'update'])->name('admin.tournaments.update');
    Route::delete('tournaments/{tournament}/destroy', [AdminTournamentController::class, 'destroy'])->name('admin.tournaments.destroy');
});

// Tournament Match Routes
Route::middleware(['auth', 'user_type:admin,manager', 'log.last.active'])->prefix('admin')->group(function () {
    Route::get('tournaments/{tournamentId}/matches', [TournamentMatchController::class, 'index'])->name('admin.tournaments.matches');
    Route::get('matches/{matchId}/winner', [TournamentMatchController::class, 'getWinner']);
    Route::get('tournaments/matches/create/{tournamentId}', [TournamentMatchController::class, 'create'])->name('admin.tournamentMatches.create');
    Route::put('tournaments/{tournamentId}/matches/{matchId}', [TournamentMatchController::class, 'update'])->name('admin.tournaments.matches.update');
    Route::delete('tournaments/{tournamentId}/matches/{matchId}', [TournamentMatchController::class, 'destroy'])->name('admin.tournaments.matches.destroy');
    Route::post('tournaments/matches', [TournamentMatchController::class, 'store'])->name('admin.tournamentMatches.store');
});

// User Tournament
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('/generate-bracket', [BracketController::class, 'generateBracket'])->name('generate-bracket');
    Route::get('/tournaments/{tournament}/bracket', [BracketController::class, 'showBracket'])->name('user.tournaments.bracket');
    Route::get('/download-certificate/{winnerId}', [BracketController::class, 'downloadCertificate'])->name('download-certificate');
});

// User Events
Route::middleware(['auth', 'log.last.active'])->group(function () {
    Route::get('user/events', [EventController::class, 'show_event'])->name('user.events.show');
});

// Route untuk fitur Kirim Agenda Harian Admin
Route::middleware(['auth', 'user_type:admin', 'log.last.active'])->prefix('admin')->name('admin.send_message.')->group(function () {
    Route::get('send-message', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'index'])->name('index');
    Route::post('send-message', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'store'])->name('store');
    Route::get('send-message/{id}', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'show'])->name('show');
    Route::post('send-message/{id}/send', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'send'])->name('send');
    // Tambahan agar tombol edit dan hapus tidak error
    Route::get('send-message/{id}/edit', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'edit'])->name('edit');
    Route::put('send-message/{id}', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'update'])->name('update');
    Route::delete('send-message/{id}', [\App\Http\Controllers\Admin\AdminSendMessageController::class, 'destroy'])->name('destroy');
});

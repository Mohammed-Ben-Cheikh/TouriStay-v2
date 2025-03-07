<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\bookingController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PropertyController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // app routes
    Route::get('/', [TouristController::class,'index'])->name('home');
    Route::get('/home', [TouristController::class,'index'])->name('home');
    Route::get('/blogs', function () {return view('blogs');})->name('blogs');
    Route::get('/hébergements', [PropertyController::class, 'index'])->name('hébergements.index');
    Route::post('/hébergements', [PropertyController::class, 'index'])->name('hébergements.index');
    Route::get('/hébergements/{hébergement}', [PropertyController::class, 'show'])->name('hébergements.show');
    Route::post('/favorites/{property}', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/check-in/{property}', [bookingController::class, 'index'])->name('check-in');
    Route::post('/reservations', [BookingController::class, 'store']);
    Route::get('/reservations', [BookingController::class, 'getReservations']);
    Route::get('/reservation/{reservation}', [BookingController::class, 'reservationIndex'])->name('payment.process');
    Route::get('/booking', [BookingController::class, 'getBooking']);
    Route::get('/reservation/confirmation/{reservation}', [BookingController::class, 'showConfirmation'])->name('booking.confirmation');
    Route::get('/booking/{id}/confirmation', [BookingController::class, 'confirmationData']);
    Route::get('/MesRéservation', [BookingController::class, 'réservationIndex'])->name('MesRéservation');
    Route::get('/booking/download-pdf/{id}', [BookingController::class, 'downloadPdf'])->name('booking.download-pdf');
    
    // tourist routes
    Route::middleware(['isTourist'])->group(function () {
        Route::get('/become-an-owner', [OwnerController::class, 'welcome'])->name('become-an-owner');
        Route::post('/register-owner', [OwnerController::class, 'become_owner'])->name('register.owner');
    });
    

    // owner routes
    Route::middleware(['isOwner'])->group(function () {
        Route::get('/hébergement/create', [PropertyController::class, 'create'])->name('hébergements.create');
        Route::post('/hébergement/store', [PropertyController::class, 'store'])->name('hébergements.store');
        Route::get('/hébergement/{hébergement}/edit', [PropertyController::class, 'edit'])->name('hébergements.edit');
        Route::put('/hébergement/{hébergement}', [PropertyController::class, 'update'])->name('hébergements.update');
        Route::delete('/hébergement/{hébergement}', [PropertyController::class, 'destroy'])->name('hébergements.destroy');
        Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
        Route::get('/owner/reservations', [OwnerController::class, 'reservations'])->name('reservations.data');
        Route::get('/owner/reservations/{hébergement}', [OwnerController::class, 'index'])->name('reservations.index');
        Route::get('/booking/{id}', [OwnerController::class, 'getSingleBooking']);
        Route::get('/owner/reservation/{booking}', [OwnerController::class, 'bookingIndex'])->name('booking.index');
    });


    // admin routes
    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/hébergement/create', [AdminController::class, 'create'])->name('admin.hébergements.create');
        Route::post('/admin/hébergement/store', [AdminController::class, 'store'])->name('admin.hébergements.store');
        Route::get('/admin/hébergement/{hébergement}/edit', [AdminController::class, 'edit'])->name('admin.hébergements.edit');
        Route::put('/admin/hébergement/{hébergement}', [AdminController::class, 'update'])->name('admin.hébergements.update');
        Route::delete('/admin/hébergement/{hébergement}', [AdminController::class, 'destroy'])->name('admin.hébergements.destroy');
        Route::get('/admin/reservations', [AdminController::class, 'reservations'])->name('admin.reservations.data');
        Route::get('/admin/reservations/{hébergement}', [AdminController::class, 'index'])->name('admin.reservations.index');
        Route::get('/admin/booking/{id}', [AdminController::class, 'getSingleBooking']);
        Route::get('/admin/reservation/{booking}', [AdminController::class, 'bookingIndex'])->name('admin.booking.index');
    });
});

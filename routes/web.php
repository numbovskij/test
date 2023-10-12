<?php

use App\Domain\Ticket\TicketController;
use App\Domain\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::name('user.')->group(function () {
    Route::get('/ticket', [TicketController::class, 'index'])->middleware('auth')->name('ticket');

    Route::post('/ticket-create', [TicketController::class, 'create'])->name('ticket-create');

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect(route('user.create.ticket'));
        }

        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'login']);

    Route::get('/logout', function () {
        Auth::logout();

        redirect('/');
    });

    Route::get('/registration', function () {
        if (Auth::check()) {
            return redirect(route('user.ticket'));
        }

        return view('registration');
    })->name('registration');

    Route::post('/registration', [UserController::class, 'save']);
});

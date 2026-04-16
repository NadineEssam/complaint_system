<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Complaints\ComplaintController;



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
Route::get('/complaints/data', [ComplaintController::class, 'data'])->name('complaints.data');


Route::get('/complaints/details', [ComplaintController::class, 'details'])
    ->name('complaints.details');


Route::post('/complaints/store', [ComplaintController::class, 'store'])
    ->name('complaints.store');

Route::get('complaints/{id}/responses', [ComplaintController::class, 'responses'])
    ->name('complaints.responses');

Route::post('complaints/responses/store', [ComplaintController::class, 'storeResponse'])
    ->name('complaints.responses.store');

Route::get('complaints/{id}/responses/data', [ComplaintController::class, 'responsesData'])
    ->name('complaints.responses.data');
Route::get('/responses/{id}', [ComplaintController::class, 'showResponse']);
Route::get('/', function () {
    return view('home');
})->name('home');

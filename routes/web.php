<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Complaints\ComplaintController;
use App\Http\Controllers\Complaints\ComplaintResponseController;


// Show login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
Route::get('/complaints/data', [ComplaintController::class, 'data'])->name('complaints.data');
Route::get('/complaints/details', [ComplaintController::class, 'details'])->name('complaints.details');
Route::post('/complaints/store', [ComplaintController::class, 'store']) ->name('complaints.store');
Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
Route::get('/complaints/{id}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
Route::put('/complaints/{id}', [ComplaintController::class, 'update'])->name('complaints.update');
Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

Route::get('complaints/{id}/responses', [ComplaintResponseController::class, 'index'])->name('complaints.responses');
Route::get('complaints/{id}/responses/create', [ComplaintResponseController::class, 'create'])->name('complaints.responses.create');
Route::get('/responses/{id}/edit', [ComplaintResponseController::class, 'edit'])->name('responses.edit');
Route::post('complaints/responses/store', [ComplaintResponseController::class, 'store'])->name('complaints.responses.store');
Route::get('complaints/{id}/responses/data', [ComplaintResponseController::class, 'data'])->name('complaints.responses.data');
Route::get('/responses/{id}', [ComplaintResponseController::class, 'show']);
Route::delete('/responses/{id}', [ComplaintResponseController::class, 'destroy'])->name('responses.delete');
Route::post('/responses/{id}/update', [ComplaintResponseController::class, 'update'])
    ->name('responses.update');



Route::get('/', function () {
    return view('home');
})->name('home');

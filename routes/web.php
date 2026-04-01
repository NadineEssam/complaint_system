<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/test', function () {
    return view('home');
})->name('dashboard');




   
            /*start roles routes*/
            Route::group(['prefix' => 'roles'], function () {
                Route::get('/roles.rolePermissions', [\App\Http\Controllers\Dashboard\RolesController::class, 'rolePermissions'])->name('roles.rolePermissions');
                Route::resource('/', \App\Http\Controllers\Dashboard\RolesController::class)->names([
                    'index' => 'roles.index',
                    'create' => 'roles.create',
                    'store' => 'roles.store',
                    'update' => 'roles.update',
                    'edit' => 'roles.edit',
                    'destroy' => 'roles.destroy',
                    'show' => 'roles.show'
                ])->parameter('', 'role');
            });
            /*end roles routes*/
            /*start users routes*/
            Route::group(['prefix' => 'users'], function () {
              //  Route::post('/roles/rolesPermissions', [\App\Http\Controllers\Dashboard\UsersController::class, 'rolesPermissions'])->name('roles.rolesPermissions');
                Route::get('/profile', [\App\Http\Controllers\Dashboard\UsersController::class, 'profile'])->name('users.profile');
                Route::put('/profile', [\App\Http\Controllers\Dashboard\UsersController::class, 'profileUpdate'])->name('users.profile_update');
                Route::delete('/delete-multi', [\App\Http\Controllers\Dashboard\UsersController::class, 'deleteMulti'])->name('users.multi_destroy');
                Route::post('/export', [\App\Http\Controllers\Dashboard\UsersController::class, 'export'])->name('users.export');
                Route::resource('/', \App\Http\Controllers\Dashboard\UsersController::class)->names([
                    'index' => 'users.index',
                    'create' => 'users.create',
                    'store' => 'users.store',
                    'update' => 'users.update',
                    'edit' => 'users.edit',
                    'destroy' => 'users.destroy',
                    'show' => 'users.show',
                ])->parameter('', 'user');
            });
            /*end users routes*/
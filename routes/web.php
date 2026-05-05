<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\ReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/optimize-clear', function () {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');
    Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/dashboard', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', function () {
    //     return view('home');
    // })->name('dashboard');

    /*start reports routes*/
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/',                    [ReportController::class, 'index'])->name('reports.index');
        Route::get('/{key}/filters',       [ReportController::class, 'filters'])->name('reports.filters');
        Route::post('/{key}/generate',     [ReportController::class, 'generate'])->name('reports.generate');
    });
    /*end reports routes*/


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

     /*start complaints routes*/
    Route::group(['prefix' => 'complaints'], function () {
        Route::resource('/', \App\Http\Controllers\Dashboard\ComplaintController::class)->names([
            'index' => 'complaints.index',
            'create' => 'complaints.create',
            'store' => 'complaints.store',
            'update' => 'complaints.update',
            'edit' => 'complaints.edit',
            'destroy' => 'complaints.destroy',
            'show' => 'complaints.show'
        ])->parameter('', 'complaint');
    });
    /*end complaints routes*/

     Route::group(['prefix' => 'responses'], function () {
        Route::resource('/', \App\Http\Controllers\Dashboard\ComplaintResponseController::class)->names([
            'index' => 'responses.index',
            'create' => 'responses.create',
            'store' => 'responses.store',
            'update' => 'responses.update',
            'edit' => 'responses.edit',
            'destroy' => 'responses.destroy',
            'data' => 'responses.data',
            'show' => 'responses.show'
        ])->parameter('', 'response');
    });


    

});







Route::get('/test', function () {
    // 
})->name('test');

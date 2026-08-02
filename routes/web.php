<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\ReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



// Route::any('{any?}', function (\Illuminate\Http\Request $request) {
//     dd([
//         'path' => $request->path(),
//         'url' => $request->url(),
//         'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
//     ]);
// })->where('any', '.*');


Route::get('/optimize-clear', function () {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware(['auth', 'route.permission'])->group(function () {

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
        Route::get('/{complaint}/duplicate/create', [\App\Http\Controllers\Dashboard\ComplaintController::class, 'duplicateCreate'])
            ->name('complaints.duplicate.create');
        Route::post('/{complaint}/duplicate', [\App\Http\Controllers\Dashboard\ComplaintController::class, 'duplicateStore'])
            ->name('complaints.duplicate.store');
        Route::get('/{complaint}/duplicates', [\App\Http\Controllers\Dashboard\ComplaintController::class, 'duplicatesIndex'])
            ->name('complaints.duplicates.index');
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


    // ✅ SERVICES ROUTES
Route::group(['prefix' => 'services'], function () {
    Route::resource('/', \App\Http\Controllers\Dashboard\ServiceTypeController::class)->names([
        'index' => 'services.index',
        'create' => 'services.create',
        'store' => 'services.store',
        'update' => 'services.update',
        'edit' => 'services.edit',
        'destroy' => 'services.destroy',
        'show' => 'services.show'
    ])->parameter('', 'service');
});

// ✅ ComSources Routes
Route::group(['prefix' => 'sources'], function () {
    Route::resource('/', \App\Http\Controllers\Dashboard\ComSourceController::class)->names([
        'index' => 'sources.index',
        'create' => 'sources.create',
        'store' => 'sources.store',
        'update' => 'sources.update',
        'edit' => 'sources.edit',
        'destroy' => 'sources.destroy',
        'show' => 'sources.show'
    ])->parameter('', 'source');
});
 
// ✅ Close Reason Classifications Routes
Route::group(['prefix' => 'close-reason-classify'], function () {
    Route::resource('/', \App\Http\Controllers\Dashboard\CompCloseReasonClassifyController::class)->names([
        'index' => 'close-reason-classify.index',
        'create' => 'close-reason-classify.create',
        'store' => 'close-reason-classify.store',
        'update' => 'close-reason-classify.update',
        'edit' => 'close-reason-classify.edit',
        'destroy' => 'close-reason-classify.destroy',
        'show' => 'close-reason-classify.show'
    ])->parameter('', 'classification');
});

    

});







Route::get('/test', function () {
    // 
})->name('test');
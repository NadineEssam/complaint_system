<?php

use App\Http\Controllers\Dashboard\ReportController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/test', function () {

    $ldapHosts = [

        '192.168.161.100',

        '192.168.161.131',

        '192.168.161.201',

    ];

    $ldapPort = 389;



    $username= 'amjad.anwarxx';
    $password = 'Mego@01277112438';

    $ldapConn = null;

    $authenticated = false;




    // Try each LDAP host until successful bind




    //$ds = ldap_connect("ldap://{$ldaphost}:{$ldapport} ldap://{$ldaphost2}:{$ldapport} ldap://{$ldaphost3}:{$ldapport}") or die("Could not connect to {$ldaphost}");




    foreach ($ldapHosts as $host) {



        $ldapConn = ldap_connect("ldap://{$host}:{$ldapPort}");

        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);

        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        $ldapRdn = "sfd" . chr(92) . $username;
        //chr(92)=\

        if (@ldap_bind(
            $ldapConn,
            $ldapRdn,
            $password
        )) {

            $authenticated = true;

            break;
        }
    }

    if ($authenticated) {
        echo "Authentication successful!";
    } else {
        echo "Authentication failed.";
    }

    // return view('home');
})->name('dashboard');



Route::get('/reports',                    [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/{key}/filters',       [ReportController::class, 'filters'])->name('reports.filters');
Route::post('/reports/{key}/generate',     [ReportController::class, 'generate'])->name('reports.generate');

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
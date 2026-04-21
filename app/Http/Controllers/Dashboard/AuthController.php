<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('dashboard.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'userID' => 'required',
            'password' => 'required',
        ]);

        $ldapHosts = [
            '192.168.161.100',
            '192.168.161.131',
            '192.168.161.201',
        ];

        $ldapPort = 389;

        $username = $request->userID;
        $password = $request->password;

        $user = User::where('userID', $username)->first();
        if (!$user) {
            return back()->withErrors([
                'userID' => 'المستخدم غير مسجل في النظام.',
            ]);
        }

        $authenticated = false;

        foreach ($ldapHosts as $host) {

            $ldapConn = ldap_connect("ldap://{$host}:{$ldapPort}");

            if (!$ldapConn) {
                continue;
            }

            ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

            $ldapRdn = "sfd\\" . $username;

            if (@ldap_bind($ldapConn, $ldapRdn, $password)) {
                $authenticated = true;
                break;
            }
        }

        if (!$authenticated) {
            return back()->withErrors([
                'userID' => 'خطأ في اسم المستخدم أو كلمة المرور. يرجى المحاولة مرة أخرى.',
            ]);
        }

        // 👇 Important: sync with Laravel users table
        // $user = User::firstOrCreate(
        //     ['userID' => $username],
        //     [
        //         'userID' => $username,
        //         'userEmail' => $username . '@sfdegypt.org' 
        //     ]
        // );

      


        // 👇 Login using Laravel session
        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}

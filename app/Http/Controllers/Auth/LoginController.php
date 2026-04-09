<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        
        $ldapHosts = explode(',', env('LDAP_HOSTS', '127.0.0.1'));
        $ldapPort = env('LDAP_PORT', 389);
        $ldapDomain = env('LDAP_DOMAIN', 'sfd');

        $authenticated = false;

        foreach ($ldapHosts as $host) {
            $ldapConn = @ldap_connect("ldap://{$host}:{$ldapPort}");
            if (!$ldapConn) continue;

            ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

            $ldapRdn = $ldapDomain . '\\' . $username;

            if (@ldap_bind($ldapConn, $ldapRdn, $password)) {
                $authenticated = true;
                break;
            }
        }

        if ($authenticated) {
            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $username,
                    'email' => $username ,
                ]
            );

            Auth::login($user);

            return redirect()->intended('/home'); 
        }

        return back()->withErrors([
            'username' => 'Invalid credentials',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
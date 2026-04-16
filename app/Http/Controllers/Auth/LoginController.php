<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'userID'   => 'required|string',
            'password' => 'required|string',
        ]);

        $userID   = trim($request->userID);
        $password = $request->password;

    
        $dbUser = DB::table('users_groups')
            ->where('userID', $userID)
            ->first();

        if (!$dbUser) {
            return back()->withErrors([
                'userID' => 'User does not exist.'
            ])->withInput();
        }

       
        $ldapHosts = [
            '192.168.161.100',
            '192.168.161.131',
            '192.168.161.201',
        ];

        $ldapPort = 389;
        $domain   = 'sfd';

        $authenticated = false;

        foreach ($ldapHosts as $host) {

            $ldapConn = @ldap_connect("ldap://{$host}:{$ldapPort}");

            if (!$ldapConn) {
                continue;
            }

            ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

            $ldapUser = $domain . chr(92) . $userID; // sfd\userID

            if (@ldap_bind($ldapConn, $ldapUser, $password)) {
                $authenticated = true;
                ldap_unbind($ldapConn);
                break;
            }

            ldap_unbind($ldapConn);
        }

        if (!$authenticated) {
            return back()->withErrors([
                'password' => 'Password is incorrect.'
            ])->withInput();
        }

        
        $user = User::firstOrCreate(
            ['email' => $userID . '@local.com'],
            [
                'name' => $userID,
                'password' => bcrypt('temporary-password')
            ]
        );

        Auth::login($user);

        return redirect()->route('complaints.index');
    }

  
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
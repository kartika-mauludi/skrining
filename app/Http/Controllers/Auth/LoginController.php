<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Guru;
use App\Models\User;
use App\Models\LoginLog;

class LoginController extends Controller
{
    public function login(Request $request){
  
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required',
        ],[
            'login.required' => 'username /email/ NIP tidak boleh kosong',
            'password.required' => 'password tidak boleh kosong' 
        ]);

        $login = $request->login;
        $password = $request->password;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        }
        
        else {
        $user = User::where('name', $login)->first();

        // 3️⃣ Kalau bukan username → cek NIP guru
        if (!$user) {
            $guru = Guru::where('nip', $login)->first();
            $user = $guru?->user; // relasi guru -> user
        }
    }

         if (!$user) {
            return back()->withErrors([
                'login' => 'Akun tidak ditemukan'
            ]);
        }
      
        if (Auth::attempt([
            'email' => $user->email,
            'password' => $password
        ])) {

            LoginLog::create([
                'user_id'    => Auth::id(),
                'email'      => $request->login,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status'     => 'success',
            ]);

             $request->session()->regenerate();
              return match ($user->role) {
                'super_admin' => redirect('/admin/index'),
                'guru'        => redirect('/guru/dashboard'),
                default       => redirect('/login'),
              };
        }   
        
        LoginLog::create([
            'email'      => $request->login,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status'     => 'failed',
        ]);
        
        return back()->withErrors([
            'password' => 'Password tidak sesuai'
        ]);
    }

    public function logout(Request $request){
         Auth::logout(); // Log out the current user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/');
    }
}

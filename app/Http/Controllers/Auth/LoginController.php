<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class LoginController extends Controller
{
    public function login(Request $request){
  
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ],[
            'email.required' => 'email tidak boleh kosong',
            "email.exists" => "Akun tidak ditemukan",
            'password.required' => 'password tidak boleh kosong' 
        ]);

        $credentials = $request->only('email', 'password');
      
        if (Auth::attempt($credentials) ) {
            if (Auth::user()->role === 'super_admin') {
                return redirect('/admin/index');
            }else if (Auth::user()->role === 'guru') {
                return redirect('/guru/dashboard');
            }
            else{
                return redirect('/login');
            }
        }    
        
        else{
            return redirect()->route('login')
            ->withErrors([
                'password' => 'password tidak sesuai',
            ]);
        }
    }

    public function logout(Request $request){
         Auth::logout(); // Log out the current user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/');
    }
}

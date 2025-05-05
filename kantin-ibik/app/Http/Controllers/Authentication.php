<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Authentication extends Controller
{
    public function index(){
        //echo Hash::make("123456");
        return view('authentications.signin');
    }

    public function showRegisterForm()
    {
        return view('authentications.register'); // atau sesuai view Anda
    }

    public function authenticate(Request $request):RedirectResponse{
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        

       
        // dd($credentials); 
        // $credentials['password'] = Hash::make($credentials['password']);
        // //jika berhasil
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); //untuk menghindari session fixation (masuk kedalam celah melalui session sama)
            return redirect()->intended('dashboard');
        }

        //jika gagal
        return back()->with('loginerror','The provided credentials do not match our records.');
    }
    public function registerStudent(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);

      

       
    }

    

    function signout(Request $request):RedirectResponse{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }



    
    public function registerSave(Request $request)
{
    Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed'
    ])->validate();

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    // Tambahkan flash message
    return redirect()->route('login')->with('success', 'Registrasi berhasil');
}

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();
  
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }
  
        $request->session()->regenerate();
  
        return redirect()->route('dashboard');
    }
  
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
    return redirect('/login')->with('success', 'Berhasil logout');
    }
 

    
    public function profile()
    {
        return view('profile');
    }

public function updateProfile(Request $request)
{
    $user = auth()->user();
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->save();

    return redirect()->route('profile')->with('success', 'Profile updated!');

}
}
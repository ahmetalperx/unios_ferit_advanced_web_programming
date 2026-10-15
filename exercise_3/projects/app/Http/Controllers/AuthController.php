<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    // ---------------------------------------------------------------------------------------------- //

    public function showLogin() {

        if (Auth::check() === true) {

            return redirect() -> route('projects.index');

        }

        return view('auth.login');

    }

    // ---------------------------------------------------------------------------------------------- //

    public function login(Request $request) {

        $credentials = $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials) === true) {

            $request -> session() -> regenerate();

            return redirect() -> intended(route('projects.index'));

        }

        return back() -> withErrors([
            'email' => __('Invalid credentials provided.')
        ]) -> onlyInput('email');

    }

    // ---------------------------------------------------------------------------------------------- //

    public function showRegister() {

        if (Auth::check() === true) {

            return redirect() -> route('projects.index');

        }

        return view('auth.register');

    }

    // ---------------------------------------------------------------------------------------------- //

    public function register(Request $request) {

        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        Auth::login($user);

        return redirect() -> route('projects.index');

    }

    // ---------------------------------------------------------------------------------------------- //

    public function logout(Request $request) {

        $locale = $request -> session() -> get('locale', 'en');

        Auth::logout();

        $request -> session() -> invalidate();

        $request -> session() -> regenerateToken();

        $request -> session() -> put('locale', $locale);

        $request -> session() -> save();

        return redirect() -> route('login');

    }

    // ---------------------------------------------------------------------------------------------- //

}

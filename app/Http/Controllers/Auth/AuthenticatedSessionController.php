<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationAPI;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(AuthenticationAPI $api)
    {
        Request::validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (filter_var(Request::input('login'), FILTER_VALIDATE_EMAIL)) {
            $credentials = Request::only('login', 'password');
            if (Auth::attempt($credentials)) {
                Request::session()->regenerate();

                return Redirect::intended(Auth::user()->home_page);
            }

            return back()->withErrors([
                'login' => __('auth.failed'),
            ]);
        }

        $data = $api->authenticate(Request::input('login'), Request::input('password'));

        if (! $data['found']) {
            return back()->withErrors([
                'login' => $data['message'],
            ]);
        }

        $user = User::where('login', Request::input('login')) // incase of database config to case sensitive 🤫
                    ->orWhere('login', ucfirst(Request::input('login')))
                    ->orWhere('login', strtolower(Request::input('login')))
                    ->orWhere('login', ucfirst(strtolower(Request::input('login'))))
                    ->first();
        if (! $user) {
            Session::put('profile', $data);

            return Redirect::route('register');
        }

        Auth::login($user);

        return Redirect::intended($user->home_page);
    }

    public function destroy()
    {
        Auth::guard('web')->logout();

        Request::session()->invalidate();

        Request::session()->regenerateToken();

        return Redirect::to('/');
    }
}

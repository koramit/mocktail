<?php

namespace App\Http\Controllers\Auth;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Auth/Register', ['profile' => Session::get('profile', [])]);
    }

    public function store()
    {
        $baseRules = [
            'login' => 'required|string|unique:users',
            'name' => 'required|string|unique:users',
            'full_name' => 'required|string',
            'tel_no' => 'required|digits_between:9,10',
            'agreement_accepted' => 'required',
        ];

        if (Session::has('profile')) {
            // AD
            Request::validate($baseRules);
            $data = Request::only(['login', 'name', 'full_name', 'pln', 'tel_no']);
            $data['center_id'] = 1;
            $data['password'] = Hash::make(Str::random(64));
            $profile = [];
            $ADProfile = Session::get('profile');
            $profile['org_id'] = $ADProfile['org_id'];
            $profile['remark'] = $ADProfile['remark'];
            $profile['divsion'] = $ADProfile['org_division_name'] ?? null;
            $user = new User();
        } else {
            // email
            Request::validate($baseRules + [
                'center' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            $data = Request::only(['login', 'center', 'name', 'full_name', 'pln', 'tel_no', 'email', 'password']);
            $data['center_id'] = Center::findByName($data['center'])->id; // need error handle
            $data['password'] = Hash::make($data['password']);
            $profile = [];
            $user = new User();
        }
        $profile['full_name'] = $data['full_name'];
        $profile['pln'] = $data['pln'];
        $profile['tel_no'] = $data['tel_no'];
        $profile['home_page'] = 'home';
        $user->login = $data['login'];
        $user->name = $data['name'];
        $user->center_id = $data['center_id'];
        $user->password = $data['password'];
        $user->profile = $profile;
        $user->save();

        Registered::dispatch($user);

        Auth::login($user);
        Session::forget('profile');

        return Redirect::route('home');
    }
}

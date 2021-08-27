<?php

namespace App\Http\Controllers;

use App\Contracts\AuthenticationAPI;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class LinkUserController extends Controller
{
    public function __invoke(AuthenticationAPI $api)
    {
        $data = $api->authenticate(Request::input('login'), Request::input('password'));

        if (! $data['found']) {
            return [
                'found' => false,
                'login' => $data['message'],
            ];
        }

        $user = User::where('login', Request::input('login')) // incase of database config to case sensitive 🤫
                    ->orWhere('login', ucfirst(Request::input('login')))
                    ->orWhere('login', strtolower(Request::input('login')))
                    ->orWhere('login', ucfirst(strtolower(Request::input('login'))))
                    ->first();
        if (! $user) {
            return [
                'found' => false,
                'login' => 'login not found',
            ];
        }

        if ($user && $user->profile['org_id'] != Request::input('org_id')) {
            return [
                'found' => false,
                'org_id' => 'org_id not matched',
            ];
        }

        $user->tokens()->where('name', 'link-user')->delete();

        $token = $user->createToken('link-user');

        return [
            'found' => true,
            'token' => $token->plainTextToken,
        ];
    }
}

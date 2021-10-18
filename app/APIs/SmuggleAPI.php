<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
use Illuminate\Support\Facades\Http;

class SmuggleAPI implements PatientAPI, AuthenticationAPI
{
    public function authenticate($login, $password)
    {
        return $this->makePost([
            'function' => 'authenticate',
            'org_id' => $login,
            'password' => $password,
        ]);
    }

    public function getPatient($hn)
    {
    }

    public function getAdmission($an)
    {
    }

    public function recentlyAdmission($hn)
    {
    }

    protected function makePost($data)
    {
    }

    // protected function makePost($data)
    // {
    //     $headers = [
    //         'token'  => env('SMUGGLE_TOKEN'), // Your apps token
    //         'secret' => env('SMUGGLE_SECRET'),
    //     ];
    //     $response = Http::acceptJson()
    //                     ->withHeaders($headers)
    //                     ->timeout(8)
    //                     ->retry(2, 100)
    //                     ->post(env('SMUGGLE_URL'), $data);

    //     return $response->body();
    // }
}

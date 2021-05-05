<?php

namespace App\Http\Controllers\APIs\Front;

use App\Contracts\PatientAPI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class PatientAPIController extends Controller
{
    public function recentlyAdmission(PatientAPI $api)
    {
        Request::validate(['hn' => 'required|digits:8']);

        return $api->recentlyAdmission(Request::input('hn'));
    }

    public function patient(PatientAPI $api)
    {
        Request::validate(['hn' => 'required|digits:8']);

        return $api->getPatient(Request::input('hn'));
    }
}

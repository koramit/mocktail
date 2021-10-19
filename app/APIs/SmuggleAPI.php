<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class SmuggleAPI implements PatientAPI, AuthenticationAPI
{
    public function authenticate($login, $password)
    {
        $password = str_replace('+', 'BuAgSiGn', $password);
        $password = str_replace('%', 'PeRcEnTsIgN', $password);
        $password = str_replace('&', 'LaEsIgN', $password);
        $password = str_replace('=', 'TaOkUbSiGn', $password);

        $data = $this->makePost([
            'function' => 'authenticate',
            'org_id' => $login,
            'password' => $password,
        ]);

        if (($data['reply_code'] ?? 1) != 0) {
            return [
                'found' => false,
                'message' => __('auth.failed'),
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'username' => $login,
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'email' => $data['email'],
            'org_id' => $data['org_id'],
            'tel_no' => $data['tel_no'] ?? null,
            'document_id' => null,
            'org_division_name' => $data['org_division_name'],
            'org_position_title' => $data['org_position_title'],
            'remark' => $data['remark'],
            'password_expires_in_days' => $data['password_days_left'],
        ];
    }

    public function getPatient($hn)
    {
        $data = $this->makePost(['function' => 'patient', 'hn' => $hn]);

        if (($data['reply_code'] ?? 1) != 0) {
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'alive' => true,
            'hn' => $hn,
            'patient_name' => trim(($data['title'] ?? '').' '.($data['patient_name'] ?? '')),
            'title' => $data['title'],
            'first_name' => $data['first_name'],
            'middle_name' => null,
            'last_name' => $data['last_name'],
            'document_id' => $data['document_id'],
            'dob' => $data['dob'],
            'gender' => $data['gender'] ? 'male' : 'female',
            'race' => $data['race'],
            'nation' => $data['nation'],
            'tel_no' => $data['tel_no'],
            'spouse' => $data['spouse'],
            'address' => $data['address'],
            'postcode' => explode(' ', $data['location'])[0] ?? null,
            'province' => $data['province'],
            'insurance_name' => $data['insurance_name'],
            'marital_status' => $data['marital_status_name'],
            'alternative_contact' => $data['alternative_contact'],
        ];
    }

    public function getAdmission($an)
    {
        $data = $this->makePost(['function' => 'admission', 'an' => $an]);

        if (($data['reply_code'] ?? 1) != 0) {
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'alive' => true,
            'hn' => $data['hn'],
            'an' => $an,
            'dob' => $data['dob'],
            'gender' => $data['gender'] ? 'male' : 'female',
            'patient_name' => $data['patient_name'],
            'patient' => [
              'ok' => true,
              'found' => true,
              'alive' => true,
              'hn' => $data['hn'],
              'patient_name' => $data['patient_name'],
              'title' => $data['patient']['title'],
              'first_name' => $data['patient']['first_name'],
              'middle_name' => null,
              'last_name' => $data['patient']['last_name'],
              'document_id' => $data['patient']['document_id'],
              'dob' => $data['patient']['dob'],
              'gender' => $data['gender'] ? 'male' : 'female',
              'race' => $data['patient']['race'],
              'nation' => $data['patient']['nation'],
              'tel_no' => str_replace('-', '', $data['patient']['tel_no']),
              'spouse' => $data['patient']['spouse'],
              'address' => $data['patient']['address'],
              'postcode' => explode(' ', $data['patient']['location'])[0] ?? null,
              'province' => $data['patient']['province'],
              'insurance_name' => $data['patient']['insurance_name'],
              'marital_status' => $data['patient']['marital_status_name'],
              'alternative_contact' => $data['patient']['alternative_contact'],
              'marital_status_name' => $data['patient']['marital_status_name'],
              'location' => explode(' ', $data['patient']['location'])[0] ?? null,
            ],
            'ward_name' => $data['ward_name'],
            'ward_name_short' => $data['ward_name_short'],
            'admitted_at' => $data['datetime_admit'],
            'discharged_at' => $data['datetime_dc'],
            'attending' => $data['attending_name'],
            'attending_license_no' => $data['attending_pln'],
            'discharge_type' => $data['discharge_type_name'],
            'discharge_status' => $data['discharge_status_name'],
            'department' => $data['patient_dept_name'],
            'division' => $data['patient_sub_dept'],
            'attending_name' => $data['attending_name'],
            'discharge_type_name' => $data['discharge_type_name'],
            'discharge_status_name' => $data['discharge_status_name'],
            'encountered_at' => $data['datetime_admit'] ? Carbon::parse($data['datetime_admit'], 'asia/bangkok')->tz('UTC') : null,
            'dismissed_at' => $data['datetime_dc'] ? Carbon::parse($data['datetime_dc'], 'asia/bangkok')->tz('UTC') : null,
        ];
    }

    public function recentlyAdmission($hn)
    {
        $data = $this->makePost(['function' => 'recently-admit', 'hn' => $hn]);

        if (($data['reply_code'] ?? 1) != 0) {
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'alive' => true,
            'hn' => $hn,
            'an' => $data['an'],
            'dob' => $data['dob'],
            'gender' => $data['gender'] ? 'male' : 'female',
            'patient_name' => $data['patient_name'],
            'patient' => [
              'ok' => true,
              'found' => true,
              'alive' => true,
              'hn' => $data['hn'],
              'patient_name' => $data['patient_name'],
              'title' => $data['patient']['title'],
              'first_name' => $data['patient']['first_name'],
              'middle_name' => null,
              'last_name' => $data['patient']['last_name'],
              'document_id' => $data['patient']['document_id'],
              'dob' => $data['patient']['dob'],
              'gender' => $data['gender'] ? 'male' : 'female',
              'race' => $data['patient']['race'],
              'nation' => $data['patient']['nation'],
              'tel_no' => str_replace('-', '', $data['patient']['tel_no']),
              'spouse' => $data['patient']['spouse'],
              'address' => $data['patient']['address'],
              'postcode' => explode(' ', $data['patient']['location'])[0] ?? null,
              'province' => $data['patient']['province'],
              'insurance_name' => $data['patient']['insurance_name'],
              'marital_status' => $data['patient']['marital_status_name'],
              'alternative_contact' => $data['patient']['alternative_contact'],
              'marital_status_name' => $data['patient']['marital_status_name'],
              'location' => explode(' ', $data['patient']['location'])[0] ?? null,
            ],
            'ward_name' => $data['ward_name'],
            'ward_name_short' => $data['ward_name_short'],
            'admitted_at' => $data['datetime_admit'],
            'discharged_at' => $data['datetime_dc'],
            'attending' => $data['attending_name'],
            'attending_license_no' => $data['attending_pln'],
            'discharge_type' => $data['discharge_type_name'],
            'discharge_status' => $data['discharge_status_name'],
            'department' => $data['patient_dept_name'],
            'division' => $data['patient_sub_dept'],
            'attending_name' => $data['attending_name'],
            'discharge_type_name' => $data['discharge_type_name'],
            'discharge_status_name' => $data['discharge_status_name'],
            'encountered_at' => $data['datetime_admit'] ? Carbon::parse($data['datetime_admit'], 'asia/bangkok')->tz('UTC') : null,
            'dismissed_at' => $data['datetime_dc'] ? Carbon::parse($data['datetime_dc'], 'asia/bangkok')->tz('UTC') : null,
        ];
    }

    protected function makePost($data)
    {
        $headers = [
            'token'  => env('SMUGGLE_TOKEN'), // Your apps token
            'secret' => env('SMUGGLE_SECRET'),
        ];
        $response = Http::acceptJson()
                        ->withHeaders($headers)
                        ->timeout(12)
                        ->post(env('SMUGGLE_URL'), $data);

        return $response->json();
    }
}

<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
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
        // return $this->makePost(['function' => 'admission', 'an' => $an]);
        return [
            'found' => false,
            'message' => __('service.failed'),
        ];
    }

    public function recentlyAdmission($hn)
    {
        // return $this->makePost(['function' => 'recently-admit', 'hn' => $hn]);
        return [
            'found' => false,
            'message' => __('service.failed'),
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

<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class HannahAPI implements  AuthenticationAPI, PatientAPI
{
    public function authenticate($login, $password)
    {
        $password = str_replace('+', 'BuAgSiGn', $password);
        $password = str_replace('%', 'PeRcEnTsIgN', $password);
        $password = str_replace('&', 'LaEsIgN', $password);
        $password = str_replace('=', 'TaOkUbSiGn', $password);

        $headers = ['token' => config('app.GATECENTER_API_SERVICE_TOKEN')];
        $options = ['timeout' => 8.0, 'verify' => false];
        $url = config('app.GATECENTER_API_SERVICE_URL');
        $response = Http::withHeaders($headers)->withOptions($options)
                         ->post($url, ['name' => $login, 'pwd' => $password]);
        
        $data = json_decode($response->getBody(),true);
       // return $data;
        if($response->status()!=200){
            return ['reply_code' => '1', 'reply_text' => 'request failed','found'=>'false'];
        }
      
        if(!$data['found']) {
            return ['reply_code' => '1', 'reply_text' => $data['body'],'found'=>'false'];
        }
       

        return [
                    'ok' => $data['found'], 
                    'found' => $data['found'],
                    'username' => $data['UserInfo']['UserData']['username'],
                    'name' => $data['UserInfo']['UserData']['full_name'],
                    'name_en' => $data['UserInfo']['UserData']['eng_name'],
                    'email' => $data['UserInfo']['UserData']['email'],
                    'org_id' => $data['UserInfo']['UserData']['sapid'],
                    'tel_no' => $data['UserInfo']['UserData']['ipPhone'] ?? null,
                    'document_id' => null,
                    'org_division_name' => $data['UserInfo']['UserData']['department'],
                    'org_position_title' => $data['UserInfo']['UserData']['position'],
                    'remark' => $data['UserInfo']['UserData']['office'],
                    'password_expires_in_days' => $data['UserInfo']['UserData']['daysLeft'],
                ];

     
    }

    public function getPatient($hn) {

        $headers = ['app' => config('app.HAN_API_SERVICE_SECRET'), 'token' => config('app.HANNAH_API_SERVICE_TOKEN')];
        $options = ['timeout' => 5.0, 'verify' => false];
        $url = config('app.HANNAH_API_SERVICE_URL') . 'patient';
        $response = Http::withHeaders($headers)->withOptions($options)
                         ->post($url, ['hn' => $hn]);
        
        $data = json_decode($response->getBody(),true);

        if (! $data || ! isset($data['found'])) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! $data['found']) { // error: $data = null
            $data['message'] = __('service.item_not_found', ['item' => 'HN']);
            unset($data['body']);

            return $data;
        }
        return $data;
    }

    public function getAdmission($an) {
   
        $headers = ['app' => config('app.HAN_API_SERVICE_SECRET'), 'token' => config('app.HANNAH_API_SERVICE_TOKEN')];
        $options = ['timeout' => 5.0, 'verify' => false];
        $url = config('app.HANNAH_API_SERVICE_URL') . 'admission';
        $response = Http::withHeaders($headers)->withOptions($options)
                         ->post($url, ['an' => $an]);
                    
        $data = json_decode($response->getBody(),true);
        
        if (! $data || ! isset($data['found'])) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if ($data['found']) {
            $data['patient']['found'] = true;
            $data['attending_name'] = $data['attending'];
            $data['discharge_type_name'] = $data['discharge_type'];
            $data['discharge_status_name'] = $data['discharge_status'];
            $data['encountered_at'] = $data['admitted_at'] ? Carbon::parse($data['admitted_at'], 'asia/bangkok')->tz('UTC') : null;
            $data['dismissed_at'] = $data['discharged_at'] ? Carbon::parse($data['discharged_at'], 'asia/bangkok')->tz('UTC') : null;
            $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
            $data['patient']['location'] = $data['patient']['postcode'];

            return $data;
        }

        return $data;
    }

    public function recentlyAdmission($hn) {

        $headers = ['app' => config('app.HAN_API_SERVICE_SECRET'), 'token' => config('app.HANNAH_API_SERVICE_TOKEN')];
        $options = ['timeout' => 5.0, 'verify' => false];
        $url = config('app.HANNAH_API_SERVICE_URL') . 'patient-recently-admit';
        $response = Http::withHeaders($headers)->withOptions($options)
                         ->post($url, ['hn' => $hn]);
        
        $data = json_decode($response->getBody(),true);

        return $data;

        if (! $data || ! isset($data['found'])) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if ($data['found']) { // error: not found found
            $data['patient']['found'] = true;
            $data['attending_name'] = $data['attending'];
            $data['discharge_type_name'] = $data['discharge_type'];
            $data['discharge_status_name'] = $data['discharge_status'];
            $data['encountered_at'] = $data['admitted_at'] ? Carbon::parse($data['admitted_at'], 'asia/bangkok')->tz('UTC') : null;
            $data['dismissed_at'] = $data['discharged_at'] ? Carbon::parse($data['discharged_at'], 'asia/bangkok')->tz('UTC') : null;
            $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
            $data['patient']['location'] = $data['patient']['postcode'];

            return $data;
        }

        $data['message'] = __('service.item_not_found', ['item' => 'admission']);
        if (isset($data['patient']) && $data['patient']['found']) { // error not found patient
            $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
            $data['patient']['location'] = $data['patient']['postcode'];

            return $data;
        }

        $data['patient']['message'] = __('service.item_not_found', ['item' => 'HN']);

        return $data;
    }


}
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

        $headers = ['app' => config('app.HAN_API_SERVICE_SECRET'),'token' => config('app.HANNAH_API_SERVICE_TOKEN')];
        $options = ['timeout' => 8.0, 'verify' => false];
        $url = config('app.HANNAH_API_SERVICE_URL').'auth';
        $response = Http::withHeaders($headers)->withOptions($options)
                         ->post($url, ['login' => $login, 'password' => $password]);
        
        $data = json_decode($response->getBody(),true);
       // return $data;
    //    \Log::info('Hannah return-------------');
    //    \Log::info($data);
        // if($response->status()!=200){
        //     return ['reply_code' => '1', 'reply_text' => 'request failed','found'=>'false'];
        // }
      
        // if(!$data['found']) {
        //     return ['reply_code' => '1', 'reply_text' => $data['body'],'found'=>'false'];
        // }

        if (! $data || ! isset($data['found'])) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! $data['ok'] || ! $data['found']) {
            $data['found'] = false;
            $data['message'] = $data['message'] ?? __('auth.failed');
            unset($data['UserInfo']);
            unset($data['body']);

            return $data;
        }
       
        return [
                    'ok' => $data['ok'], 
                    'found' => $data['found'],
                    'username' => $data['login'],
                    'name' => $data['full_name'],
                    'name_en' => $data['full_name_en'],
                    'email' => $data['email'],
                    'org_id' => $data['org_id'],
                    'tel_no' => $data['phone'] ?? null,
                    'document_id' => null,
                    'org_division_name' => $data['division_name'],
                    'org_position_title' => $data['position_name'],
                    'remark' => $data['remark'],
                    'password_expires_in_days' => $data['password_expires_in_days'],
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
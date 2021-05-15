<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class GateCenterAPI implements  AuthenticationAPI
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

    protected function callEndPoint($username,$password)
    {
        try {
            $response = Http::timeout(5)
                        ->withOptions(['verify' => false])
                        ->asForm()
                        ->post(config('services.toothpaste.url'), ['payload' => $data]);
        } catch (Exception $e) {
            return [
                'ok' => false,
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return $response->json();
    }
}
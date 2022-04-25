<?php

namespace App\Tasks;

use App\Models\Center;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class Dumper
{
    public static function centers()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'centers', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                return false;
            }

            $data = $res->json();

            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                $model = new Center();
                $model->name = $record['name'];
                $model->name_short = $record['name_short'];
                $model->active = $record['active'];
                $model->creator_id = $record['creator_id'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function users()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'users', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                return false;
            }

            $data = $res->json();
            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                $model = new User();
                $model->name = $record['name'];
                $model->login = $record['login'];
                $model->password = $record['password'];
                $model->profile = $record['profile'];
                $model->center_id = $record['center_id'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }
}

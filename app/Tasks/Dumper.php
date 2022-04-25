<?php

namespace App\Tasks;

use App\Models\Ability;
use App\Models\Admission;
use App\Models\Center;
use App\Models\Note;
use App\Models\Patient;
use App\Models\ReferCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class Dumper
{
    public static function run()
    {
        echo "center\n";
        static::centers();

        echo "\nuser\n";
        static::users();

        echo "\nability\n";
        static::abilities();

        echo "\nrole\n";
        static::roles();

        echo "\nability-role\n";
        static::abilityRole();

        echo "\nrole-user\n";
        static::roleUser();
    }

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
                if (User::whereLogin($record['login'])->first()) {
                    $record['login'] = $record['login'] . now()->format('Hi');
                }
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

    public static function abilities()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'abilities', ['startId' => $startId, 'limit' => $limit]);

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
                $model = new Ability();
                $model->name = $record['name'];
                $model->label = $record['label'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function roles()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'roles', ['startId' => $startId, 'limit' => $limit]);

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
                $model = new Role();
                $model->name = $record['name'];
                $model->label = $record['label'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function abilityRole()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'ability-role', ['startId' => $startId, 'limit' => $limit]);

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
                $role = Role::find($record['id']);
                $role->abilities()->sync(collect($record['abilities'])->pluck('id')->all());
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function roleUser()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'role-user', ['startId' => $startId, 'limit' => $limit]);

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
                $user = User::find($record['id']);
                $user->roles()->sync(collect($record['roles'])->pluck('id')->all());
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function patients()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'patients', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                $status = $res->status();
                echo "{$startId} => {$status}\n";
                return false;
            }

            $data = $res->json();
            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                if (Patient::find($record['id'])) {
                    continue;
                }
                $model = new Patient();
                $model->slug = $record['slug'];
                $model->hn = $record['hn'];
                // $model->mini_hash = $record['mini_hash'];
                $model->profile = $record['profile'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function admissions()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'admissions', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                $status = $res->status();
                echo "{$startId} => {$status}\n";
                return false;
            }

            $data = $res->json();
            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                if (Admission::find($record['id'])) {
                    continue;
                }
                $model = new Admission();
                $model->slug = $record['slug'];
                $model->an = $record['an'];
                $model->patient_id = $record['patient_id'];
                $model->meta = $record['meta'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function notes()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'notes', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                $status = $res->status();
                echo "{$startId} => {$status}\n";
                return false;
            }

            $data = $res->json();
            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                if (Note::find($record['id'])) {
                    continue;
                }
                $model = new Note();
                $model->slug = $record['slug'];
                $model->type = $record['type'];
                $model->admission_id = $record['admission_id'];
                $model->contents = $record['contents'];
                $model->user_id = $record['user_id'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function referCases()
    {
        $startId = 1;
        $limit = 10;
        do {
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->get(config('app.dump_data_url').'refer-cases', ['startId' => $startId, 'limit' => $limit]);

            if (!$res->ok()) {
                $status = $res->status();
                echo "{$startId} => {$status}\n";
                return false;
            }

            $data = $res->json();
            if (!$data) {
                return true;
            }

            $count = count($data);
            echo "{$startId} => {$count}\n";

            foreach ($data as $record) {
                if (ReferCase::find($record['id'])) {
                    continue;
                }
                $model = new ReferCase();
                $model->slug = $record['slug'];
                $model->hn = $record['hn'];
                $model->patient_name = $record['patient_name'];
                $model->patient_id = $record['patient_id'];
                $model->admission_id = $record['admission_id'];
                $model->note_id = $record['note_id'];
                $model->submitted_at = $record['submitted_at'];
                $model->meta = $record['meta'];
                $model->user_id = $record['user_id'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }
}

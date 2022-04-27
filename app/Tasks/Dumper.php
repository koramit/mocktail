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
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        echo "\npatient\n";
        static::patients();

        echo "\nadmission\n";
        static::admissions();

        echo "\nnote\n";
        static::notes();

        echo "\nreferCase\n";
        static::referCases();
    }

    public static function centers()
    {
        $startId = Center::max('id') ?? 0 + 1;
        $limit = 25;
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
        $startId = User::max('id') ?? 0 + 1;
        $limit = 25;
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
                if (User::find($record['id'])) {
                    continue;
                }
                $model = new User();
                if (User::whereName($record['name'])->first()) {
                    $record['name'] = $record['name'] . now()->format('Hi');
                }
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
        $startId = Ability::max('id') ?? 0 + 1;
        $limit = 25;
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
        $startId = Role::max('id') ?? 0 + 1;
        $limit = 25;
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
        $limit = 25;
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
        $limit = 25;
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
        $startId = Patient::max('id') ?? 0 + 1;
        $limit = 25;
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
        $startId = Admission::max('id') ?? 0 + 1;
        $limit = 25;
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
                $model->encountered_at = $record['encountered_at'];
                $model->dismissed_at = $record['dismissed_at'];
                $model->updated_at = $record['updated_at'];
                $model->created_at = $record['created_at'];
                $model->save();
            }

            $startId = $startId + $limit;
        } while (true);
    }

    public static function notes()
    {
        $startId = Note::max('id') ?? 0 + 1;
        $limit = 25;
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
        $startId = ReferCase::max('id') ?? 0 + 1;
        $limit = 25;
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

    public static function downloadAttachments()
    {
        $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                    ->get(config('app.dump_data_url').'uploaded-files', []);

        if (!$res->ok()) {
            $status = $res->status();
            echo "status => {$status}\n";
            return false;
        }

        $files = $res->json();

        $errors = [];
        foreach ($files as $file) {
            if (Storage::exists($file)) {
                continue;
            }

            $url = config('app.dump_data_url').'download-file/'.str_replace("uploads/", '', $file);
            $resource = Utils::tryFopen(storage_path('app/'.$file), 'w');
            $res = Http::withHeaders(['token' => config('app.dump_data_token')])
                        ->withOptions(['sink' => $resource])
                        ->get($url, []);

            fclose($resource);
            $status = $res->status();
            if ($status !== 200) {
                $errors[] = $file;
            }
            echo "{$file} => {$status}\n";
        }

        return $errors;
    }
}

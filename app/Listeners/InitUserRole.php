<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Support\Facades\Log;

class InitUserRole
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $profile = $event->user->profile;

        if (! isset($profile['org_id'])) {
            // non SI User
            $initRole = json_decode(file_get_contents(storage_path('app/init/role.json')), true);
            $userCenter = strtolower($event->user->center->name_short);
            if (! isset($initRole['center'][$userCenter])) {
                Log::notice($event->user->center->name.' '.$profile['full_name'].' ไม่สามารถกำหนด role ได้');

                return;
            }
            if ($profile['pln'] === $initRole['center'][$userCenter]['pln'] &&
                $profile['tel_no'] === $initRole['center'][$userCenter]['tel_no']
            ) {
                $event->user->assignRole('referer');
                $event->user->assignRole('center');

                return;
            }

            return;
        }

        if (strpos($profile['full_name'], ' พญ. ') !== false ||
            strpos($profile['full_name'], ' นพ. ') !== false ||
            strpos($profile['full_name'], 'อ.พญ.') === 0 ||
            strpos($profile['full_name'], 'อ.นพ.') === 0 ||
            strpos($profile['full_name'], 'นพ.') === 0 ||
            strpos($profile['full_name'], 'พญ.') === 0
        ) {
            // SI MD
            $event->user->assignRole('md');
        } else {
            // SI User
            $initRole = json_decode(file_get_contents(storage_path('app/init/role.json')), true);
            if (collect($initRole['root'])->search($profile['org_id']) !== false) {
                $event->user->assignRole('root');

                return;
            }

            if (collect($initRole['admin'])->search($profile['org_id']) !== false) {
                $event->user->assignRole('admin');

                return;
            }

            Log::notice($event->user->center->name.' '.$profile['full_name'].' ไม่สามารถกำหนด role ได้');
        }
    }
}
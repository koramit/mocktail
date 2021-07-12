<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $initRole = json_decode(Storage::get('init/role.json'), true);

        if (! isset($profile['org_id'])) {
            // SI MD without AD
            $mds = $initRole['si_md_no_ad'];
            foreach ($mds as $md) {
                if (
                    $md['tel_no'] === $event->user->profile['tel_no'] &&
                    $md['pln'] === $event->user->profile['pln'] &&
                    $md['center_id'] === $event->user->center->id
                    ) {
                    $event->user->assignRole('md');

                    return;
                }
            }

            // non SI User
            $referers = $initRole['referer'];
            foreach ($referers as $referer) {
                if (
                    $referer['tel_no'] === $event->user->profile['tel_no'] &&
                    $referer['pln'] === $event->user->profile['pln'] &&
                    $referer['center_id'] === $event->user->center->id
                    ) {
                    $event->user->assignRole('referer');

                    return;
                }
            }

            // center
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
            Log::notice($event->user->center->name.' '.$profile['full_name'].' ไม่สามารถกำหนด role ได้');

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
            if (collect($initRole['root'])->search($profile['org_id']) !== false) {
                $event->user->assignRole('root');
                if (config('app.env') === 'dev') {
                    $event->user->assignRole('md');
                    $event->user->assignRole('nurse');
                }

                return;
            }

            if (collect($initRole['admin'])->search($profile['org_id']) !== false) {
                $event->user->assignRole('admin');
                if (config('app.env') === 'dev') {
                    $event->user->assignRole('md');
                    $event->user->assignRole('nurse');
                }

                return;
            }

            if (collect($initRole['nurse'])->search($profile['org_id']) !== false) {
                $event->user->assignRole('nurse');

                return;
            }

            Log::notice($event->user->center->name.' '.$profile['full_name'].' ไม่สามารถกำหนด role ได้');
        }
    }
}

<?php

namespace App\Tasks;

use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Log;

class AdmitCases
{
    public function __invoke()
    {
        $workHours = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
        if (! $workHours->contains(now()->hour)) {
            return;
        }

        $cases = ReferCase::where('meta->type', 'Home Isolation')
                          ->where('meta->status', 'submitted')
                          ->get();

        $api = app()->make('App\Contracts\PatientAPI');
        $manager = new AdmissionManager();

        $caseCount = 0;
        foreach ($cases as $case) {
            $admission = $api->recentlyAdmission($case->hn);
            if (! ($admission['found'] ?? null)) {
                continue;
            }

            $admission = $manager->manage($admission['an']);
            if ($admission['found'] && $admission['admission']->meta['place_name'] === 'Home Isolation') {
                $case->status = 'admitted';
                $caseCount++;
            }
        }

        Log::notice("ADMIT CASE : {$cases->count()} cases checked, $caseCount admitted.");
    }
}

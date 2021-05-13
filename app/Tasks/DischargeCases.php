<?php

namespace App\Tasks;

use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Log;

class DischargeCases
{
    public function __invoke()
    {
        $cases = ReferCase::with('admission')
                          ->whereNotNull('admission_id')
                          ->where('meta->status', 'admitted')
                          ->get();

        $manager = new AdmissionManager();
        $caseCount = 0;
        foreach ($cases as $case) {
            if ($case->admission->dismissed_at) {
                $case->status = 'discharged';
                $caseCount++;
                continue;
            }

            $admission = $manager->manage($case->admission->an);
            if ($admission['found'] && $admission['admission']->dismissed_at) {
                $case->status = 'discharged';
                $caseCount++;
                continue;
            }
        }

        Log::notice("DISCHARGE CASE : {$cases->count()} casesa checking, $caseCount discharged.");
    }
}

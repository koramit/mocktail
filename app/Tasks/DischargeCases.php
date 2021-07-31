<?php

namespace App\Tasks;

use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Log;

class DischargeCases
{
    public function __invoke()
    {
        $workHours = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]);
        if (! $workHours->contains(now()->hour)) {
            return;
        }

        $cases = ReferCase::with('admission')
                          ->whereNotNull('admission_id')
                          ->where('meta->status', 'admitted')
                          ->orderBy('submitted_at')
                          ->limit(50)
                          ->get();

        $manager = new AdmissionManager();
        $caseCount = 0;
        $sumId = 0;
        foreach ($cases as $case) {
            $sumId += $case->id;
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

            $case->timestamps = false;
            $case->submitted_at = now();
            $case->save();
        }

        // Log::notice("DISCHARGE CASE : {$cases->count()} cases checked, $caseCount discharged. Sum ID = $sumId.");
    }
}

<?php

namespace App\Tasks;

use App\Events\CaseUpdated;
use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Log;

class AdmitCases
{
    public function __invoke()
    {
        $workHours = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]);
        if (! $workHours->contains(now()->hour)) {
            return;
        }

        $cases = ReferCase::with('note')
                          ->whereNull('admission_id')
                          ->where('meta->type', 'Home Isolation')
                          ->where('meta->status', 'submitted')
                          ->orderBy('submitted_at')
                          ->limit(20)
                          ->get();

        $api = app()->make('App\Contracts\PatientAPI');
        $manager = new AdmissionManager();

        $caseCount = 0;
        $sumId = 0;
        foreach ($cases as $case) {
            $sumId += $case->id;
            $admission = $api->recentlyAdmission($case->hn);
            if (! ($admission['found'] ?? null)) {
                $case->timestamps = false;
                $case->submitted_at = now();
                $case->save();
                continue;
            }

            $admission = $manager->manage($admission['an']);
            if ($admission['found'] && $admission['admission']->meta['place_name'] === 'Home Isolation') {
                $case->admission_id = $admission['admission']->id;
                if ($case->patient_id !== $admission['admission']->patient_id) {
                    $case->patient_id = $admission['admission']->patient_id;
                }
                $case->status = 'admitted';
                // attach admission to refer note
                $case->note->admission_id = $case->admission_id;
                $case->note->save();

                CaseUpdated::dispatch($case);

                $caseCount++;
            }

            $case->timestamps = false;
            $case->submitted_at = now();
            $case->save();
            continue;
        }

        // Log::notice("ADMIT CASE : {$cases->count()} cases checked, $caseCount admitted. Sum ID = $sumId.");
    }
}

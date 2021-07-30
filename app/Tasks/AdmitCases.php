<?php

namespace App\Tasks;

use App\Events\CaseUpdated;
use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AdmitCases
{
    public function __invoke()
    {
        $workHours = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
        if (! $workHours->contains(now()->hour)) {
            return;
        }

        $cases = ReferCase::with('note')
                          ->where('meta->type', 'Home Isolation')
                          ->where('meta->status', 'submitted')
                          ->get();

        $api = app()->make('App\Contracts\PatientAPI');
        $manager = new AdmissionManager();

        $caseCount = 0;
        $overdue = 0;
        foreach ($cases as $case) {
            if (today()->diffInDays(Carbon::create($case->note->contents['patient']['date_refer'])) >= 7) {
                $case->cancel('system', 'overdue');
                $overdue++;
                continue;
            }
            $admission = $api->recentlyAdmission($case->hn);
            if (! ($admission['found'] ?? null)) {
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
        }

        Log::notice("ADMIT CASE : {$cases->count()} cases checked, $caseCount admitted, $overdue overdue.");
    }
}

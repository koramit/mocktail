<?php

namespace App\Tasks;

use App\Models\ReferCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ClearOverdue
{
    public function __invoke()
    {
        $cases = ReferCase::with('note')
                          ->whereNull('admission_id')
                          ->where('meta->type', 'Home Isolation')
                          ->where('meta->status', 'submitted')
                          ->get();

        $overdue = 0;
        foreach ($cases as $case) {
            if (today()->diffInDays(Carbon::create($case->note->contents['patient']['date_refer'])) >= 7) {
                $case->cancel('system', 'overdue');
                $overdue++;
            }
        }

        Log::notice("CLEAR CASE : $overdue overdue.");
    }
}

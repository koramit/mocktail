<?php

namespace Database\Seeders;

use App\Events\CaseUpdated;
use App\Models\ReferCase;
use Illuminate\Database\Seeder;

class HomeReferNoteAdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cases = ReferCase::with('note')
                          ->whereNotNull('admission_id')
                          ->where('meta->type', 'Home Isolation')
                          ->where(function ($q) {
                              $q->where('meta->status', 'admitted')
                                ->orWhere('meta->status', 'discharged');
                          })->get();

        $cases->each(function ($case) {
            $case->note->admission_id = $case->admission_id;
            $case->note->save();

            CaseUpdated::dispatch($case);
        });
    }
}

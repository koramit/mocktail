<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\ReferCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class OnDemandReportController extends Controller
{
    public function __invoke($passcode)
    {
        if (! (Auth::user()->role_names->contains('admin') || Auth::user()->role_names->contains('root'))) {
            abort(403);
        }

        if ($passcode === '731-2564') { // ambu 731/2564
            function castDate($dateStr)
            {
                if (! $dateStr) {
                    return null;
                }

                return Carbon::create($dateStr)->format('d-M-Y');
            }

            function casesGenerator()
            {
                $query = ReferCase::with(['patient', 'note', 'admission'])
                                ->whereNotNull('admission_id')
                                ->whereHas('admission', function ($query) {
                                    $query->whereBetween('encountered_at', ['2021-06-30', '2022-01-01']);
                                })
                                ->orderBy(Admission::select('an')->whereColumn('admissions.id', 'refer_cases.admission_id'));

                $count = ReferCase::count();
                $chunk = 500;
                $round = ceil($count / $chunk);

                $statuses = collect(['admitted', 'discharged']);
                for ($i = 0; $i < $round; $i++) {
                    $cases = $query->skip($i * $chunk)->take($chunk)->get();
                    foreach ($cases as $case) {
                        if (! $statuses->contains($case->status)) {
                            continue;
                        }
                        $contents = $case->note->contents;
                        yield [
                            'hn' => $case->hn,
                            'name' => $case->name,
                            'an' => $case->admission->an,
                            'admit_type' => $case->meta['type'] ?? 'Hospitel',
                            'insurance' => $contents['patient']['insurance'],
                            'refer_from' => $contents['patient']['ward'] ?? null,
                            'date_onset' => castDate($contents['patient']['date_symptom_start']),
                            'date_infected' => castDate($contents['patient']['date_covid_infected']),
                            'date_admitted' => $case->admission->encountered_at->tz('asia/bangkok')->format('d-M-Y'),
                            'date_quarantine_ended' => castDate($contents['patient']['date_quarantine_end']),
                            'temperature_celsius' => $contents['vital_signs']['temperature_celsius'],
                            'estimated_temperature_celsius' => ($contents['estimations']['temperature_celsius'] ?? false) ? 'Y' : 'N',
                            'pulse_per_minute' => $contents['vital_signs']['pulse_per_minute'],
                            'estimated_pulse_per_minute' => ($contents['estimations']['pulse_per_minute'] ?? false) ? 'Y' : 'N',
                            'respiration_rate_per_minute' => $contents['vital_signs']['respiration_rate_per_minute'],
                            'estimated_respiration_rate_per_minute' => ($contents['estimations']['respiration_rate_per_minute'] ?? false) ? 'Y' : 'N',
                            'sbp' => $contents['vital_signs']['sbp'],
                            'estimated_sbp' => ($contents['estimations']['sbp'] ?? false) ? 'Y' : 'N',
                            'dbp' => $contents['vital_signs']['dbp'],
                            'estimated_dbp' => ($contents['estimations']['dbp'] ?? false) ? 'Y' : 'N',
                            'o2_sat' => $contents['vital_signs']['o2_sat'],
                            'estimated_o2_sat' => ($contents['estimations']['o2_sat'] ?? false) ? 'Y' : 'N',
                            'asymptomatic_symptom' => $contents['symptoms']['asymptomatic_symptom'] ? 'Y' : 'N',
                            'asymptomatic_detail' => $contents['symptoms']['asymptomatic_detail'],
                            'fever' => $contents['symptoms']['fever'] ? 'Y' : 'N',
                            'cough' => $contents['symptoms']['cough'] ? 'Y' : 'N',
                            'sore_throat' => $contents['symptoms']['sore_throat'] ? 'Y' : 'N',
                            'rhinorrhoea' => $contents['symptoms']['rhinorrhoea'] ? 'Y' : 'N',
                            'sputum' => $contents['symptoms']['sputum'] ? 'Y' : 'N',
                            'fatigue' => $contents['symptoms']['fatigue'] ? 'Y' : 'N',
                            'anosmia' => $contents['symptoms']['anosmia'] ? 'Y' : 'N',
                            'loss_of_taste' => $contents['symptoms']['loss_of_taste'] ? 'Y' : 'N',
                            'myalgia' => $contents['symptoms']['myalgia'] ? 'Y' : 'N',
                            'diarrhea' => $contents['symptoms']['diarrhea'] ? 'Y' : 'N',
                            'other_symptoms' => $contents['symptoms']['other_symptoms']
                                                ? str_replace("\n", '', $contents['symptoms']['other_symptoms'])
                                                : null,
                            'asymptomatic_diagnosis' => $contents['diagnosis']['asymptomatic_diagnosis'] ? 'Y' : 'N',
                            'uri' => $contents['diagnosis']['uri'] ? 'Y' : 'N',
                            'date_uri' => castDate($contents['diagnosis']['date_uri']),
                            'pneumonia' => $contents['diagnosis']['pneumonia'] ? 'Y' : 'N',
                            'date_pneumonia' => castDate($contents['diagnosis']['date_pneumonia']),
                            'gastroenteritis' => $contents['diagnosis']['gastroenteritis'] ? 'Y' : 'N',
                            'other_diagnosis' => $contents['diagnosis']['other_diagnosis']
                                                    ? str_replace("\n", '', $contents['diagnosis']['other_diagnosis'])
                                                    : null,
                            'no_comorbids' => $contents['comorbids']['no_comorbids'] ? 'Y' : 'N',
                            'dm' => $contents['comorbids']['dm'] ? 'Y' : 'N',
                            'ht' => $contents['comorbids']['ht'] ? 'Y' : 'N',
                            'other_comorbids' => $contents['comorbids']['other_comorbids']
                                                    ? str_replace("\n", '', $contents['comorbids']['other_comorbids'])
                                                    : null,
                            'temperature_per_day' => $contents['treatments']['temperature_per_day'],
                            'oxygen_sat_RA_per_day' => $contents['treatments']['oxygen_sat_RA_per_day'],
                            'favipiravir' => $contents['treatments']['favipiravir'] || ($contents['treatments']['due_to_obesity'] ?? false) ? 'Y' : 'N',
                            'favipiravir_by_bw' => $contents['treatments']['due_to_obesity'] ?? null,
                            'date_start_favipiravir' => castDate($contents['treatments']['date_start_favipiravir']),
                            'date_stop_favipiravir' => castDate($contents['treatments']['date_stop_favipiravir']),
                            'remark' => $contents['remark'] ? str_replace("\n", ' ', $contents['remark']) : null,
                            'set A' => (str_contains(strtolower($contents['remark']), 'set a') || str_contains(strtolower($contents['remark']), 'seta')) ? 'Y' : null,
                            'set B' => (str_contains(strtolower($contents['remark']), 'set b') || str_contains(strtolower($contents['remark']), 'setb')) ? 'Y' : null,
                            'set C' => (str_contains(strtolower($contents['remark']), 'set c') || str_contains(strtolower($contents['remark']), 'setc')) ? 'Y' : null,
                            'Dexa' => str_contains(strtolower($contents['remark']), 'dexa') ? 'Y' : null,
                        ];
                    }
                }
            }
            $filename = 'mocktail_713-2564@'.now()->tz(Auth::user()->timezone)->format('d-m-Y').'.xlsx';

            return FastExcel::data(casesGenerator())->download($filename);
        }

        return '🤓';
    }

    protected function castDate($dateStr)
    {
        if (! $dateStr) {
            return null;
        }

        return Carbon::create($dateStr)->format('d-M-Y');
    }
}
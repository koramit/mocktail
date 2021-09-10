<?php

namespace App\Http\Controllers\APIs;

use App\Events\CaseUpdated;
use App\Http\Controllers\Controller;
use App\Managers\HomeIsolationNoteManager;
use App\Managers\PatientManager;
use App\Managers\ReferNoteManager;
use App\Models\Note;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ReferCasesController extends Controller
{
    public function store()
    {
        $data = Request::all();

        $patient = (new PatientManager())->manage($data['hn']); // need handle error
        if (! $patient['found'] || ! collect(['Home Isolation', 'Hospitel', 'cancel'])->contains($data['refer_type'])) {
            abort(422);
        }
        $patient = $patient['patient'];
        $user = Auth::user();

        if ($data['refer_type'] === 'cancel') {
            $case = ReferCase::wherePatientId($patient->id)
                             ->where('meta->status', 'transit')
                             ->first();
            if (! $case) {
                abort(422);
            }

            $case->user_id = $user->id;
            $case->save();
            $case->delete();

            return ['ok' => true];
        }

        $contents = ($data['refer_type']) === 'Hospitel'
                    ? ReferNoteManager::initNote()
                    : HomeIsolationNoteManager::initNote();

        /// COPY DATA
        $contents['patient']['ward'] = 'ARI คลินิก';
        $contents['patient']['tel_no'] = $data['tel_no'];
        $contents['patient']['insurance'] = $data['insurance'];
        $contents['patient']['date_symptom_start'] = $data['date_symptom_start'];
        $contents['patient']['date_covid_infected'] = $data['date_covid_infected'];
        $contents['patient']['date_admit_origin'] = $data['date_refer'];
        $contents['patient']['date_refer'] = $data['date_refer'];

        $contents['vital_signs']['temperature_celsius'] = $data['temperature_celsius'];
        $contents['vital_signs']['o2_sat'] = $data['o2_sat'];

        $contents['symptoms'] = [
            'asymptomatic_symptom' => $data['asymptomatic_symptom'],
            'asymptomatic_detail' => 'ไม่มีอาการตั้งแต่ต้น',
            'fever' => $data['fever'],
            'cough' => $data['cough'],
            'sore_throat' => $data['sore_throat'],
            'rhinorrhoea' => $data['rhinorrhoea'],
            'sputum' => $data['sputum'],
            'fatigue' => $data['fatigue'],
            'anosmia' => $data['anosmia'],
            'loss_of_taste' => $data['loss_of_taste'],
            'myalgia' => $data['myalgia'],
            'diarrhea' => $data['diarrhea'],
            'other_symptoms' => $data['other_symptoms'],
        ];

        $contents['diagnosis'] = [
            'asymptomatic_diagnosis' => $data['asymptomatic_symptom'],
            'uri' => $data['fever']
                || $data['cough']
                || $data['sore_throat']
                || $data['rhinorrhoea']
                || $data['sputum']
                || $data['fatigue']
                || $data['anosmia']
                || $data['loss_of_taste']
                || $data['myalgia'],
            'date_uri' => $data['asymptomatic_symptom'] ? null : $data['date_symptom_start'],
            'pneumonia' => false,
            'date_pneumonia' => null,
            'gastroenteritis' => $data['diarrhea'],
            'other_diagnosis' => null,
        ];

        $contents['comorbids'] = [
            'no_comorbids' => $data['no_comorbids'],
            'dm' => $data['dm'],
            'ht' => $data['ht'],
            'other_comorbids' => trim(($data['dlp'] ? 'DLP ' : '').($data['obesity'] ? 'obesity ' : '').($data['other_comorbids'])),
        ];

        if ($data['refer_type'] === 'Hospitel') {
            $contents['vital_signs']['pulse_per_minute'] = 0;
            $contents['vital_signs']['respiration_rate_per_minute'] = 0;
            $contents['vital_signs']['sbp'] = 0;
            $contents['vital_signs']['dbp'] = 0;
            $contents['estimations']['pulse_per_minute'] = true;
            $contents['estimations']['respiration_rate_per_minute'] = true;
            $contents['estimations']['sbp'] = true;
            $contents['estimations']['dbp'] = true;
            $contents['patient']['meal'] = 'ปกติ';
            $contents['treatments']['temperature_per_day'] = 'วันละสองครั้งเช้าเย็น';
            $contents['treatments']['oxygen_sat_RA_per_day'] = 'วันละสองครั้งเช้าเย็น';
            if ($data['weight'] ?? false) {
                if ($data['weight'] >= 90) {
                    $contents['treatments']['due_to_obesity'] = 'BW ≥ 90 kg: Favipiravir (200) 12 tabs po q 12 h day 1 then 5 tabs po q 12 h D2-5';
                } else {
                    $contents['treatments']['due_to_obesity'] = 'BW < 90 kg: Favipiravir (200) 9 tabs po q 12 h day 1 then 4 tabs po q 12 h D2-5';
                }
            }
        }

        $contents['remark'] = $data['remark'];
        /// END COPY DATA

        // If Update Case
        $case = ReferCase::wherePatientId($patient->id)
                         ->where('meta->status', 'transit')
                         ->first();
        if ($case) {
            $case->note->contents = $contents;
            $case->note->user_id = $user->id;
            $case->note->save();
            $case->user_id = $user->id;
            $meta = $case->meta;
            $meta['type'] = $data['refer_type'];
            $meta['ward'] = $data['refer_to'];
            $case->meta = $meta;
            $case->save();
            CaseUpdated::dispatch($case);

            return ['ok' => true];
        }

        // new case
        $note = new Note();
        $note->slug = Str::uuid()->toString();
        $note->user_id = $user->id;
        $note->contents = $contents;
        $note->type = 'refer note';
        $note->save();

        $case = [
            'slug' => Str::uuid()->toString(),
            'note_id' => $note->id,
            'meta' => [
                'status' => 'transit',
                'type' => $data['refer_type'],
                'ward' => $data['refer_to'],
            ],
        ];

        $case['patient_id'] = $patient->id;
        $case['patient_name'] = $patient->full_name;
        $case = $user->referCases()->create($case);
        $case->tel_no = $data['tel_no'];
        CaseUpdated::dispatch($case);

        return ['ok' => true];
    }
}

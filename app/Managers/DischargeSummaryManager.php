<?php

namespace App\Managers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class DischargeSummaryManager extends NoteManager
{
    public function setFlashData($report = false)
    {
        // title and menu
        if ($report) {
            Request::session()->flash('page-title', 'Discharge Summary: '.($this->note->patient->full_name));
            Request::session()->flash('messages', [
                'status' => 'info',
                'messages' => [
                    'สำหรับอ่านเท่านั้น',
                ],
            ]);
        } else {
            Request::session()->flash('page-title', 'Discharge Summary: '.($this->note->patient->full_name));
            Request::session()->flash('messages', [
                'status' => 'info',
                'messages' => [
                    'สามารถแก้ไขข้อมูลได้จนกว่าจะสรุปแฟ้ม',
                ],
            ]);
        }

        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        ]);
        Request::session()->flash('action-menu', []);
    }

    public function getContents()
    {
        $contents = $this->note->contents;
        $contents['admission']['name'] = $this->note->patient->full_name;
        $contents['admission']['hn'] = $this->note->patient->hn;
        $contents['admission']['an'] = $this->note->admission->an;
        $contents['admission']['length_of_stay'] = $this->note->admission->length_of_stay.'';
        $contents['admission']['encountered_at'] = $this->note->admission->encountered_at->tz(Auth::user()->timezone)->format('d M Y H:i');
        $contents['admission']['dismissed_at'] = $this->note->admission->dismissed_at ? $this->note->admission->dismissed_at->tz(Auth::user()->timezone)->format('d M Y H:i') : null;

        return $contents;
    }

    public function getConfigs()
    {
        return [
            'discharge_status' => ['COMPLETE RECOVERY', 'IMPROVED', 'NOT IMPROVED'],
            'discharge_type' => ['WITH APPROVAL', 'AGAINST ADVICE', 'BY ESCAPE', 'BY REFER'],
            'center' => $this->note->admission->referCase->center->name,
            'complications' => [
                ['name' => 'dyspnea', 'label' => 'Dyspnea'],
                ['name' => 'chest_discomfort', 'label' => 'Chest discomfort'],
                ['name' => 'fever', 'label' => 'Fever (T > 37.5 ℃)'],
                ['name' => 'headache', 'label' => 'Headache'],
                ['name' => 'diarrhea', 'label' => 'Diarrhea'],
                ['name' => 'desaturation', 'label' => 'Desaturation'],
            ],
            'symptoms' => [
                ['label' => 'ไข้', 'name' => 'fever'],
                ['label' => 'ไอ', 'name' => 'cough'],
                ['label' => 'เจ็บคอ', 'name' => 'sore_throat'],
                ['label' => 'มีน้ำมูก', 'name' => 'rhinorrhoea'],
                ['label' => 'มีเสมหะ', 'name' => 'sputum'],
                ['label' => 'เหนื่อย', 'name' => 'fatigue'],
                ['label' => 'จมูกไม่ได้กลิ่น', 'name' => 'anosmia'],
                ['label' => 'ลิ้นไม่ได้รส', 'name' => 'loss_of_taste'],
                ['label' => 'ปวดเมื่อยกล้ามเนื้อ', 'name' => 'myalgia'],
                ['label' => 'ท้องเสีย', 'name' => 'diarrhea'],
            ],
            'patchEndpoint' => url('/forms/'.$this->note->id),
        ];
    }

    public function getForm()
    {
        return 'Forms/DischargeSummary';
    }

    public function transferData()
    {
        $referNote = $this->note->admission->notes()->whereType('refer note')->first()->contents;
        $contents = $this->note->contents;
        $contents['comorbids'] = $referNote['comorbids'];
        $this->note->contents = $contents;

        return $this->note->save();
    }

    public static function initNote()
    {
        return [
            'discharge' => [
                'discharge_status' => null,
                'discharge_type' => null,
                'refer_to' => null,
            ],
            'diagnosis' => [
                'asymptomatic_diagnosis' => true,
                'uri' => false,
                'date_uri' => null,
                'pneumonia' => false,
                'date_pneumonia' => null,
                'gastroenteritis' => false,
                'other_diagnosis' => null,
            ],
            'comorbids' => [
                'no_comorbids' => true,
                'dm' => false,
                'ht' => false,
                'other_comorbids' => null,
            ],
            'complications' => [
                'no_complications' => true,
                'dyspnea' => false,
                'chest_discomfort' => false,
                'fever' => false,
                'headache' => false,
                'diarrhea' => false,
                'desaturation' => false,
                'desaturation_specific' => null,
                'other_complications' => null,
            ],
            'non_OR_procedures' => [
                'no_non_OR_procedures' => true,
                'non_OR_procedures_detail' => null,
            ],
            'vital_signs' => [
                'temperature_celsius' => null,
                'pulse_per_minute' => null,
                'respiration_rate_per_minute' => null,
                'sbp' => null,
                'dbp' => null,
                'o2_sat' => null,
            ],
            'symptoms' => [
                'asymptomatic_symptom' => true,
                'asymptomatic_detail' => null,
                'fever' => false,
                'cough' => false,
                'sore_throat' => false,
                'rhinorrhoea' => false,
                'sputum' => false,
                'fatigue' => false,
                'anosmia' => false,
                'loss_of_taste' => false,
                'myalgia' => false,
                'diarrhea' => false,
                'other_symptoms' => null,
            ],
            'problem_list' => [
                'no_problem_list' => true,
                'quarantine' => false,
                'other_problem_list' => null,
            ],
            'appointment' => [
                'no_appointment' => true,
                'date_appointment' => null,
                'appointment_at' => null,
            ],
            'repeat_NP_swab' => [
                'no_repeat_NP_swab' => true,
                'date_repeat_NP_swab' => null,
            ],
        ];
    }
}

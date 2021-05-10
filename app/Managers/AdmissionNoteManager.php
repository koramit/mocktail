<?php

namespace App\Managers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdmissionNoteManager extends NoteManager
{
    public function setFlashData($report = false)
    {
        // title and menu
        if ($report) {
            Request::session()->flash('page-title', 'Admission note: '.($this->note->patient->full_name));
            Request::session()->flash('messages', [
                'status' => 'info',
                'messages' => [
                    'สำหรับอ่านเท่านั้น',
                ],
            ]);
        } else {
            Request::session()->flash('page-title', 'เขียน Admission note: '.($this->note->patient->full_name));
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
        $contents['admission']['encountered_at'] = $this->note->admission->encountered_at->tz(Auth::user()->timezone)->format('d M Y H:i');

        return $contents;
    }

    public function getConfigs()
    {
        return [
            'insurances' => ['กรมบัญชีกลาง', 'ประกันสังคม', '30 บาท', 'ชำระเงินเอง'],
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
            // 'note_id' => $this->note->id,
            // 'author_username' => $this->note->author->name,
            // 'author' => $this->note->author->full_name,
            // 'contact' => $this->note->author->tel_no,
            // 'center' => $this->note->center->name,
        ];
    }

    public function getForm()
    {
        return 'Forms/AdmissionNote';
    }

    public function transferData()
    {
        $referNote = $this->note->admission->notes()->whereType('refer note')->first()->contents;
        $contents = [];

        foreach ($this->note->contents as $key => $value) {
            foreach ($value as $index => $data) {
                $contents[$key][$index] = $referNote[$key][$index];
            }
        }
        $this->note->contents = $contents;

        return $this->note->save();
    }

    public static function initNote()
    {
        return [
            'patient' => [
                'sat_code' => null,
                'insurance' => null,
                'date_symptom_start' => null,
                'date_covid_infected' => null,
                'date_admit_origin' => null,
                'date_refer' => null,
                'date_expect_discharge' => null,
                'date_quarantine_end' => null,
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
                'asymptomatic_symptom' => false,
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
            'diagnosis' => [
                'asymptomatic_diagnosis' => false,
                'uri' => false,
                'date_uri' => null,
                'pneumonia' => false,
                'date_pneumonia' => null,
                'gastroenteritis' => false,
                'other_diagnosis' => null,
            ],
            'adr' => [
                'no_adr' => false,
                'adr_detail' => null,
            ],
            'comorbids' => [
                'no_comorbids' => false,
                'dm' => false,
                'ht' => false,
                'other_comorbids' => null,
            ],
            'treatments' => [
                'temperature_per_day' => null,
                'oxygen_sat_RA_per_day' => null,
                'favipiravir' => false,
                'date_start_favipiravir' => null,
                'date_stop_favipiravir' => null,
                'date_repeat_NP_swap' => null,
            ],
        ];
    }
}

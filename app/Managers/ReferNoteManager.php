<?php

namespace App\Managers;

use App\Models\Note;
use Illuminate\Support\Facades\Request;

class ReferNoteManager
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function setFlashData()
    {
        // title and menu
        Request::session()->flash('page-title', 'เขียนใบส่งตัว: '.($this->note->referCase->patient_name ?? 'ยังไม่มีชื่อ'));
        Request::session()->flash('messages', [
            'status' => 'info',
            'messages' => [
                'สามารถกลับมาลงข้อมูลต่อภายหลังได้',
                'เมื่อลงข้อมูลครบแล้วให้ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> ท้ายฟอร์ม',
                'เมื่อ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> แล้วยังสามารถแก้ไขข้อมูลได้อยู่จนกว่าเคสจะแอดมิด',
            ],
        ]);
        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
            // ['icon' => 'clinic', 'label' => 'Clinics', 'route' => 'prototypes/ClinicsIndex'],
            // ['icon' => 'procedure', 'label' => 'Procedures', 'route' => 'prototypes/ProceduresIndex'],
        ]);
        Request::session()->flash('action-menu', [
            // ['icon' => 'wheelchair', 'label' => 'Add Stay case', 'action' => 'add-stay-case'],
            // ['icon' => 'ambulance', 'label' => 'Add Stay case without HN (soon... 😤)', 'action' => 'not-ready'],
            // ['icon' => 'procedure', 'label' => 'Add IPD case (later... 😅)', 'action' => 'not-ready'],
        ]);
    }

    public function getContents()
    {
        $contents = $this->note->contents;
        $contents['patient']['name'] = $this->note->referCase->patient_name;
        $contents['patient']['hn'] = $this->note->referCase->patient ? $this->note->referCase->patient->hn : $this->note->referCase->hn;

        return $contents;
    }

    public function getConfigs()
    {
        return [
            'insurances' => ['กรมบัญชีกลาง', 'ประกันสังคม', '30 บาท', 'ชำระเงินเอง'],
            'meals' => ['ปกติ', 'อิสลาม', 'มังสวิรัติ'],
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
            'note_id' => $this->note->id,
        ];
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
                'meal' => null,
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
                'asymptomatic' => false,
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
                'others' => null,
            ],
            'adr' => [
                'none' => false,
                'detail' => null,
            ],
            'comorbids' => [
                'dm' => false,
                'ht' => false,
                'others' => null,
            ],
            'treatments' => [
                'temperature_per_day' => null,
                'oxygen_sat_RA' => null,
                'favipiravir' => false,
                'date_start_favipiravir' => null,
                'date_stop_favipiravir' => null,
                'date_repeat_NP_swap' => null,
            ],
            'diagnosis' => [
                'asymptomatic' => false,
                'uri' => false,
                'date_uri' => null,
                'pneumonia' => false,
                'date_pneumonia' => null,
                'gastroenteritis' => false,
                'others' => null,
            ],
            'uploads' => [
                'film' => null,
                'lab' => null,
                'id_document' => null,
            ],
        ];
    }

    public static function validate()
    {
        Request::validate([
            'sat_code' => 'required',
            'insurance' => 'required',
            'date_symptom_start' => 'required',
            'date_covid_infected' => 'required',
            'date_admit_origin' => 'required',
            'date_refer' => 'required',
            'date_expect_discharge' => 'required',
            'date_quarantine_end' => 'required',
            'meal' => 'required',
        ]);
    }
}

<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReferNoteManager
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function setFlashData($report = false)
    {
        // title and menu
        if ($report) {
            Request::session()->flash('page-title', 'ใบส่งตัว: '.($this->note->referCase->patient_name ?? 'ยังไม่มีชื่อ'));
            Request::session()->flash('messages', [
                'status' => 'info',
                'messages' => [
                    'สำหรับอ่านเท่านั้น',
                ],
            ]);
        } else {
            Request::session()->flash('page-title', 'เขียนใบส่งตัว: '.($this->note->referCase->patient_name ?? 'ยังไม่มีชื่อ'));
            Request::session()->flash('messages', [
                'status' => 'info',
                'messages' => [
                    'สามารถกลับมาลงข้อมูลต่อภายหลังได้',
                    'เมื่อลงข้อมูลครบแล้วให้ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> ท้ายฟอร์ม',
                    'เมื่อ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> แล้วยังสามารถแก้ไขข้อมูลได้อยู่จนกว่าเคสจะแอดมิด',
                ],
            ]);
        }

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
            'uploads' => [
                'film' => null,
                'lab' => null,
                'id_document' => null,
            ],
        ];
    }

    public static function validate(Note $note)
    {
        $rules = [
            'sat_code' => 'required|alpha_num|size:18',
            'insurance' => 'required|string',
            'date_symptom_start' => 'required|date',
            'date_covid_infected' => 'required|date',
            'date_admit_origin' => 'required|date',
            'date_refer' => 'required|date',
            'date_expect_discharge' => 'required|date',
            'date_quarantine_end' => 'required|date',
            'meal' => 'required|string',

            'temperature_celsius' => 'required|numeric',
            'pulse_per_minute' => 'required|integer',
            'respiration_rate_per_minute' => 'required|integer',
            'sbp' => 'required|integer',
            'dbp' => 'required|integer',
            'o2_sat' => 'required|integer',

            'temperature_per_day' => 'required|string',
            'oxygen_sat_RA_per_day' => 'required|string',
        ];

        if (Session::get('center')->id === config('app.main_center_id')) {
            $rules['hn'] = 'required|digits:8';
        }

        $data = Request::all();
        Validator::make($data['patient'] + $data['vital_signs'] + $data['treatments'], $rules)->validate();

        $patient = $data['patient'];

        // validate hn
        $patientExists = (new PatientManager())->manage($patient['hn']);
        if (! $patientExists['found']) {
            return [
                'hn' => 'ไม่พบ HN นี้ในระบบ',
            ];
        } else {
            if ($note->referCase->patient_id !== $patientExists['patient']->id) {
                $note->referCase->patient_id = $patientExists['patient']->id;
                $note->referCase->save();
            }
        }

        // *** validate timeline
        $timeline = [
            'date_symptom_start',
            'date_covid_infected',
            'date_admit_origin',
            'date_refer',
            'date_expect_discharge',
            'date_quarantine_end',
        ];
        for ($i = 0; $i < count($timeline) - 1; $i++) {
            if (Carbon::create($patient[$timeline[$i + 1]])->lessThanOrEqualTo(Carbon::create($patient[$timeline[$i]]))) {
                return [
                    $timeline[$i] => 'ลำดับเวลาไม่สอดคล้อง',
                    $timeline[$i + 1] => 'ลำดับเวลาไม่สอดคล้อง',
                ];
            }
        }

        $errors = []; // we can combine errors now

        // validate symptoms
        $symptoms = $data['symptoms'];
        if ($symptoms['asymptomatic_symptom']) {
            if (! $symptoms['asymptomatic_detail']) {  // if no symtoms then need detail
                $errors['asymptomatic_detail'] = 'โปรดระบุรายละเอียด';
            }
        } else {
            if (! ($symptoms['fever'] ||
                $symptoms['cough'] ||
                $symptoms['sore_throat'] ||
                $symptoms['rhinorrhoea'] ||
                $symptoms['sputum'] ||
                $symptoms['fatigue'] ||
                $symptoms['anosmia'] ||
                $symptoms['loss_of_taste'] ||
                $symptoms['myalgia'] ||
                $symptoms['diarrhea'] ||
                $symptoms['other_symptoms'])
            ) { // if not asymptomatic then need some symptoms
                $errors['symptoms'] = 'โปรดระบุอาการแสดง หากไม่มีอาการโปรดเลือก Asymptomatic';
            }
        }

        // validate diagnosis
        $diagnosis = $data['diagnosis'];
        if (! $diagnosis['asymptomatic_diagnosis']) {
            if (! ($diagnosis['uri'] ||
                $diagnosis['pneumonia'] ||
                $diagnosis['gastroenteritis'] ||
                $diagnosis['other_diagnosis'])
            ) {
                $errors['diagnosis'] = 'โปรดระบุการวินิจฉัย';
            }

            if ($diagnosis['uri']) {
                if (! $diagnosis['date_uri']) {
                    $errors['date_uri'] = 'จำเป็นต้องลง วันที่เริ่มมีอาการ URI';
                } elseif (
                    Carbon::create($diagnosis['date_uri'])->lessThan(Carbon::create($patient['date_symptom_start'])) ||
                    Carbon::create($diagnosis['date_uri'])->greaterThanOrEqualTo(Carbon::create($patient['date_refer']))
                ) {
                    $errors['date_uri'] = 'วันที่เริ่มมีอาการ URI ควรอยู่ระหว่างวันแรกที่มีอาการและวันที่ส่งผู้ป่วยไป Hospitel';
                }
            }

            if ($diagnosis['pneumonia']) {
                if (! $diagnosis['date_pneumonia']) {
                    $errors['date_pneumonia'] = 'จำเป็นต้องลง วันที่เริ่มมีอาการ PNEUMONIA';
                } elseif (
                    Carbon::create($diagnosis['date_pneumonia'])->lessThan(Carbon::create($patient['date_symptom_start'])) ||
                    Carbon::create($diagnosis['date_pneumonia'])->greaterThanOrEqualTo(Carbon::create($patient['date_refer']))
                ) {
                    $errors['date_pneumonia'] = 'วันที่เริ่มมีอาการ PNEUMONIA ควรอยู่ระหว่างวันแรกที่มีอาการและวันที่ส่งผู้ป่วยไป Hospitel';
                }
            }
        }

        // validate adr
        $adr = $data['adr'];
        if (! $adr['no_adr'] && ! $adr['adr_detail']) {
            $errors['adr_detail'] = 'จำเป็นต้องลง ยา/อาหารที่แพ้ หากไม่มีโปรดเลือกไม่แพ้';
        }

        // validate comorbids
        $comorbids = $data['comorbids'];
        if (! $comorbids['no_comorbids']) {
            if (! ($comorbids['dm'] ||
                $comorbids['ht'] ||
                $comorbids['other_comorbids'])
            ) {
                $errors['comorbids'] = 'โปรดระบุโรคประจำตัว';
            }
        }

        // treatments validate
        $treatments = $data['treatments'];
        if ($treatments['favipiravir']) {
            if (! $treatments['date_start_favipiravir']) {
                $errors['date_start_favipiravir'] = 'จำเป็นต้องลง วันที่เริ่มยา';
            } elseif (
                Carbon::create($treatments['date_start_favipiravir'])->lessThan(Carbon::create($patient['date_admit_origin'])) ||
                Carbon::create($treatments['date_start_favipiravir'])->greaterThanOrEqualTo(Carbon::create($patient['date_refer']))
            ) {
                $errors['date_start_favipiravir'] = 'วันที่เริ่มยา ควรอยู่ระหว่างวันที่รับไว้ในโรงพยาบาลและวันที่ส่งผู้ป่วยไป Hospitel';
            }

            if (! $treatments['date_stop_favipiravir']) {
                $errors['date_stop_favipiravir'] = 'จำเป็นต้องลง กำหนดครบวันที่';
            } elseif ($treatments['date_start_favipiravir'] && (
                Carbon::create($treatments['date_stop_favipiravir'])->lessThan(Carbon::create($treatments['date_start_favipiravir'])) ||
                Carbon::create($treatments['date_stop_favipiravir'])->greaterThanOrEqualTo(Carbon::create($patient['date_refer']))
            )) {
                $errors['date_stop_favipiravir'] = 'กำหนดครบวันที่ ควรอยู่ระหว่างวันที่เริ่มยาและวันที่ส่งผู้ป่วยไป Hospitel';
            }
        }

        // treatments validate
        $uploads = $data['uploads'];
        if (! $uploads['film']) {
            $errors['film'] = ['จำเป็นต้องแนบภาพ Film Chest ล่าสุด'];
        } elseif (! Storage::exists('uploads/'.$uploads['film'])) {
            $errors['film'] = ['กรุณาแนบไฟล์ใหม่'];
        }

        if (! $uploads['lab']) {
            $errors['lab'] = ['จำเป็นต้องแนบภาพ ใบรายงานผล COVID'];
        } elseif (! Storage::exists('uploads/'.$uploads['lab'])) {
            $errors['lab'] = ['กรุณาแนบไฟล์ใหม่'];
        }

        if (! $patient['hn']) {
            if (! $uploads['id_document']) {
                $errors['id_document'] = ['จำเป็นต้องแนบภาพ รูปถ่ายหน้าบัตรประชาชน'];
            } elseif (! Storage::exists('uploads/'.$uploads['id_document'])) {
                $errors['id_document'] = ['กรุณาแนบไฟล์ใหม่'];
            }
        }

        // check candidate keys
        $count = Note::where('contents->patient->sat_code', $patient['sat_code'])
                     ->where('contents->patient->date_admit_origin', $patient['date_admit_origin'])
                     ->count();
        if ($count > 1) {
            $errors['sat_code'] = 'เคสซ้ำ โปรดตรวจสอบ SAT CODE และ วันที่รับไว้ในโรงพยาบาล';
        }

        if (count($errors) > 0) {
            return $errors;
        }
    }
}

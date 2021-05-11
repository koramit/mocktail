<?php

namespace App\Managers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

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
                    'สามารถกลับมาเขียนต่อภายหลังได้',
                    'เมื่อเขียนเสร็จแล้วให้ <span class="font-semibold">เผยแพร่โน๊ต</span> ท้ายฟอร์ม',
                    'เมื่อ <span class="font-semibold">เผยแพร่โน๊ต</span> แล้วยังสามารถกลับมาแก้ไขได้จนกว่าจะสรุปแฟ้ม',
                ],
            ]);
        }

        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
            ['icon' => 'clipboard-list', 'label' => 'โน๊ตของเคสนี้', 'route' => 'refer-cases/'.$this->note->admission->referCase->slug.'/notes'],
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
            'note_id' => $this->note->id,
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
            if (! collect(['array', 'object'])->contains(gettype($value))) {
                continue;
            }
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
            'submitted' => false,
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

    public static function validate()
    {
        $rules = [
            'temperature_celsius' => 'required|numeric',
            'pulse_per_minute' => 'required|integer',
            'respiration_rate_per_minute' => 'required|integer',
            'sbp' => 'required|integer',
            'dbp' => 'required|integer',
            'o2_sat' => 'required|integer',

            'temperature_per_day' => 'required|string',
            'oxygen_sat_RA_per_day' => 'required|string',

            'adr_detail' => 'exclude_if:no_adr,true|required|string',
        ];

        $errors = [];
        $data = Request::all();
        $validator = Validator::make($data['patient'] + $data['vital_signs'] + $data['treatments'] + $data['adr'], $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
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

        $patient = $data['patient'];

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
                } elseif ( // date_uri MUST >= date_symptom_start
                    ! Carbon::create($diagnosis['date_uri'])->greaterThanOrEqualTo(Carbon::create($patient['date_symptom_start']))
                ) {
                    $errors['date_uri'] = 'ข้อมูลไม่สอดคล้องกับ วันแรกที่มีอาการ';
                }
            }

            if ($diagnosis['pneumonia']) {
                if (! $diagnosis['date_pneumonia']) {
                    $errors['date_pneumonia'] = 'จำเป็นต้องลง วันที่เริ่มมีอาการ PNEUMONIA';
                } elseif ( // date_pneumonia MUST >= date_symptom_start
                    ! Carbon::create($diagnosis['date_uri'])->greaterThanOrEqualTo(Carbon::create($patient['date_symptom_start']))
                ) {
                    $errors['date_pneumonia'] = 'ข้อมูลไม่สอดคล้องกับ วันแรกที่มีอาการ';
                }
            }
        }

        // validate treatments
        $treatments = $data['treatments'];
        if ($treatments['favipiravir']) {
            if (! $treatments['date_start_favipiravir']) {
                $errors['date_start_favipiravir'] = 'จำเป็นต้องลง วันที่เริ่มยา';
            } elseif ( // date_start_favipiravir MUST >= date_admit_origin
                ! Carbon::create($treatments['date_start_favipiravir'])->greaterThanOrEqualTo(Carbon::create($patient['date_admit_origin']))
            ) {
                $errors['date_start_favipiravir'] = 'ข้อมูลไม่สอดคล้องกับ วันที่รับไว้ในโรงพยาบาล';
            }

            if (! $treatments['date_stop_favipiravir']) {
                $errors['date_stop_favipiravir'] = 'จำเป็นต้องลง กำหนดครบวันที่';
            } elseif ($treatments['date_start_favipiravir'] && ( // date_stop_favipiravir MUST > date_stop_favipiravir
                ! Carbon::create($treatments['date_stop_favipiravir'])->greaterThan(Carbon::create($treatments['date_start_favipiravir']))
            )) {
                $errors['date_stop_favipiravir'] = 'ข้อมูลไม่สอดคล้องกับ วันที่เริ่มยา';
            }
        }
        // date_repeat_NP_swap >= date_expect_discharge
        if ($treatments['date_repeat_NP_swap'] && ! Carbon::create($treatments['date_repeat_NP_swap'])->greaterThanOrEqualTo(Carbon::create($patient['date_expect_discharge']))) { // timeline fails
            $errors['date_repeat_NP_swap'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ครบกำหนดนอนใน hospitel';
        }

        if (count($errors) > 0) {
            return $errors;
        }
    }
}

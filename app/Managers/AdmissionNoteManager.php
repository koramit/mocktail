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
        Request::session()->flash('page-title', 'Admission note: AN '.$this->note->admission->an.' '.($this->note->patient->full_name));
        if ($report) {
            Request::session()->flash('messages', null);
        } else {
            if ($this->note->contents['submitted']) {
                Request::session()->flash('messages', [
                    'status' => 'warning',
                    'messages' => ['โปรดกด <span class="font-semibold">ปรับปรุง</span> ทุกครั้งหลังแก้ไขข้อมูล'],
                ]);
            } else {
                Request::session()->flash('messages', [
                    'status' => 'info',
                    'messages' => [
                        'สามารถกลับมาเขียนต่อภายหลังได้',
                        'เมื่อเขียนเสร็จแล้วให้ <span class="font-semibold">เผยแพร่โน๊ต</span> ท้ายฟอร์ม',
                        'เมื่อ <span class="font-semibold">เผยแพร่โน๊ต</span> แล้วยังสามารถกลับมาแก้ไขได้จนกว่าจะสรุปแฟ้ม',
                    ],
                ]);
            }
        }

        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
            ['icon' => 'folder-open', 'label' => 'โน๊ตของเคสนี้', 'route' => 'refer-cases/'.$this->note->admission->referCase->slug.'/notes'],
        ]);
        Request::session()->flash('action-menu', []);
    }

    public function getContents($report = false)
    {
        $contents = $this->note->contents;
        $contents['admission']['name'] = $this->note->patient->full_name;
        $contents['admission']['hn'] = $this->note->patient->hn;
        $contents['admission']['an'] = $this->note->admission->an;
        $contents['admission']['encountered_at'] = $this->note->admission->encountered_at->tz(Auth::user()->timezone)->format('d M Y H:i');

        // check new keys, set them if not already set
        $this->checkNewKeys($contents);

        if (! $report) {
            return $contents;
        }

        // admission
        $contents['admission']['ward'] = $this->note->admission->meta['place_name'];
        $contents['admission']['attending'] = $this->note->admission->meta['attending'];
        $contents['admission']['age'] = $this->note->admission->patient_age_at_encounter.' '.$this->note->admission->patient_age_at_encounter_unit;

        $contents['author'] = [
            'name' => $this->note->author->full_name,
            'pln' =>  $this->note->author->pln,
            'tel_no' =>  $this->note->author->tel_no,
            'updated_at' => $this->note->updated_at->tz(Auth::user()->timezone)->format('d M Y H:i:s'),
        ];

        // symptoms
        $symptoms = $contents['symptoms'];
        if ($symptoms['asymptomatic_symptom']) {
            $contents['symptoms'] = 'Asymptomatic '.$symptoms['asymptomatic_detail'];
        } else {
            $symptomsList = $this->getConfigs()['symptoms'];
            $text = '';
            foreach ($symptomsList as $symptom) {
                if ($symptoms[$symptom['name']]) {
                    $text .= "{$symptom['label']} ";
                }
            }

            $text .= $symptoms['other_symptoms'];
            $contents['symptoms'] = $text;
        }

        // diagnosis
        $diagnosis = $contents['diagnosis'];
        if ($diagnosis['asymptomatic_diagnosis']) {
            $contents['diagnosis'] = 'Asymptomatic COVID 19 infection';
        } else {
            $text = '';
            if ($diagnosis['uri']) {
                $text .= ('COVID 19 with URI เมื่อ '.$this->getDateString($diagnosis['date_uri']).'<br>');
            }

            if ($diagnosis['pneumonia']) {
                $text .= ('COVID 19 with Pneumonia เมื่อ '.$this->getDateString($diagnosis['date_pneumonia']).'<br>');
            }

            if ($diagnosis['gastroenteritis']) {
                $text .= 'COVID 19 with Gastroenteritis<br>';
            }

            $text .= $diagnosis['other_diagnosis'];
            $contents['diagnosis'] = $text;
        }

        // adr
        if ($contents['adr']['no_adr']) {
            $contents['adr'] = 'ไม่แพ้';
        } else {
            $contents['adr'] = $contents['adr']['adr_detail'];
        }

        // comordibs
        $comorbids = $contents['comorbids'];
        if ($comorbids['no_comorbids']) {
            $contents['comorbids'] = 'ไม่มี';
        } else {
            $text = '';
            if ($comorbids['dm']) {
                $text .= 'เบาหวาน ';
            }

            if ($comorbids['ht']) {
                $text .= 'ความดันโลหิตสูง ';
            }

            $text .= $comorbids['other_comorbids'];
            $contents['comorbids'] = $text;
        }

        // treatment
        $treatments = $contents['treatments'];
        if (! $treatments['favipiravir']) {
            unset($treatments['favipiravir'], $treatments['date_start_favipiravir'], $treatments['date_stop_favipiravir']);
        } else {
            $treatments['date_start_favipiravir'] = $this->getDateString($treatments['date_start_favipiravir']);
            $treatments['date_stop_favipiravir'] = $this->getDateString($treatments['date_stop_favipiravir']);
        }
        if (! $treatments['date_repeat_NP_swap']) {
            unset($treatments['date_repeat_NP_swap']);
        } else {
            $treatments['date_repeat_NP_swap'] = $this->getDateString($treatments['date_repeat_NP_swap']);
        }
        $contents['treatments'] = $treatments;

        // remark
        if ($contents['remark']) {
            $lines = explode("\n", $contents['remark']);
            if (count($lines) > 1) {
                $contents['remark'] = collect($lines)->map(function ($line) {
                    return "<p>{$line}</p>";
                })->join('');
            }
        }

        return $contents;
    }

    public function checkNewKeys(&$contents)
    {
        if (! isset($contents['remark'])) {
            $contents['remark'] = null;
        }
    }

    public function getConfigs($report = false)
    {
        if (! $report) {
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
            ];
        }

        $configs = [
            'note_slug' => $this->note->slug,
            'patient' => [
                ['label' => 'วันแรกที่มีอาการ', 'name' => 'date_symptom_start', 'format' => 'date'],
                ['label' => 'วันที่ตรวจพบเชื้อ', 'name' => 'date_covid_infected', 'format' => 'date'],
                ['label' => 'วันที่รับไว้ในโรงพยาบาล', 'name' => 'date_admit_origin', 'format' => 'date'],
                ['label' => 'วันที่ส่งผู้ป่วยไป Hospitel', 'name' => 'date_refer', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดนอนใน hospitel', 'name' => 'date_expect_discharge', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน', 'name' => 'date_quarantine_end', 'format' => 'date'],
            ],
            'admission' => [
                ['label' => 'an', 'name' => 'an'],
                ['label' => 'hn', 'name' => 'hn'],
                ['label' => 'ชื่อผู้ป่วย', 'name' => 'name'],
                ['label' => 'อายุ', 'name' => 'age'],
                ['label' => 'วันเวลาที่แอดมิท', 'name' => 'encountered_at'],
                ['label' => 'หอผู้ป่วย', 'name' => 'ward'],
                ['label' => 'แพทย์เจ้าของไข้', 'name' => 'attending'],
            ],
            'vital_signs' => [
                ['label' => 'Temp (℃)', 'name' => 'temperature_celsius'],
                ['label' => 'Pulse (min)', 'name' => 'pulse_per_minute'],
                ['label' => 'RR (min)', 'name' => 'respiration_rate_per_minute'],
                ['label' => 'SBP (mmHg)', 'name' => 'sbp'],
                ['label' => 'DBP (mmHg)', 'name' => 'dbp'],
                ['label' => 'O₂ sat (% RA)', 'name' => 'o2_sat'],
                ['label' => 'Level of consciousness', 'name' => 'level_of_consciousness'],
                ['label' => 'emotional status', 'name' => 'emotional_status'],
            ],
            'topics' => [
                ['label' => 'บันทึกอาการแสดง', 'name' => 'symptoms'],
                ['label' => 'วินิจฉัย', 'name' => 'diagnosis'],
                ['label' => 'ประวัติแพ้ยา/อาหาร ', 'name' => 'adr'],
                ['label' => 'โรคประจำตัว ', 'name' => 'comorbids'],
            ],
            'treatments' => [
                ['label' => 'Temperature', 'name' => 'temperature_per_day'],
                ['label' => 'Oxygen sat RA', 'name' => 'oxygen_sat_RA_per_day'],
                ['label' => 'Favipiravir (วันที่เริ่มยา)', 'name' => 'date_start_favipiravir'],
                ['label' => 'Favipiravir (กำหนดครบวันที่)', 'name' => 'date_stop_favipiravir'],
                ['label' => 'นัดมาทำ NP swab ซ้ำ วันที่', 'name' => 'date_repeat_NP_swap'],
            ],
        ];

        return $configs;
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
                if (isset($referNote[$key][$index])) {
                    $contents[$key][$index] = $referNote[$key][$index];
                } else {
                    $contents[$key][$index] = $this->note->contents[$key][$index];
                }
            }
        }
        if (isset($referNote['remark'])) {
            $contents['remark'] = $referNote['remark'];
        }
        $this->note->contents = $contents;

        return $this->note->save();
    }

    public static function initNote()
    {
        return [
            'submitted' => false,
            'remark' => null,
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
                'level_of_consciousness' => ' Alert, Oriented, Cooperate',
                'emotional_status' => 'Calm',
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

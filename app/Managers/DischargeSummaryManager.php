<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class DischargeSummaryManager extends NoteManager
{
    public function setFlashData($report = false)
    {
        // title and menu
        Request::session()->flash('page-title', 'Discharge Summary: AN '.$this->note->admission->an.' '.($this->note->patient->full_name));
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
        $contents['admission']['length_of_stay'] = $this->note->admission->length_of_stay.'';
        $contents['admission']['encountered_at'] = $this->note->admission->encountered_at->tz($this->user->timezone)->format('d M Y H:i');
        $contents['admission']['dismissed_at'] = $this->note->admission->dismissed_at ? $this->note->admission->dismissed_at->tz($this->user->timezone)->format('d M Y H:i') : null;

        // check new keys, set them if not already set
        $this->checkNewKeys($contents);
        if (! $report) {
            return $contents;
        }

        // author
        $contents['author'] = [
            'name' => $this->note->author->full_name,
            'pln' =>  $this->note->author->pln,
            'tel_no' =>  $this->note->author->tel_no,
            'updated_at' => $this->note->updated_at->tz($this->user->timezone)->format('d M Y H:i:s'),
        ];

        // admission
        $contents['admission']['ward'] = $this->note->admission->meta['place_name'];
        $contents['admission']['attending'] = $this->note->admission->meta['attending'];
        $contents['admission']['discharge_status'] = $contents['discharge']['discharge_status'];
        $contents['admission']['discharge_type'] = $contents['discharge']['discharge_type'];
        $contents['admission']['age'] = $this->note->admission->patient_age_at_encounter.' '.$this->note->admission->patient_age_at_encounter_unit;
        if ($contents['discharge']['refer_to']) {
            $contents['admission']['refer_to'] = $contents['discharge']['refer_to'];
        }
        unset($contents['discharge']);

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

        // comorbids
        $comorbids = $contents['comorbids'];
        if ($comorbids['no_comorbids']) {
            unset($contents['comorbids']);
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

        // complications
        $complications = $contents['complications'];
        if ($complications['no_complications']) {
            unset($contents['complications']);
        } else {
            $complicationsList = $this->getConfigs()['complications'];
            $text = '';
            foreach ($complicationsList as $complication) {
                if ($complications[$complication['name']]) {
                    if ($complication['name'] === 'fever') {
                        $text .= 'Fever, ';
                    } elseif ($complication['name'] === 'desaturation') {
                        $text .= "{$complications['desaturation_specific']}, ";
                    } else {
                        $text .= "{$complication['label']}, ";
                    }
                }
            }

            $text = trim($text, ', ');
            $text .= " {$complications['other_complications']}";
            $contents['complications'] = $text;
        }

        // non_OR_procedures
        if ($contents['non_OR_procedures']['no_non_OR_procedures']) {
            unset($contents['non_OR_procedures']);
        } else {
            $contents['non_OR_procedures'] = $contents['non_OR_procedures']['non_OR_procedures_detail'];
        }

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

        // problem_list
        $problem_list = $contents['problem_list'];
        if ($problem_list['no_problem_list']) {
            unset($contents['problem_list']);
        } else {
            $text = '';
            if ($problem_list['quarantine']) {
                $text .= 'ต้องกักตัวต่อที่บ้าน ';
            }

            $text .= $problem_list['other_problem_list'];
            $contents['problem_list'] = $text;
        }

        // appointment
        $appointment = $contents['appointment'];
        if ($appointment['no_appointment']) {
            unset($contents['appointment']);
        } else {
            $text = '';
            $text .= "{$this->getDateString($appointment['date_appointment'])} ";
            $text .= $appointment['appointment_at'];
            $contents['appointment'] = $text;
        }

        // repeat_NP_swab
        $repeat_NP_swab = $contents['repeat_NP_swab'];
        if ($repeat_NP_swab['no_repeat_NP_swab']) {
            unset($contents['repeat_NP_swab']);
        } else {
            $contents['repeat_NP_swab'] = $this->getDateString($repeat_NP_swab['date_repeat_NP_swab']);
        }

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
                'discharge_status' => ['COMPLETE RECOVERY', 'IMPROVED', 'NOT IMPROVED'],
                'discharge_type' => ['WITH APPROVAL', 'AGAINST ADVICE', 'BY ESCAPE', 'BY REFER'],
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
                'note_id' => $this->note->id,
                'center' => $this->note->admission->referCase->center->name,
            ];
        }

        $configs = [
            'note_slug' => $this->note->slug,
            'admission' => [
                ['label' => 'an', 'name' => 'an'],
                ['label' => 'hn', 'name' => 'hn'],
                ['label' => 'ชื่อผู้ป่วย', 'name' => 'name'],
                ['label' => 'อายุ', 'name' => 'age'],
                ['label' => 'หอผู้ป่วย', 'name' => 'ward'],
                ['label' => 'จำนวนวันนอน', 'name' => 'length_of_stay'],
                ['label' => 'วันเวลาที่แอดมิท', 'name' => 'encountered_at'],
                ['label' => 'วันเวลาที่จำหน่าย', 'name' => 'dismissed_at'],
                ['label' => 'สถานะ', 'name' => 'discharge_status'],
                ['label' => 'ประเภท', 'name' => 'discharge_type'],
            ],
            'topics_a' => [
                ['label' => 'วินิจฉัย', 'name' => 'diagnosis'],
            ],
            'vital_signs' => [
                ['label' => 'Temp (℃)', 'name' => 'temperature_celsius'],
                ['label' => 'Pulse (min)', 'name' => 'pulse_per_minute'],
                ['label' => 'RR (min)', 'name' => 'respiration_rate_per_minute'],
                ['label' => 'SBP (mmHg)', 'name' => 'sbp'],
                ['label' => 'DBP (mmHg)', 'name' => 'dbp'],
                ['label' => 'O₂ sat (% RA)', 'name' => 'o2_sat'],
            ],
        ];

        // admission
        if ($this->note->contents['discharge']['discharge_type'] === 'BY REFER') {
            $configs['admission'][] = ['label' => 'โรงพยาบาลที่ส่งไป', 'name' => 'refer_to'];
        }
        $configs['admission'][] = ['label' => 'แพทย์เจ้าของไข้', 'name' => 'attending'];

        // topics
        if (! $this->note->contents['comorbids']['no_comorbids']) {
            $configs['topics_a'][] = ['label' => 'โรคประจำตัว', 'name' => 'comorbids'];
        }
        if (! $this->note->contents['complications']['no_complications']) {
            $configs['topics_a'][] = ['label' => 'ภาวะแทรกซ้อน', 'name' => 'complications'];
        }
        if (! $this->note->contents['non_OR_procedures']['no_non_OR_procedures']) {
            $configs['topics_a'][] = ['label' => 'Non-OR procedures', 'name' => 'non_OR_procedures'];
        }

        $configs['topics_b'][] = ['label' => 'อาการแสดงวันจำหน่าย', 'name' => 'symptoms'];
        if (! $this->note->contents['problem_list']['no_problem_list']) {
            $configs['topics_b'][] = ['label' => 'ปัญหาที่ต้องดูแลต่อเนื่อง', 'name' => 'problem_list'];
        }
        if (! $this->note->contents['appointment']['no_appointment']) {
            $configs['topics_b'][] = ['label' => 'วันนัดครั้งต่อไป', 'name' => 'appointment'];
        }
        if (! $this->note->contents['repeat_NP_swab']['no_repeat_NP_swab']) {
            $configs['topics_b'][] = ['label' => 'นัดมาทำ NP swab ซ้ำ', 'name' => 'repeat_NP_swab'];
        }

        return $configs;
    }

    public function transferData()
    {
        $referNote = $this->note->admission->notes()->whereType('refer note')->first()->contents;
        $contents = $this->note->contents;
        $contents['comorbids'] = $referNote['comorbids'];
        $contents['patient'] = $referNote['patient'];
        $this->note->contents = $contents;

        return $this->note->save();
    }

    public function checkDischarge()
    {
        if ($this->note->admission->referCase->status !== 'admitted') {
            return;
        }

        $admission = (new AdmissionManager())->manage($this->note->admission->an);
        if ($admission['found'] && $admission['admission']->dismissed_at) {
            $this->note->admission->referCase->status = 'discharged';
        }
    }

    public static function initNote()
    {
        return [
            'submitted' => false,
            'remark' => null,
            'discharge' => [
                'discharge_status' => null,
                'discharge_type' => null,
                'refer_to' => null,
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

    public static function validate(Note $note)
    {
        $rules = [
            'discharge_status' => 'required|string',
            'discharge_type' => 'required|string',
            'refer_to' => 'exclude_unless:discharge_type,BY REFER|required|string',

            'temperature_celsius' => 'required|numeric',
            'pulse_per_minute' => 'required|integer',
            'respiration_rate_per_minute' => 'required|integer',
            'sbp' => 'required|integer',
            'dbp' => 'required|integer',
            'o2_sat' => 'required|integer',

            'non_OR_procedures_detail' => 'exclude_if:no_non_OR_procedures,true|required|string',

            'date_appointment' => 'exclude_if:no_appointment,true|required|date',
            'appointment_at' => 'exclude_if:no_appointment,true|required|string',

            'date_repeat_NP_swab' => 'exclude_if:no_repeat_NP_swab,true|required|date',
        ];

        $errors = [];
        $data = Request::all();
        $validator = Validator::make($data['discharge'] + $data['vital_signs'] + $data['non_OR_procedures'] + $data['appointment'] + $data['repeat_NP_swab'], $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
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
                    ! Carbon::create($diagnosis['date_pneumonia'])->greaterThanOrEqualTo(Carbon::create($patient['date_symptom_start']))
                ) {
                    $errors['date_pneumonia'] = 'ข้อมูลไม่สอดคล้องกับ วันแรกที่มีอาการ';
                }
            }
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

        // validate complications
        $complications = $data['complications'];
        if (! $complications['no_complications']) {
            if (! ($complications['dyspnea'] ||
                $complications['chest_discomfort'] ||
                $complications['fever'] ||
                $complications['headache'] ||
                $complications['diarrhea'] ||
                $complications['desaturation'] ||
                $complications['other_complications'])
            ) {
                $errors['complications'] = 'โปรดระบุภาวะแทรกซ้อน';
            } elseif ($complications['desaturation'] && ! $complications['desaturation_specific']) {
                $errors['desaturation_specific'] = 'โปรดระบุ Desaturation';
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

        // validate problem_list
        $problem_list = $data['problem_list'];
        if (! $problem_list['no_problem_list']) {
            if (! ($problem_list['quarantine'] ||
                $problem_list['other_problem_list'])
            ) {
                $errors['problem_list'] = 'โปรดระบุปัญหา';
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }
    }
}

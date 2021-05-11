<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

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
                    'สามารถกลับมาเขียนต่อภายหลังได้',
                    'เมื่อเขียนเสร็จแล้วให้ <span class="font-semibold">เผยแพร่โน๊ต</span> ท้ายฟอร์ม',
                    'เมื่อ <span class="font-semibold">เผยแพร่โน๊ต</span> แล้วยังสามารถกลับมาแก้ไขได้จนกว่าจะสรุปแฟ้ม',
                ],
            ]);
        }

        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
            ['icon' => 'folder-open', 'label' => 'โน๊ตของเคสนี้', 'route' => 'refer-cases/'.$this->note->admission->referCase->slug.'/notes'],
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

    public function getForm()
    {
        return 'Forms/DischargeSummary';
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
            'appointment_at' => 'exclude_if:no_appointment,true|required|date',

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
                    ! Carbon::create($diagnosis['date_uri'])->greaterThanOrEqualTo(Carbon::create($patient['date_symptom_start']))
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

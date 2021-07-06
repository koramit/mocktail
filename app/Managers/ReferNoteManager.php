<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReferNoteManager extends NoteManager
{
    public function setFlashData($report = false)
    {
        // title and menu
        Request::session()->flash('page-title', 'ใบส่งตัว: '.($this->note->referCase->name).' วันที่ '.$this->getDateString($this->note->contents['patient']['date_refer']));
        if ($report) {
            Request::session()->flash('messages', null);
        } else {
            if ($this->note->contents['submitted']) {
                Request::session()->flash('messages', [
                    'status' => 'warning',
                    'messages' => ['โปรด <span class="font-semibold">ยืนยัน</span> ทุกครั้งหลังแก้ไขข้อมูล'],
                ]);
            } else {
                Request::session()->flash('messages', [
                    'status' => 'info',
                    'messages' => [
                        'สามารถกลับมาลงข้อมูลต่อภายหลังได้',
                        'เมื่อลงข้อมูลครบแล้วให้ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> ท้ายฟอร์ม',
                        'เมื่อ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> แล้วยังสามารถแก้ไขข้อมูลได้จนกว่าเคสจะแอดมิท',
                    ],
                ]);
            }
        }

        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        ]);
        Request::session()->flash('action-menu', []);
    }

    public function getContents($report = false)
    {
        $contents = $this->note->contents;
        $contents['patient']['name'] = $this->note->referCase->name;
        $contents['patient']['tel_no'] = $this->note->referCase->tel_no;
        $contents['patient']['hn'] = $this->note->referCase->patient ? $this->note->referCase->patient->hn : $this->note->referCase->hn;

        // check new keys, set them if not already set
        $this->checkNewKeys($contents);

        if (! $report) {
            return $contents;
        }

        $ward = $this->note->contents['patient']['ward'] ?? null;
        $contents['patient']['center'] = $this->note->referCase->center->name.($ward ? " ({$ward})" : '');

        $contents['author'] = [
            'name' => $this->note->author->full_name,
            'pln' =>  $this->note->author->pln,
            'tel_no' =>  $this->note->author->tel_no,
            'updated_at' => $this->note->updated_at->tz($this->user->timezone)->format('d M Y H:i:s'),
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

        // meal
        $contents['meal'] = $contents['patient']['meal'];

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

        if (! isset($contents['vital_signs']['level_of_consciousness'])) {
            $contents['vital_signs']['level_of_consciousness'] = null;
            $contents['vital_signs']['emotional_status'] = null;
        }

        if (! isset($contents['patient']['tel_no'])) {
            $contents['patient']['tel_no'] = null;
            $contents['patient']['ward'] = null;
        }

        if (! isset($contents['criterias'])) {
            $contents['criterias'] = [
                'version' => 3,
                'diagnosis' => null,
            ];
        }
    }

    public function getConfigs($report = false)
    {
        if (! $report) {
            return [
                'insurances' => ['กรมบัญชีกลาง', 'ประกันสังคม', '30 บาท', 'ชำระเงินเอง'],
                'wards' => $this->note->referCase->center->name === config('app.main_center') ?
                    ['มว ทีม 1', 'มว ทีม 2', 'มว ทีม 3', 'อฎ 11 เหนือ', 'อฎ 11 ใต้', 'อฎ 12 เหนือ', 'อฎ 12 ใต้', 'อานันทมหิดล 2', 'ARI คลินิก'] :
                    ['ARI คลินิก', 'COVID-19 ward'],
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
                'author_username' => $this->note->author->name,
                'author' => $this->note->author->full_name,
                'contact' => $this->note->author->tel_no,
                'center' => $this->note->referCase->center->name,
            ];
        }

        $configs = [
            'note_slug' => $this->note->slug,
            'patient' => [
                ['label' => 'ส่งตัวจาก', 'name' => 'center'],
                ['label' => 'sat code', 'name' => 'sat_code'],
                ['label' => 'hn', 'name' => 'hn'],
                ['label' => 'ชื่อผู้ป่วย', 'name' => 'name'],
                ['label' => 'สิทธิ์การรักษา', 'name' => 'insurance'],
                ['label' => 'วันแรกที่มีอาการ', 'name' => 'date_symptom_start', 'format' => 'date'],
                ['label' => 'วันที่ตรวจพบเชื้อ', 'name' => 'date_covid_infected', 'format' => 'date'],
                ['label' => 'วันที่รับไว้ในโรงพยาบาล', 'name' => 'date_admit_origin', 'format' => 'date'],
                ['label' => 'วันที่ส่งผู้ป่วยไป Hospitel', 'name' => 'date_refer', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดนอนใน hospitel', 'name' => 'date_expect_discharge', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน', 'name' => 'date_quarantine_end', 'format' => 'date'],
            ],
            'vital_signs' => [
                ['label' => 'Temp (℃)', 'name' => 'temperature_celsius'],
                ['label' => 'Pulse (min)', 'name' => 'pulse_per_minute'],
                ['label' => 'RR (min)', 'name' => 'respiration_rate_per_minute'],
                ['label' => 'SBP (mmHg)', 'name' => 'sbp'],
                ['label' => 'DBP (mmHg)', 'name' => 'dbp'],
                ['label' => 'O₂ sat (% RA)', 'name' => 'o2_sat'],
            ],
            'topics' => [
                ['label' => 'บันทึกอาการแสดง', 'name' => 'symptoms'],
                ['label' => 'วินิจฉัย', 'name' => 'diagnosis'],
                ['label' => 'ประวัติแพ้ยา/อาหาร ', 'name' => 'adr'],
                ['label' => 'โรคประจำตัว ', 'name' => 'comorbids'],
                ['label' => 'อาหาร', 'name' => 'meal'],
            ],
            'treatments' => [
                ['label' => 'Temperature', 'name' => 'temperature_per_day'],
                ['label' => 'Oxygen sat RA', 'name' => 'oxygen_sat_RA_per_day'],
                ['label' => 'Favipiravir (วันที่เริ่มยา)', 'name' => 'date_start_favipiravir'],
                ['label' => 'Favipiravir (กำหนดครบวันที่)', 'name' => 'date_stop_favipiravir'],
                ['label' => 'นัดมาทำ NP swab ซ้ำ วันที่', 'name' => 'date_repeat_NP_swap'],
            ],
        ];

        if (isset($this->note->contents['vital_signs']['level_of_consciousness'])) {
            $configs['vital_signs'][] = ['label' => 'Level of consciousness', 'name' => 'level_of_consciousness'];
            $configs['vital_signs'][] = ['label' => 'emotional status', 'name' => 'emotional_status'];
        }

        return $configs;
    }

    public static function initNote()
    {
        return [
            'submitted' => false,
            'no_admit' => false,
            'remark' => null,
            'patient' => [
                'sat_code' => null,
                'tel_no' => null,
                'insurance' => null,
                'ward' => null,
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
            'uploads' => [
                'film' => null,
                'lab' => null,
                'id_document' => null,
            ],
            'criterias' => [
                'version' => 3,
                'diagnosis' => null,
            ],
        ];
    }

    public static function validate(Note &$note)
    {
        if ($note->contents['no_admit']) {
            return static::validateNoAdmit($note);
        }

        $errors = [];
        $data = Request::all();
        $rules = static::getBaseRules();
        $validator = Validator::make($data['patient'] + $data['vital_signs'] + $data['treatments'] + $data['adr'], $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
        }
        static::validateCommonFields($note, $data, $errors);

        // *** validate timeline
        $patient = $data['patient'];
        // date_covid_infected MUST <= date_admit_origin
        if (! Carbon::create($patient['date_covid_infected'])->lessThanOrEqualTo(Carbon::create($patient['date_admit_origin']))) { // timeline fails
            $errors['date_covid_infected'] = 'ข้อมูลไม่สอดคล้องกับ วันที่รับไว้ในโรงพยาบาล';
        }
        // date_symptom_start MUST <= date_admit_origin
        if (! Carbon::create($patient['date_symptom_start'])->lessThanOrEqualTo(Carbon::create($patient['date_admit_origin']))) { // timeline fails
            $errors['date_symptom_start'] = 'ข้อมูลไม่สอดคล้องกับ วันที่รับไว้ในโรงพยาบาล';
        }

        // date_admit_origin MUST >= date_covid_infected
        if (! Carbon::create($patient['date_admit_origin'])->greaterThanOrEqualTo(Carbon::create($patient['date_covid_infected']))) { // timeline fails
            $errors['date_admit_origin'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ตรวจพบเชื้อ';
        }
        // date_refer MUST >= date_admit_origin (admitted at 2am refer at 8am)
        if (! Carbon::create($patient['date_refer'])->greaterThanOrEqualTo(Carbon::create($patient['date_admit_origin']))) { // timeline fails
            $errors['date_refer'] = 'ข้อมูลไม่สอดคล้องกับ วันที่รับไว้ในโรงพยาบาล';
        }
        // date_expect_discharge MUST > date_refer
        if (! Carbon::create($patient['date_expect_discharge'])->greaterThan(Carbon::create($patient['date_refer']))) { // timeline fails
            $errors['date_expect_discharge'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ส่งผู้ป่วยไป Hospitel';
        }
        // date_quarantine_end MUST >= date_expect_discharge
        if (! Carbon::create($patient['date_quarantine_end'])->greaterThanOrEqualTo(Carbon::create($patient['date_expect_discharge']))) { // timeline fails
            $errors['date_quarantine_end'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ครบกำหนดนอนใน hospitel';
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

        /* NO LONGER CHECK sat_code DUE TO its not require in most case */
        // check candidate keys
        // if (! isset($errors['sat_code'])) {
        //     $count = Note::where('contents->patient->sat_code', $patient['sat_code'])
        //                 ->where('contents->patient->date_admit_origin', $patient['date_admit_origin'])
        //                 ->whereHas('referCase', function ($query) {
        //                     $query->where('meta->status', '<>', 'canceled');
        //                 })
        //                 ->count();
        //     if ($count > 1) {
        //         $errors['sat_code'] = 'เคสซ้ำ โปรดตรวจสอบ SAT CODE และ วันที่รับไว้ในโรงพยาบาล';
        //     }
        // }

        if (count($errors) > 0) {
            return $errors;
        }
    }

    protected static function validateNoAdmit(Note &$note)
    {
        $errors = [];
        $data = Request::all();
        $rules = static::getBaseRules(true);
        $validator = Validator::make($data['patient'] + $data['vital_signs'] + $data['treatments'] + $data['adr'], $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
        }
        static::validateCommonFields($note, $data, $errors);

        // timeline => date_covid_infected : reference
        $patient = $data['patient'];
        // 'date_refer' MUST >= date_covid_infected
        if (! Carbon::create($patient['date_refer'])->greaterThanOrEqualTo(Carbon::create($patient['date_covid_infected']))) { // timeline fails
            $errors['date_refer'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ตรวจพบเชื้อ';
        }
        // 'date_expect_discharge' MUST > date_refer
        if (! Carbon::create($patient['date_expect_discharge'])->greaterThan(Carbon::create($patient['date_refer']))) { // timeline fails
            $errors['date_expect_discharge'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ส่งผู้ป่วยไป Hospitel';
        }
        // 'date_quarantine_end' MUST >= date_expect_discharge
        if (! Carbon::create($patient['date_quarantine_end'])->greaterThanOrEqualTo(Carbon::create($patient['date_expect_discharge']))) { // timeline fails
            $errors['date_quarantine_end'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ครบกำหนดนอนใน Hospitel';
        }
        // 'date_repeat_NP_swap' MUST >= date_expect_discharge
        if ($data['treatments']['date_repeat_NP_swap'] && ! Carbon::create($data['treatments']['date_repeat_NP_swap'])->greaterThanOrEqualTo(Carbon::create($patient['date_expect_discharge']))) { // timeline fails
            $errors['date_repeat_NP_swap'] = 'ข้อมูลไม่สอดคล้องกับ วันที่ครบกำหนดนอนใน Hospitel';
        }

        if (count($errors) > 0) {
            return $errors;
        }
    }

    protected static function getBaseRules($noAdmit = false)
    {
        $rules = [
            'sat_code' => 'nullable|alpha_num|size:18',
            'tel_no' => 'required|digits_between:9,10',
            'insurance' => 'required|string',
            'date_covid_infected' => 'required|date',
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

            'adr_detail' => 'exclude_if:no_adr,true|required|string',

            'ward' => 'required|string',
        ];

        if (! $noAdmit) {
            $rules = $rules + [
                'date_symptom_start' => 'required|date',
                'date_admit_origin' => 'required|date',
                'temperature_per_day' => 'required|string',
                'oxygen_sat_RA_per_day' => 'required|string',
            ];
        }

        if (Session::get('center')->id === config('app.main_center_id')) {
            $rules['hn'] = 'required|digits:8';
        }

        return $rules;
    }

    protected static function validateCommonFields($note, &$data, &$errors)
    {
        $patient = $data['patient'];

        if ($patient['hn'] || Session::get('center')->name === config('app.main_center')) {
            // validate hn
            $patientExists = (new PatientManager())->manage($patient['hn']);
            if (! $patientExists['found']) {
                return [
                    'hn' => 'ไม่พบ HN นี้ในระบบ',
                ];
            } else {
                if ($note->referCase->patient_id !== $patientExists['patient']->id) {
                    $note->referCase->patient_id = $patientExists['patient']->id;
                    $note->referCase->patient_name = $patientExists['patient']->full_name;
                    $note->referCase->save();
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

        // validate uploads
        $uploads = $data['uploads'];
        if ($note->author->center->id !== config('app.main_center_id')) {
            if (! $uploads['film']) {
                $errors['film'] = ['จำเป็นต้องแนบภาพ Film Chest ล่าสุด'];
            } elseif (! Storage::exists('uploads/'.$uploads['film'])) {
                $errors['film'] = ['กรุณาแนบไฟล์ใหม่'];
            }
            if (! ($uploads['lab'] ?? false)) {
                $errors['lab'] = ['จำเป็นต้องแนบภาพ ใบรายงานผล COVID'];
            } elseif (! Storage::exists('uploads/'.$uploads['lab'])) {
                $errors['lab'] = ['กรุณาแนบไฟล์ใหม่'];
            }
        }

        if (! $patient['hn']) {
            if (! $uploads['id_document']) {
                $errors['id_document'] = ['จำเป็นต้องแนบภาพ รูปถ่ายหน้าบัตรประชาชน'];
            } elseif (! Storage::exists('uploads/'.$uploads['id_document'])) {
                $errors['id_document'] = ['กรุณาแนบไฟล์ใหม่'];
            }
        }
    }
}

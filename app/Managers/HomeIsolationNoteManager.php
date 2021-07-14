<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HomeIsolationNoteManager extends ReferNoteManager
{
    public function getForm()
    {
        return 'Forms/HomeIsolationNote';
    }

    public function getPrintout()
    {
        return 'Printouts/HomeIsolationNote';
    }

    public function setFlashData($report = false)
    {
        parent::setFlashData($report);
        // title and menu
        Request::session()->flash('page-title', 'Home isolation: '.($this->note->referCase->name).' เริ่มวันที่ '.$this->getDateString($this->note->contents['patient']['date_refer']));
        // if ($report) {
        //     Request::session()->flash('messages', null);
        // } else {
        //     if ($this->note->contents['submitted']) {
        //         Request::session()->flash('messages', [
        //             'status' => 'warning',
        //             'messages' => ['โปรด <span class="font-semibold">ยืนยัน</span> ทุกครั้งหลังแก้ไขข้อมูล'],
        //         ]);
        //     } else {
        //         Request::session()->flash('messages', [
        //             'status' => 'info',
        //             'messages' => [
        //                 'สามารถกลับมาลงข้อมูลต่อภายหลังได้',
        //                 'เมื่อลงข้อมูลครบแล้วให้ <span class="font-semibold">ยืนยันการรับผู้ป่วย Home Isolation</span> ท้ายฟอร์ม',
        //                 'เมื่อ <span class="font-semibold">ยืนยันการรับผู้ป่วย Home Isolation</span> แล้ว <span class="italic underline">จะไม่สามารถแก้ไขข้อมูลได้อีก</span>',
        //             ],
        //         ]);
        //     }
        // }

        // Request::session()->flash('main-menu-links', [ // need check abilities
        //     ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        // ]);
        // Request::session()->flash('action-menu', []);
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
                'version' => 1,
                'ARI' => true,
            ];
        }

        if (! isset($contents['estimations'])) {
            $contents['estimations'] = [
                'temperature_celsius' => false,
                'pulse_per_minute' => false,
                'respiration_rate_per_minute' => false,
                'sbp' => false,
                'dbp' => false,
                'o2_sat' => false,
            ];
        }
    }

    public function getConfigs($report = false)
    {
        $configs = parent::getConfigs($report);
        if (! $report) {
            $configs['wards'][] = 'ศูนย์เฉพาะกิจ (ใบหยก1)';
        } else {
            $configs['patient'] = [
                ['label' => 'ส่งตัวจาก', 'name' => 'center'],
                ['label' => 'sat code', 'name' => 'sat_code'],
                ['label' => 'hn', 'name' => 'hn'],
                ['label' => 'ชื่อผู้ป่วย', 'name' => 'name'],
                ['label' => 'สิทธิ์การรักษา', 'name' => 'insurance'],
                ['label' => 'วันแรกที่มีอาการ', 'name' => 'date_symptom_start', 'format' => 'date'],
                ['label' => 'วันที่ตรวจพบเชื้อ', 'name' => 'date_covid_infected', 'format' => 'date'],
                ['label' => 'วันที่รับไว้ในโรงพยาบาล', 'name' => 'date_admit_origin', 'format' => 'date'],
                ['label' => 'วันที่เริ่มกักตัวเองที่บ้าน', 'name' => 'date_refer', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดกักตัวเองที่บ้าน', 'name' => 'date_expect_discharge', 'format' => 'date'],
            ];
        }

        return $configs;
    }

    public static function initNote()
    {
        $contents = parent::initNote();
        $contents['criterias'] = [
            'version' => 1,
        ];

        return $contents;
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
            $errors['date_expect_discharge'] = 'ข้อมูลไม่สอดคล้องกับ วันที่เริ่มกักตัวเองที่บ้าน';
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
}

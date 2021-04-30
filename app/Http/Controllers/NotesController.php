<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class NotesController extends Controller
{
    public function edit(Note $note)
    {
        // title and menu
        Request::session()->flash('page-title', 'สร้างเคสใหม่');
        Request::session()->flash('messages', [
            'status' => 'info',
            'messages' => [
                'สามารถทำการ <span class="font-semibold">บันทึกร่าง</span> เพื่อกลับมาลงข้อมูลภายหลังได้ แต่เคสจะยังไม่ถูกส่งจนกว่าจะทำการ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย<span>',
                'เมื่อ <span class="font-semibold">ยืนยันการส่งต่อผู้ป่วย</span> แล้วยังสามารถแก้ไขข้อมูลได้อยู่จนกว่าเคสจะแอดมิด',
            ],
        ]);
        Request::session()->flash('main-menu-links', [
            // ['icon' => 'patient', 'label' => 'Patients', 'route' => 'prototypes/PatientsIndex'],
            // ['icon' => 'clinic', 'label' => 'Clinics', 'route' => 'prototypes/ClinicsIndex'],
            // ['icon' => 'procedure', 'label' => 'Procedures', 'route' => 'prototypes/ProceduresIndex'],
        ]);
        Request::session()->flash('action-menu', [
            // ['icon' => 'wheelchair', 'label' => 'Add Stay case', 'action' => 'add-stay-case'],
            // ['icon' => 'ambulance', 'label' => 'Add Stay case without HN (soon... 😤)', 'action' => 'not-ready'],
            // ['icon' => 'procedure', 'label' => 'Add IPD case (later... 😅)', 'action' => 'not-ready'],
        ]);

        $configs = [
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
        ];

        $contents = $note->contents;
        $contents['patient']['name'] = $note->referCase->patient_name;

        return Inertia::render('Forms/ReferNote', [
            'contents' => $contents,
            'formConfigs' => $configs,
        ]);
    }
}

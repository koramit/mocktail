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
        Request::session()->flash('page-title', 'แก้ไขใบส่งตัว: '.($note->referCase->patient_name ?? 'ยังไม่มีชื่อ'));
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
        $contents['patient']['hn'] = $note->referCase->patient ? $note->referCase->patient->hn : null;

        return Inertia::render('Forms/ReferNote', [
            'patchEndpoint' => url('/forms/'.$note->id),
            'contents' => $contents,
            'formConfigs' => $configs,
        ]);
    }

    public function update(Note $note)
    {
        $note->forceFill(Request::all());
        $note->save();

        return back();
    }
}

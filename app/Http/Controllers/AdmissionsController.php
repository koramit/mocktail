<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AdmissionsController extends Controller
{
    public function store()
    {
        $validator = Validator::make(Request::only(['id', 'an', 'room_number']), [
            'id' => 'required|exists:refer_cases',
            'an' => 'required|digits:8',
            'room_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->add('hidden', true));
        }

        //
        $admission = (new AdmissionManager())->manage(Request::input('an'));
        if (! $admission['found']) {
            return back()->withErrors([
                'hidden' => true,
                'an' => 'ระบบขัดข้อง โปรดลองใหม่ในอีกสักครู่',
            ]);
        }
        $case = ReferCase::find(Request::input('id'));
        $meta = $case->meta;
        $meta['status'] = 'admitted';
        $meta['room_number'] = Request::input('room_number');
        $case->meta = $meta;
        $case->admission_id = $admission['admission']->id;
        if ($case->patient_id !== $admission['admission']->patient_id) {
            $case->patient_id = $admission['admission']->patient_id;
        }
        $case->save();

        return back()->with('messages', [
            'status' => 'success',
            'messages' => ['แอดมิด '.$case->name.' สำเร็จ'],
        ]);
    }
}

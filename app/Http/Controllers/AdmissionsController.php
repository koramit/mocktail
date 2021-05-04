<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AdmissionsController extends Controller
{
    public function store()
    {
        $validator = Validator::make(Request::only(['id', 'an', 'room_number']), [
            'id' => 'required|exists:refer_cases',
            'an' => 'required|digits:8',
            'room_number' => 'required|digits:3',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->add('hidden', true));
        }
        Request::validate([
            'id' => 'required|exists:refer_cases',
            'an' => 'required|digits:8',
            'room_number' => 'required|digits:3',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionNoteManager;
use App\Models\Note;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdmissionNotesController extends Controller
{
    public function __invoke(Note $note)
    {
        if ($errors = AdmissionNoteManager::validate($note)) {
            return back()->withErrors($errors);
        }

        $data = Request::all();
        unset($data['remember']);
        unset($data['patient']['hn']);
        unset($data['patient']['name']);

        $isUpdate = true;
        if (! $data['submitted']) {
            $data['submitted'] = true;
            $isUpdate = false;
            $note->admission->referCase->touch();
        }

        $note->contents = $data;
        $note->save();

        return Redirect::route('case-notes', ['case' => $note->admission->referCase])->with('messages', [
            'status' => 'success',
            'messages' => [
                ($isUpdate ? 'ปรับปรุง' : 'เผยแพร่').' admission note '.$note->admission->referCase->name.' สำเร็จ',
            ],
        ]);
    }
}

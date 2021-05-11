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
        $errors = AdmissionNoteManager::validate($note);

        if ($errors) {
            return back()->withErrors($errors);
        }

        $data = Request::all();

        unset($data['remember']);
        unset($data['patient']['hn']);
        unset($data['patient']['name']);

        $data['submitted'] = true;
        $note->contents = $data;
        $note->save();

        return Redirect::route('case-notes', ['case' => $note->admission->referCase])->with('messages', [
            'status' => 'success',
            'messages' => [
                'เผยแพร่ admission note '.$note->admission->referCase->name.' สำเร็จ',
            ],
        ]);

        // return 'OK🥳';
    }
}

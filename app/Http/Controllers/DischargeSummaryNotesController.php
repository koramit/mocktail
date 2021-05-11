<?php

namespace App\Http\Controllers;

use App\Managers\DischargeSummaryManager;
use App\Models\Note;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class DischargeSummaryNotesController extends Controller
{
    public function __invoke(Note $note)
    {
        $errors = DischargeSummaryManager::validate($note);

        if ($errors) {
            return back()->withErrors($errors);
        }

        // return 'OK🥳';

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
    }
}

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
        if ($errors = DischargeSummaryManager::validate($note)) {
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
                ($isUpdate ? 'ปรับปรุง' : 'เผยแพร่').' Discharge summary '.$note->admission->referCase->name.' สำเร็จ',
            ],
        ]);
    }
}

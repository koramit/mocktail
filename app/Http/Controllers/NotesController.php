<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionNoteManager;
use App\Managers\DischargeSummaryManager;
use App\Managers\ReferNoteManager;
use App\Models\Note;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NotesController extends Controller
{
    public function store()
    {
        Request::validate([
            'refer_case_id' => 'required|exists:refer_cases,id',
            'type' => 'required|in:admission note,discharge summary,progress note,nurse note',
        ]);

        $type = Request::input('type');
        $case = ReferCase::find(Request::input('refer_case_id'));

        // checks duplicate admission note and discharge summary
        if (collect(['admission note', 'discharge summary']).contains($type)) {
            $note = $case->admission->notes()->whereType($type);
            if ($note) {
                return '🤮 ลำใย';
            }
        }

        if ($type === 'admission note') {
            $manager = new AdmissionNoteManager();
        } elseif ($type === 'discharge summary') {
            $manager = new DischargeSummaryManager();
        }

        $note = new Note();
        $note->slug = Str::uuid()->toString();
        $note->type = Request::input('type');
        $note->contents = $manager->initNote();
        $note->user_id = Auth::id();
        $note->admission_id = $case->admission->id;
        $note->save();

        return 'OK🤗';
    }

    public function show(Note $note)
    {
        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager($note);
        }

        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager($note);
        }
        $manager->setFlashData(true);

        return Inertia::render('Reports/ReferNote', [
            'contents' => $manager->getContents(),
            'formConfigs' => $manager->getConfigs(),
        ]);
    }

    public function edit(Note $note)
    {
        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager($note);
        }
        $manager->setFlashData();

        return Inertia::render('Forms/ReferNote', [
            'contents' => $manager->getContents(),
            'formConfigs' => $manager->getConfigs(),
        ]);
    }

    public function update(Note $note)
    {
        $data = Request::all();
        if (array_key_first($data) === 'contents->patient->hn') {
            $note->referCase->update(['hn' => $data['contents->patient->hn']]);

            return 'ok';
        } elseif (array_key_first($data) === 'contents->patient->name') {
            $note->referCase->update(['patient_name' => $data['contents->patient->name']]);

            return 'ok';
        }
        $note->forceFill($data);
        $note->save();

        return 'ok';
    }
}

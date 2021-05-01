<?php

namespace App\Http\Controllers;

use App\Managers\ReferNoteManager;
use App\Models\Note;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class NotesController extends Controller
{
    public function edit(Note $note)
    {
        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager();
        }
        $manager->setFlashData($note);

        return Inertia::render('Forms/ReferNote', [
            'patchEndpoint' => url('/forms/'.$note->id),
            'contents' => $manager->getContents($note),
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

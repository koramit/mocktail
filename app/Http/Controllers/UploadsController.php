<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    public function store()
    {
        $path = Request::file('file')->store('uploads');

        $note = Note::find(Request::input('note_id'));
        $name = Request::input('name');
        $key = collect(explode('->', $name))->last();
        $oldFile = collect([$note->contents])->pluck('uploads.'.$key)->first();
        $note->forceFill([$name => basename($path)]);
        $note->save();

        if (Storage::exists('uploads/'.$oldFile)) {
            Storage::delete('uploads/'.$oldFile);
        }

        // this is NOT A ERROR, but its only way (that I know) to make inertia accept response data when using visit api
        return back()->withErrors([
            'filename' => basename($path),
            'hidden' => true,
        ]);
    }

    public function show($path)
    {
        if (Storage::exists('uploads/'.$path)) {
            return Storage::response('uploads/'.$path);
        }
    }
}

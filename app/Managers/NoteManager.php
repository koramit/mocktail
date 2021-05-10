<?php

namespace App\Managers;

use App\Models\Note;

class NoteManager
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }
}

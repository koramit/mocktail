<?php

namespace App\Managers;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NoteManager
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function getDateString($date)
    {
        if (! $date) {
            return null;
        }

        return Carbon::create($date)->format('d M Y');
    }

    public function getForm()
    {
        return 'Forms/'.ucwords(Str::camel($this->note->type));
    }

    public function getPrintout()
    {
        return 'Printouts/'.ucwords(Str::camel($this->note->type));
    }
}

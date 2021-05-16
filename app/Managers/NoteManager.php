<?php

namespace App\Managers;

use App\Models\Note;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoteManager
{
    protected $note;
    protected $user;

    public function __construct(Note $note, User $user = null)
    {
        $this->note = $note;
        $this->user = $user ?? Auth::user();
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

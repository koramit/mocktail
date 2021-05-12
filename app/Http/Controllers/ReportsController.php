<?php

namespace App\Http\Controllers;

use App\Managers\ReferNoteManager;
use App\Models\ReferCase;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function __invoke(ReferCase $case)
    {
        $referCase = [
            'hn' => $case->hn,
            'patient_name' => $case->name,
            'an' => $case->an,
            'caneter' => $case->center->name,
        ];

        $notes = [];
        // refer note
        // $manager = new ReferNoteManager($case->note);
        $notes['refer_note'] = $case->note->contents;

        // admission note

        // discharge summary

        return Inertia::render('Reports/Folder', [
            'referCase' => $referCase,
            'notes' => $notes,
        ]);
    }
}

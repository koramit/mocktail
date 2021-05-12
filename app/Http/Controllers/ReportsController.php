<?php

namespace App\Http\Controllers;

use App\Managers\ReferNoteManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function __invoke(ReferCase $case)
    {
        Request::session()->flash('page-title', 'เวชระเบียน '.($case->an ? ' AN'.$case->an : '').' '.$case->name);
        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        ]);
        $referCase = [
            'hn' => $case->hn,
            'patient_name' => $case->name,
            'an' => $case->an,
            'center' => $case->center->name,
        ];

        $notes = [];
        // refer note
        // $manager = new ReferNoteManager($case->note);
        $notes['refer_note'] = (new ReferNoteManager($case->note))->getContents(report: true);

        // admission note

        // discharge summary

        return Inertia::render('Reports/Folder', [
            'referCase' => $referCase,
            'notes' => $notes,
        ]);
    }
}

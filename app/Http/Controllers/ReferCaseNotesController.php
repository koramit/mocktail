<?php

namespace App\Http\Controllers;

use App\Models\ReferCase;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ReferCaseNotesController extends Controller
{
    public function __invoke(ReferCase $case)
    {
        Request::session()->flash('page-title', 'AN: '.$case->admission->an.' ๏ '.$case->patient_name);
        Request::session()->flash('main-menu-links', [ // need check abilities
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        ]);

        return Inertia::render('ReferCases/Notes', [
            'referCase' => [
                'id' => $case->id,
                'slug' => $case->slug,
                'patient_name' => $case->patient_name,
                'an' => $case->admission->an,
                'type' => $case->meta['type'],
            ],
            'referer' => [
                'name' => $case->referer->profile['full_name'],
                'tel_no' => $case->referer->profile['tel_no'],
            ],
            'notes' => $case->admission->notes->transform(function ($note) {
                return [
                    'slug' => $note->slug,
                    'type' => $note->type,
                    'author_id' => $note->user_id,
                    'created_at' => $note->created_at->format('Y-d-m H:i:s'),
                ];
            }),
        ]);
    }
}

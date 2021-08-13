<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionNoteManager;
use App\Managers\DischargeSummaryManager;
use App\Managers\HomeIsolationNoteManager;
use App\Managers\ReferNoteManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function __invoke(ReferCase $case)
    {
        $referCase = [
            'slug' => $case->slug,
            'hn' => $case->hn,
            'patient_name' => $case->name,
            'an' => $case->an,
            'center' => $case->center->name,
        ];

        $mainCenterUser = Auth::user()->center->id === config('app.main_center_id');

        Request::session()->flash('page-title', 'เวชระเบียน '.($case->an ? ' AN'.$case->an : '').' '.$case->name);

        if (! $mainCenterUser || ! $referCase['an']) {
            Request::session()->flash('main-menu-links', [ // need check abilities
                ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
            ]);
        } else {
            Request::session()->flash('main-menu-links', [ // need check abilities
                ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
                ['icon' => 'slack-hash', 'label' => 'ใบส่งตัว', 'route' => '#refer-note'],
                ['icon' => 'slack-hash', 'label' => 'Admission Note', 'route' => '#admission-note'],
                ['icon' => 'slack-hash', 'label' => 'Discharge Summary', 'route' => '#discharge-summary'],
            ]);
        }

        $notes = [];

        // refer note
        /*
         * There is a bug that can't be reproduce yet
         * note status was set to false after
         * case was submitted so, just
         * garantee note can print
         */
        if (! collect(['draft', 'canceled'])->contains($case->status) && ! $case->note->contents['submitted']) {
            $case->note->forceFill(['contents->submitted' => true])->save();
            unset($case->note); // force reload
        }

        $manager = ($case->meta['type'] ?? 'Hospitel') === 'Hospitel' ? new ReferNoteManager($case->note) : new HomeIsolationNoteManager($case->note);
        $notes['refer_note'] = [
            'contents' => $manager->getContents(true),
            'configs' => $manager->getConfigs(true),
        ];

        if (! $mainCenterUser || ! $referCase['an']) {
            return Inertia::render('Reports/Folder', [
                'referCase' => $referCase,
                'notes' => $notes,
            ]);
        }

        // admission note
        $note = $case->admission ? $case->admission->notes()->whereType('admission note')->first() : null;
        if ($note) {
            $manager = new AdmissionNoteManager($note);
            $notes['admission_note'] = [
                'contents' => $manager->getContents(true),
                'configs' => $manager->getConfigs(true),
            ];
        }

        // discharge summary
        $note = $case->admission ? $case->admission->notes()->whereType('discharge summary')->first() : null;
        if ($note) {
            $manager = new DischargeSummaryManager($note);
            $notes['discharge_summary'] = [
                'contents' => $manager->getContents(true),
                'configs' => $manager->getConfigs(true),
            ];
        }

        return Inertia::render('Reports/Folder', [
            'referCase' => $referCase,
            'notes' => $notes,
        ]);
    }
}

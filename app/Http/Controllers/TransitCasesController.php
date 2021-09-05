<?php

namespace App\Http\Controllers;

use App\Events\CaseUpdated;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TransitCasesController extends Controller
{
    public function index()
    {
        Request::session()->flash('page-title', 'เคสจาก ARI คลินิก ศิริราช');
        Request::session()->flash('main-menu-links', [
            ['icon' => 'clipboard-list', 'label' => 'รายการเคส', 'route' => 'refer-cases'],
        ]);

        $cases = ReferCase::with(['patient', 'referer', 'note'])
                          ->where('meta->status', 'transit')
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'ward' => $case->meta['ward'],
                                  'slug' => $case->slug,
                                  'patient_name' => $case->name,
                                  'hn' => $case->hn,
                                  'date_refer' => $case->note->contents['patient']['date_refer'],
                                  'referer' => $case->referer->name,
                                  'updated_at_for_humans' => $case->updated_at_for_humans,
                            ];
                          });

        return Inertia::render('ReferCases/Transit', ['cases' => $cases]);
    }

    public function update(ReferCase $case)
    {
        $case->user_id = Auth::id();
        $case->status = 'draft';
        $case->save();
        $case->note->user_id = $case->user_id;
        $case->note->save();
        CaseUpdated::dispatch($case);

        return Redirect::route('note.form', $case->note);
    }
}

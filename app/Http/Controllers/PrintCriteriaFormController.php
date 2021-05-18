<?php

namespace App\Http\Controllers;

use App\Models\ReferCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PrintCriteriaFormController extends Controller
{
    public function __invoke(ReferCase $case)
    {
        $contents = [];
        $contents['criterias'] = $case->note->contents['criterias'];
        $contents['criterias']['patient_label'] = 'HN '.$case->admission->patient->hn.' '.$case->admission->patient->full_name.' วันที่ส่งตัว '.Carbon::create($case->note->contents['patient']['date_refer'])->format('d M Y');
        $contents['author']['name'] = $case->note->author->full_name;
        $contents['author']['pln'] = $case->note->author->pln;
        $contents['author']['tel_no'] = $case->note->author->tel_no;
        $contents['author']['updated_at'] = $case->note->updated_at->tz(Auth::user()->timezone)->format('d M Y H:i:s');

        Request::session()->flash('page-title', 'Admission note: AN '.$case->admission->an.' '.($case->admission->patient->full_name));

        return Inertia::render('Printouts/Criteria', ['contents' => $contents]);
    }
}

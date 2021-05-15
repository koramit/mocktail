<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionNoteManager;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PrintDefaultAdmissionNote extends Controller
{
    public function __invoke(ReferCase $case)
    {
        $manager = new AdmissionNoteManager($case->note);

        $contents = $manager->getContents(true);
        $contents['author']['name'] = 'รศ.นพ. ธวัชชัย ทวีมั่นคงทรัพย์';
        $contents['author']['pln'] = '16729';
        $contents['author']['tel_no'] = null;
        $contents['author']['updated_at'] = $case->note->admission->encountered_at->tz(Auth::user()->timezone)->format('d M Y H:i:s');
        $configs = $manager->getConfigs(true);

        Request::session()->flash('page-title', 'Admission Note: AN '.$case->admission->an.' '.($case->admission->patient->full_name));

        return Inertia::render('Printouts/AdmissionNote', [
            'contents' => $contents,
            'configs' => $configs,
        ]);
    }
}

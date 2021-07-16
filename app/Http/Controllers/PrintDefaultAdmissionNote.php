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

        $contents['author']['tel_no'] = null;
        $contents['author']['updated_at'] = $case->note->admission->encountered_at->tz(Auth::user()->timezone)->format('d M Y H:i:s');

        $configs = $manager->getConfigs(true);
        $configs['type'] = $case->meta['type'];

        if ($case->meta['type'] === 'Hospitel') {
            $contents['author']['name'] = 'รศ.นพ. ธวัชชัย ทวีมั่นคงทรัพย์';
            $contents['author']['pln'] = '16729';
        } else {
            $contents['author']['name'] = 'รศ.นพ. กรภัทร มยุระสาคร';
            $contents['author']['pln'] = '26447';

            $configs['patient'] = [
                ['label' => 'วันแรกที่มีอาการ', 'name' => 'date_symptom_start', 'format' => 'date'],
                ['label' => 'วันที่ตรวจพบเชื้อ', 'name' => 'date_covid_infected', 'format' => 'date'],
                ['label' => 'วันที่รับไว้ในโรงพยาบาล', 'name' => 'date_admit_origin', 'format' => 'date'],
                ['label' => 'วันที่เริ่มกักตัวเองที่บ้าน', 'name' => 'date_refer', 'format' => 'date'],
                ['label' => 'วันที่ครบกำหนดกักตัวเองที่บ้าน', 'name' => 'date_expect_discharge', 'format' => 'date'],
            ];
        }

        Request::session()->flash('page-title', 'Admission Note: AN '.$case->admission->an.' '.($case->admission->patient->full_name));

        return Inertia::render('Printouts/AdmissionNote', [
            'contents' => $contents,
            'configs' => $configs,
        ]);
    }
}

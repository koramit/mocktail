<?php

namespace App\Http\Controllers;

use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent as Agent;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ExportAdmitOrderController extends Controller
{
    public function __invoke()
    {
        ini_set('memory_limit', '256M');

        $cases = ReferCase::with(['patient', 'center', 'note', 'admission'])
                          ->filter(['status' => 'discharged'], Session::get('center')->id)
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'ชื่อ-สกุล' => $case->name,
                                  'HN' => $case->hn,
                                  'อายุ' => $case->admission->patient_age_at_encounter,
                                  'วันที่ Admit' => $case->admission ? $case->admission->encountered_at->tz('asia/bangkok')->format('d-M-Y') : null,
                                  'สิทธิ์การรักษา' => $case->note->contents['patient']['insurance'],
                                  'date_admit_th' => $this->getDateThai($case->admission->encountered_at->tz('asia/bangkok')),
                                  'ward' => $case->meta['ward'],
                                  'an' => $case->admission->an,
                              ];
                          });
        $filename = 'ข้อมูลทำคำสั่ง Admit@'.now()->tz(Auth::user()->timezone)->format('d-m-Y').'.xlsx';
        $agent = new Agent();
        $sorted = $cases->filter(fn ($c) => $c->age >= 17)->sortBy([
            fn ($a, $b) => $a['ward'] <=> $b['ward'],
            fn ($a, $b) => $b['an'] <=> $a['an'],
        ]);
        if ($agent->isSafari()) {
            $path = FastExcel::data($sorted)->export(storage_path('app/temp/safari-excel-'.Session::get('center')->id.'.xlsx'));

            return response()->download($path, $filename);
        }

        return FastExcel::data($sorted)->download($filename);
    }

    protected function getDateThai($dateRef)
    {
        $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        return $dateRef->day.' '.$thaiMonths[$dateRef->month].' '.($dateRef->year + 543);
    }
}

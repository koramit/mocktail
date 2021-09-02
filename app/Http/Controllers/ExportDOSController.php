<?php

namespace App\Http\Controllers;

use App\Models\ReferCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ExportDOSController extends Controller
{
    public function __invoke()
    {
        ini_set('memory_limit', '256M');

        $cases = ReferCase::with(['patient', 'center', 'note', 'admission'])
                          ->filter(['status' => 'discharged', 'type' => 'Home Isolation'], Session::get('center')->id)
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'ชื่อ-สกุล' => $case->name,
                                  'HN' => $case->hn,
                                  'AN' => $case->admission ? $case->admission->an : null,
                                  'วันที่แพลน Admit' => $this->castDate($case->note->contents['patient']['date_refer']),
                                  'วันที่แพลน Discharge' => $this->castDate($case->note->contents['patient']['date_expect_discharge']),
                                  'วันที่ Admit' => $case->admission ? $case->admission->encountered_at->tz('asia/bangkok')->format('d-M-Y') : null,
                                  'วันที่ Discharge' => ($case->admission && $case->admission->dismissed_at) ? $case->admission->dismissed_at->tz('asia/bangkok')->format('d-M-Y') : null,
                                  'วันนอน' => $case->admission ? $case->admission->length_of_stay : null,
                                  'การให้ยา favipiravir' => $case->note->contents['treatments']['favipiravir'] ? 'มี' : 'ไม่มี',
                                  'วันที่เริ่มยา favipiravir' => $case->note->contents['treatments']['date_start_favipiravir'],
                                  'วันครบกำหนดยา favipiravir' => $case->note->contents['treatments']['date_stop_favipiravir'],
                                  'เพิ่มเติม' => $case->note->contents['remark'] ? str_replace("\n", ' ', $case->note->contents['remark']) : null,
                                  'แพทย์ผู้ทำใบส่งตัว' => $case->meta['referer'],
                              ];
                          });
        $filename = 'ข้อมูลทำ DOS สำหรับเคส HI@'.now()->tz(Auth::user()->timezone)->format('d-m-Y').'.xlsx';
        $agent = new Agent();
        if ($agent->isSafari()) {
            $path = FastExcel::data($cases)->export(storage_path('app/temp/safari-excel-'.Session::get('center')->id.'.xlsx'));

            return response()->download($path, $filename);
        }

        return FastExcel::data($cases)->download($filename);
    }

    protected function castDate($dateStr)
    {
        if (! $dateStr) {
            return null;
        }

        return Carbon::create($dateStr)->format('d-M-Y');
    }
}

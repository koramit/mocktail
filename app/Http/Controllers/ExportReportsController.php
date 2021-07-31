<?php

namespace App\Http\Controllers;

use App\Models\ReferCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ExportReportsController extends Controller
{
    public function __invoke()
    {
        $cases = ReferCase::with(['patient', 'center', 'note', 'admission'])
                          ->withFilterUserCenter(Session::get('center')->id)
                          ->filter(Request::only('status', 'center', 'type', 'search'), Session::get('center')->id)
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'HN' => $case->hn,
                                  'ชื่อ-สกุล' => $case->name,
                                  'วันที่แพลน Admit' => $this->castDate($case->note->contents['patient']['date_refer']),
                                  'วันที่แพลน Discharge' => $this->castDate($case->note->contents['patient']['date_expect_discharge']),
                                  'รพ. ต้นทาง' => $case->center->name,
                                  'สถานะ' => $case->status_label,
                                  'หมายเลขห้อง' => $case->room_number,
                                  'ประเภท' => $case->meta['type'] ?? 'Hospitel',
                                  'ward' => $case->meta['ward'],
                                  'AN' => $case->admission ? $case->admission->an : null,
                                  'วันที่ Admit' => $case->admission ? $case->admission->encountered_at->tz('asia/bangkok')->format('d-M-Y') : null,
                                  'วันที่ Discharge' => ($case->admission && $case->admission->dismissed_at) ? $case->admission->dismissed_at->tz('asia/bangkok')->format('d-M-Y') : null,
                                  'วันนอน' => $case->admission ? $case->admission->length_of_stay : null,
                              ];
                          });
        $filename = 'รายงานแบบบันทึกการส่งต่อผู้ป่วย@'.now()->tz(Auth::user()->timezone)->format('d-m-Y').'.xlsx';
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

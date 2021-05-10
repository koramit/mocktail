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
        $cases = ReferCase::with(['patient', 'center', 'note'])
                          ->withFilterUserCenter(Session::get('center')->id)
                          ->filter(Request::only('status', 'center'))
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'HN' => $case->patient ? $case->patient->hn : null,
                                  'ชื่อ-สกุล' => $case->patient ? $case->patient->full_name : $case->patient_name,
                                  'วันที่ Admit' => $this->castDate($case->note->contents['patient']['date_refer']),
                                  'วันที่ Discharge' => $this->castDate($case->note->contents['patient']['date_expect_discharge']),
                                  'รพ. ต้นทาง' => $case->center->name,
                                  'สถานะ' => $case->status_label,
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

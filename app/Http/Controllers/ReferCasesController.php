<?php

namespace App\Http\Controllers;

use App\Managers\PatientManager;
use App\Managers\ReferNoteManager;
use App\Models\Note;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReferCasesController extends Controller
{
    public function index()
    {
        Request::session()->flash('page-title', 'รายการเคส'.(Session::get('center')->name === config('app.main_center') ? '' : (' '.Session::get('center')->name)));
        Request::session()->flash('main-menu-links', []);
        Request::session()->flash('action-menu', Auth::user()->abilities->contains('refer_case') ? [
            ['icon' => 'ambulance', 'label' => 'เพิ่มเคสใหม่', 'action' => 'create-new-case'],
        ] : []);

        $cases = ReferCase::with(['patient', 'referer', 'center', 'note'])
                          ->withFilterUserCenter(Session::get('center')->id)
                          ->filter(Request::only('status', 'center'))
                          ->orderBy('updated_at', 'desc')
                          ->orderBy('id')
                          ->paginate()
                          ->withQueryString()
                          ->through(function ($case) { // transform() will "transform" paginate->data and paginate->link
                              return [
                                  'id' => $case->id,
                                  'slug' => $case->slug,
                                  'note_slug' => $case->note->slug,
                                  'referer' => $case->referer->name,
                                  'patient_name' => $case->name,
                                  'hn' => $case->hn,
                                  'center' => $case->center->name,
                                  'status' => $case->status,
                                  'status_label' => $case->status_label,
                                  'room_number' => $case->room_number,
                                  'referer' => $case->referer->name,
                                  'updated_at_for_humans' => $case->updated_at_for_humans,
                              ];
                          });

        Request::session()->flash('messages', Request::session()->has('messages') ?
                Request::session()->pull('messages') :
                ($cases->count() > 0 ? null : ['status' => 'info', 'messages' => ['ยังไม่มีข้อมูลเคส หรือ ไม่มีข้อมูลเคสตามตัวกรอง']])
            );

        return Inertia::render('ReferCases/Index', [
            'cases' => $cases,
            'filters' => Request::all('status', 'center'),
        ]);
    }

    public function store()
    {
        $errors = [];
        $hn = Request::input('hn', null);
        if (Session::get('center')->name === config('app.main_center')) {
            // Main center needs hn
            if (! $hn) {
                $errors['hn'] = 'จำเป็นต้องลง HN';
            }
        } else {
            // Other center needs hn or name
            if (! $hn && ! Request::input('patient_name', null)) {
                $errors['patient_name'] = 'จำเป็นต้องลง ชื่อผู้ป่วย';
            }
        }

        if ($hn) { // validate hn
            if (! is_numeric($hn) || strlen($hn) !== 8) {
                $errors['hn'] = 'ไม่พบ HN นี้ในระบบ';
            } else {
                $result = (new PatientManager())->manage($hn);
                if (! $result['found']) {
                    $errors['hn'] = isset($result['message']) ? $result['message'] : 'ไม่พบ HN นี้ในระบบ';
                }
            }
        }

        if (count($errors) > 0) {
            return $errors + ['errors' => true];
        }

        if ($hn && ! Request::input('confirmed', false)) {
            return [
                'errors' => true,
                'confirmed' => (new PatientManager())->manage(Request::input('hn'))['patient']->full_name,
            ];
        }

        $user = Auth::user();
        $contents = ReferNoteManager::initNote();

        $note = new Note();
        $note->slug = Str::uuid()->toString();
        $note->user_id = $user->id;
        $note->contents = $contents;
        $note->type = 'refer note';
        $note->save();

        $case = [
            'slug' => Str::uuid()->toString(),
            'note_id' => $note->id,
            'meta' => [
                'status' => 'draft',
            ],
        ];

        if (Request::input('hn')) {
            $patient = (new PatientManager())->manage(Request::input('hn'))['patient']; // need handle error
            $case['patient_id'] = $patient->id;
            $case['patient_name'] = $patient->full_name;
        } else {
            $case['patient_name'] = Request::input('patient_name');
        }

        $case = $user->referCases()->create($case);

        return [
            'url' => 'forms/'.$note->slug.'/edit',
        ];
    }

    public function update(Note $note)
    {
        if ($errors = ReferNoteManager::validate($note)) {
            return back()->withErrors($errors);
        }

        $data = Request::all();
        // criteria V1/V2 is OK for this condition
        if (! $data['criterias']['diagnosis'] && ! $note->contents['submitted']) {
            return back();
        }

        $hn = $data['patient']['hn'];
        if (! $note->referCase->updateHn($hn)) {
            return back()->withErrors(['hn' => 'ไม่พบ HN นี้ในระบบ']);
        }

        if ($note->referCase->status === 'draft') {
            $note->referCase->status = 'submitted';
            $data['submitted'] = true;
        }

        $note->referCase->tel_no = $data['patient']['tel_no'];

        unset($data['remember']);
        unset($data['patient']['hn']);
        unset($data['patient']['name']);
        unset($data['patient']['tel_no']);

        $note->contents = $data;
        $note->save();

        return Redirect::route('refer-cases')->with('messages', [
            'status' => 'success',
            'messages' => [
                'ยืนยันการส่งต่อ '.$note->referCase->name.' สำเร็จ',
            ],
        ]);
    }

    public function destroy(ReferCase $case)
    {
        // NEED perform policy check here
        $case->cancel(Auth::user()->name, Request::input('reason'));

        return back();
    }
}

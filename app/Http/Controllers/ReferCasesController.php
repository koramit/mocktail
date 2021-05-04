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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReferCasesController extends Controller
{
    public function index()
    {
        Request::session()->flash('page-title', 'รายการเคส'.(Session::get('center')->name === config('app.main_center') ? '' : (' '.Session::get('center')->name)));
        Request::session()->flash('messages', null);
        Request::session()->flash('main-menu-links', []);
        Request::session()->flash('action-menu', [
            ['icon' => 'ambulance', 'label' => 'เพิ่มเคสใหม่', 'action' => 'create-new-case'],
        ]);

        $cases = ReferCase::with(['patient', 'referer', 'center', 'note'])
                          ->withFilterUserCenter(Session::get('center')->id)
                          ->get()
                          ->transform(function ($case) {
                              return [
                                  'id' => $case->id,
                                  'note_slug' => $case->note->slug,
                                  'referer' => $case->referer->name,
                                  'patient_name' => ($case->patient ? $case->patient->full_name : $case->patient_name) ?? 'ยังไม่มีข้อมูลชื่อ',
                                  'hn' => $case->patient ? $case->patient->hn : null,
                                  'center' => $case->center->name,
                                  'status' => $case->status,
                                  'referer' => $case->referer->name,
                                  'updated_at_for_humans' => $case->updated_at_for_humans,
                              ];
                          });

        return Inertia::render('Cases', ['cases' => $cases]);
    }

    public function store()
    {
        $validator = Validator::make(Request::all(), [
            'sat_code' => 'required|alpha_num|size:18',
            'date_admit_origin' => 'required|date',
            'hn' => [function ($attribute, $value, $fail) {
                if ($value) {
                    if (! is_numeric($value) || strlen($value) !== 8) {
                        $fail('ไม่พบ HN นี้ในระบบ');
                    }

                    $result = (new PatientManager())->manage($value);
                    if (! $result['found']) {
                        $fail('ไม่พบ HN นี้ในระบบ');
                    }
                } else {
                    if (Session::get('center')->name === config('app.main_center')) {
                        $fail('จำเป็นต้องลง HN');
                    }
                }
            }],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->add('hidden', true));
        }

        if (Request::input('hn') && ! Request::input('confirmed', false)) {
            return back()->withErrors([
                'confirmed' => (new PatientManager())->manage(Request::input('hn'))['patient']->full_name,
                'hidden' => true,
            ]);
        }

        $user = Auth::user();
        $contents = ReferNoteManager::initNote();
        $contents['patient']['sat_code'] = Request::input('sat_code');
        $contents['patient']['date_admit_origin'] = Request::input('date_admit_origin');

        $note = new Note();
        $note->slug = Str::uuid()->toString();
        $note->user_id = $user->id;
        $note->contents = $contents;
        $note->type = 'refer note';
        $note->save();

        $case = [
            'slug' => Str::uuid()->toString(),
            'note_id' => $note->id,
        ];

        if (Request::input('hn')) {
            $patient = (new PatientManager())->manage(Request::input('hn'))['patient']; // need handle error
            $case['patient_id'] = $patient->id;
            $case['patient_name'] = $patient->full_name;
        }

        $case = $user->referCases()->create($case);

        return Redirect::to(url('forms/'.$note->slug.'/edit'));
    }

    public function update(Note $note)
    {
        $errors = ReferNoteManager::validate($note);

        if ($errors) {
            return back()->withErrors($errors);
        }

        return 'OK 😇';
    }
}

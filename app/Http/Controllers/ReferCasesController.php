<?php

namespace App\Http\Controllers;

use App\Managers\PatientManager;
use App\Managers\ReferNoteManager;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReferCasesController extends Controller
{
    public function index()
    {
        Request::session()->flash('page-title', 'รายการเคส');

        return Inertia::render('Cases');
    }

    public function store()
    {
        Request::validate([
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
                }
            }],
        ]);

        if (Request::input('hn') && ! Request::input('confirmed', false)) {
            return back()->withErrors([
                'confirmed' => (new PatientManager())->manage(Request::input('hn'))['patient']->full_name,
            ]);
        }

        $user = Auth::user();
        $contents = (new ReferNoteManager())->initNote();
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
}

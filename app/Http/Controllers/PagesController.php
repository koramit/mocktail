<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PagesController extends Controller
{
    public function home()
    {
        // title and menu
        Request::session()->flash('page-title', 'หน้าหลัก');
        Request::session()->flash('main-menu-links', [
            // ['icon' => 'patient', 'label' => 'Patients', 'route' => 'prototypes/PatientsIndex'],
            // ['icon' => 'clinic', 'label' => 'Clinics', 'route' => 'prototypes/ClinicsIndex'],
            // ['icon' => 'procedure', 'label' => 'Procedures', 'route' => 'prototypes/ProceduresIndex'],
        ]);
        Request::session()->flash('action-menu', [
            // ['icon' => 'wheelchair', 'label' => 'Add Stay case', 'action' => 'add-stay-case'],
            // ['icon' => 'ambulance', 'label' => 'Add Stay case without HN (soon... 😤)', 'action' => 'not-ready'],
            // ['icon' => 'procedure', 'label' => 'Add IPD case (later... 😅)', 'action' => 'not-ready'],
        ]);

        return Inertia::render('Home');
    }
}

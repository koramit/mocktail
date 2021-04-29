<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class UserPreferencesController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Preferences');
    }
}

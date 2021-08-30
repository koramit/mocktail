<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class ReferCasesController extends Controller
{
    public function store()
    {
        Request::validate([

        ]);

        return Request::all();
    }
}

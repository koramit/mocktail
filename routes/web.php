<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia\Inertia::render('Welcome');
});

Route::get('/prototypes/{page}', function ($page) {
    return Inertia\Inertia::render('Prototypes/'.$page);
});
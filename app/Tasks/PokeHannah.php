<?php

namespace App\Tasks;

class PokeHannah
{
    public function __invoke()
    {
        $api = app()->make('App\Contracts\AuthenticationAPI');

        $api->authenticate('siriraj.sir', 'P@ssw0rd');
    }
}

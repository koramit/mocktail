<?php

namespace App\Http\Controllers;

use App\Managers\ReferNoteManager;
use App\Models\Note;
use Inertia\Inertia;

class PrintoutsController extends Controller
{
    public function __invoke(Note $note)
    {
        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager($note);
        }

        $manager->setFlashData(true);

        return Inertia::render($manager->getPrintput(), [
            'contents' => $manager->getContents(true),
            'configs' => $manager->getConfigs(true),
        ]);
    }
}

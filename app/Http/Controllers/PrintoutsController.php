<?php

namespace App\Http\Controllers;

use App\Managers\AdmissionNoteManager;
use App\Managers\DischargeSummaryManager;
use App\Managers\ReferNoteManager;
use App\Models\Note;
use Inertia\Inertia;

class PrintoutsController extends Controller
{
    public function __invoke(Note $note)
    {
        if ($note->type === 'refer note') {
            $manager = new ReferNoteManager($note);
        } elseif ($note->type === 'admission note') {
            $manager = new AdmissionNoteManager($note);
        } elseif ($note->type === 'discharge summary') {
            $manager = new DischargeSummaryManager($note);
        }

        $manager->setFlashData(true);

        return Inertia::render($manager->getPrintout(), [
            'contents' => $manager->getContents(true),
            'configs' => $manager->getConfigs(true),
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Events\CaseUpdated;
use App\Models\ReferCase;
use Illuminate\Support\Facades\Cache;

class UpdateCaseSearchIndex
{
    protected $cases;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cases = Cache::get('case-search-index', $this->initCaseSearchIndex());
    }

    /**
     * Handle the event.
     *
     * @param  CaseUpdated  $event
     * @return void
     */
    public function handle(CaseUpdated $event)
    {
        $index = $this->cases->search(function ($item) use ($event) {
            return $item['id'] === $event->case->id;
        });

        if ($index === false) {
            $this->cases->push($this->udpateCase($event->case));
        } else {
            $this->cases[$index] = $this->udpateCase($event->case);
        }

        Cache::put('case-search-index', $this->cases);
    }

    protected function initCaseSearchIndex()
    {
        return ReferCase::with('patient', 'referer', 'admission', 'center')
                          ->get()
                          ->transform(function ($case) {
                              return $this->udpateCase($case);
                          });
    }

    protected function udpateCase($case)
    {
        return [
            'id' => $case->id,
            'an' => $case->an,
            'hn' => $case->hn,
            'name' => $case->name,
            'room_number' => $case->room_number,
            'referer' => $case->referer->full_name,
            'admit_md' => $case->admission->notes()->whereType('admission note')->first()
                            ? $case->admission->notes()->whereType('admission note')->first()->author->full_name
                            : null,
            'dc_md' => $case->admission->notes()->whereType('discharge summary')->first()
                            ? $case->admission->notes()->whereType('discharge summary')->first()->author->full_name
                            : null,
        ];
    }
}

<?php

namespace App\Listeners;

use App\Events\CaseUpdated;

class UpdateCaseSearchIndex
{
    protected $case;

    /**
     * Handle the event.
     *
     * @param  CaseUpdated  $event
     * @return void
     */
    public function handle(CaseUpdated $event)
    {
        $this->case = $event->case;
        $meta = $this->case->meta;
        $metaUpdate = $this->getMeta();
        foreach ($metaUpdate as $key => $value) {
            $meta[$key] = $value;
        }
        $this->case->meta = $meta;

        return $this->case->save();
    }

    protected function getMeta()
    {
        return [
            'an' => $this->case->an,
            'hn' => $this->case->hn,
            'name' => $this->getName(),
            'room_number' => $this->case->room_number,
            'referer' => $this->case->referer->full_name,
            'admit_md' => $this->getAuthor('admission note'),
            'dc_md' => $this->getAuthor('discharge summary'),
        ];
    }

    protected function getAuthor($noteType)
    {
        if (! $this->case->admission) {
            return null;
        }

        return $this->case->admission->notes()->whereType($noteType)->first()
                ? $this->case->admission->notes()->whereType($noteType)->first()->author->full_name
                : null;
    }

    protected function getName()
    {
        if ($this->case->patient) {
            return $this->case->patient->profile['first_name'];
        }

        $names = collect(explode(' ', $this->case->name));
        if ($names->count() === 1) {
            return $this->case->name;
        }
        $names->pop();

        return $names->pop();
    }
}

<?php

namespace App;

use App\Models\ReferCase;

class BuildCaseSearchIndex
{
    public static function build()
    {
        ReferCase::with('note', 'admission', 'patient', 'referer')
                ->get()
                ->each(function ($case) {
                    $meta = $case->meta;
                    $metaUpdate = static::getMeta($case);
                    foreach ($metaUpdate as $key => $value) {
                        $meta[$key] = $value;
                    }
                    $case->meta = $meta;
                    $case->save();
                });
    }

    protected static function getMeta($case)
    {
        return [
            'an' => $case->an,
            'hn' => $case->hn,
            'name' => static::getName($case),
            'room_number' => $case->room_number,
            'referer' => $case->referer->full_name,
            'admit_md' => static::getAuthor($case, 'admission note'),
            'dc_md' => static::getAuthor($case, 'discharge summary'),
        ];
    }

    protected static function getAuthor(&$case, $noteType)
    {
        if (! $case->admission) {
            return null;
        }

        return $case->admission->notes()->whereType($noteType)->first()
                ? $case->admission->notes()->whereType($noteType)->first()->author->full_name
                : null;
    }

    protected static function getName(&$case)
    {
        $names = collect(explode(' ', $case->name));
        if ($names->count() === 1) {
            return $case->name;
        }
        $names->pop();

        return $names->pop();
    }
}

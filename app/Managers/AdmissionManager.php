<?php

namespace App\Managers;

use App\Models\Admission;
use Illuminate\Support\Str;

class AdmissionManager
{
    public function manage($an)
    {
        $admissionData = app()->make('App\Contracts\PatientAPI')->getAdmission($an);
        $admission = Admission::whereAn($an)->first();

        if ($admission) {
            // update
            $meta = $admission->meta;
            $meta['discharge_type'] = $admissionData['discharge_type_name'] ?? null;
            $meta['discharge_status'] = $admissionData['discharge_status_name'] ?? null;
            $admission->update([
                'dismissed_at' => $admissionData['dismissed_at'] ?? null,
                'meta' => $meta,
            ]);

            return [
                'found' => true,
                'admission' => $admission,
            ];
        }

        if (! $admissionData['found']) {
            return $admissionData;
        }

        // create
        $patient = (new PatientManager())->manage($admissionData['hn']);
        if (! $patient['found']) {
            return $patient; // rare case
        }

        return [
            'found' => true,
            'admission' => $patient['patient']->admissions()->create([
                'slug' => Str::uuid()->toString(),
                'an' => $an,
                'meta' => [
                    'place_name' => $admissionData['ward_name'] ?? null,
                    'place_name_short' => $admissionData['ward_name_short'] ?? null,
                    'attending' => $admissionData['attending_name'] ?? null,
                    'discharge_type' => $admissionData['discharge_type_name'] ?? null,
                    'discharge_status' => $admissionData['discharge_status_name'] ?? null,
                ],
                'encountered_at' => $admissionData['encountered_at'],
                'dismissed_at' => $admissionData['dismissed_at'],
            ]),
        ];
    }
}

<?php

namespace App\Managers;

class ReferNoteManager
{
    public function initNote()
    {
        return [
            'patient' => [
                'sat_code' => null,
                'hn' => null,
                'an' => null,
                'insurance' => null,
                'date_symptom_start' => null,
                'date_covid_infected' => null,
                'date_admit_origin' => null,
                'date_refer' => null,
                'date_expect_discharge' => null,
                'date_quarantine_end' => null,
                'meal' => null,
            ],
            'vital_signs' => [
                'temperature_celsius' => null,
                'pulse_per_minute' => null,
                'respiration_rate_per_minute' => null,
                'sbp' => null,
                'dbp' => null,
                'o2_sat' => null,
            ],
            'symptoms' => [
                'asymptomatic' => false,
                'asymptomatic_detail' => null,
                'fever' => false,
                'cough' => false,
                'sore_throat' => false,
                'rhinorrhoea' => false,
                'sputum' => false,
                'fatigue' => false,
                'anosmia' => false,
                'loss_of_taste' => false,
                'myalgia' => false,
                'diarrhea' => false,
                'others' => null,
            ],
            'adr' => [
                'none' => false,
                'detail' => null,
            ],
            'comorbids' => [
                'dm' => false,
                'ht' => false,
                'others' => null,
            ],
            'treatments' => [
                'temperature_per_day' => null,
                'oxygen_sat_RA' => null,
                'favipiravir' => false,
                'date_start_favipiravir' => null,
                'date_stop_favipiravir' => null,
                'date_repeat_NP_swap' => null,
            ],
            'diagnosis' => [
                'asymptomatic' => false,
                'uri' => false,
                'date_uri' => null,
                'pneumonia' => false,
                'date_pneumonia' => null,
                'gastroenteritis' => false,
                'others' => null,
            ],
        ];
    }
}

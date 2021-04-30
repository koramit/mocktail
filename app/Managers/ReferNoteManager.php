<?php

namespace App\Managers;

class ReferNoteManager
{
    public function initNote()
    {
        return [
            'patient' => [
                'sat_code' => '',
                'hn' => '',
                'an' => '',
                'insurance' => '',
                'date_symptom_start' => '',
                'date_covid_infected' => '',
                'date_admit' => '',
                'date_refer' => '',
                'date_quarantine_end' => '',
                'meal' => '',
            ],
            'vital_signs' => [
                'temperature_celsius' => '',
                'pulse_per_minute' => '',
                'respiration_rate_per_minute' => '',
                'sbp' => '',
                'dbp' => '',
                'o2_sat' => '',
            ],
            'symptoms' => [
                'asymptomatic' => false,
                'asymptomatic_detail' => '',
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
                'others' => '',
            ],
            'adr' => [
                'none' => false,
                'detail' => '',
            ],
            'comorbids' => [
                'dm' => false,
                'ht' => false,
                'others' => '',
            ],
            'treatments' => [
                'temperature_per_day' => '',
                'oxygen_sat_RA' => '',
                'date_start_favipiravir' => '',
                'date_stop_favipiravir' => '',
                'date_repeat_NP_swap' => '',
            ],
            'diagnosis' => [
                'asymptomatic' => false,
                'uri' => false,
                'date_uri' => '',
                'pneumonia' => false,
                'date_pneumonia' => '',
                'gastroenteritis' => false,
                'others' => '',
            ],
        ];
    }
}

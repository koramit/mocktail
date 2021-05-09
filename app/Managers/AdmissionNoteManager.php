<?php

namespace App\Managers;

class AdmissionNoteManager
{
    public static function initNote()
    {
        return [
            'patient' => [
                'sat_code' => null,
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
                'asymptomatic_symptom' => false,
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
                'other_symptoms' => null,
            ],
            'diagnosis' => [
                'asymptomatic_diagnosis' => false,
                'uri' => false,
                'date_uri' => null,
                'pneumonia' => false,
                'date_pneumonia' => null,
                'gastroenteritis' => false,
                'other_diagnosis' => null,
            ],
            'adr' => [
                'no_adr' => false,
                'adr_detail' => null,
            ],
            'comorbids' => [
                'no_comorbids' => false,
                'dm' => false,
                'ht' => false,
                'other_comorbids' => null,
            ],
            'treatments' => [
                'temperature_per_day' => null,
                'oxygen_sat_RA_per_day' => null,
                'favipiravir' => false,
                'date_start_favipiravir' => null,
                'date_stop_favipiravir' => null,
                'date_repeat_NP_swap' => null,
            ],
        ];
    }
}
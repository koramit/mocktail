<template>
    <div>
        <div class="bg-white rounded shadow-sm p-4 mt-8">
            <h2 class="font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-center text-xl">
                ใบส่งตัว
            </h2>
            <h3 class="font-normal underline text-dark-theme-light mt-6">
                ข้อมูลเบื้องต้น
            </h3>
            <div class="mt-2 sm:grid grid-rows-4 xl:grid-rows-3 grid-flow-col gap-2 lg:gap-3 xl:gap-4">
                <display-input
                    v-for="(field, key) in configs.patient"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.patient[field.name]"
                    :format="field.format ?? ''"
                />
            </div>

            <h3 class="font-normal underline text-dark-theme-light mt-12">
                Vital Signs ล่าสุด
            </h3>
            <div class="mt-2 sm:grid grid-rows-3 grid-flow-col gap-2 lg:gap-3 xl:gap-4">
                <display-input
                    v-for="(field, key) in configs.vital_signs"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.vital_signs[field.name]"
                />
            </div>

            <template
                v-for="(topic, key) in configs.topics"
                :key="key"
            >
                <h3 class="font-normal underline text-dark-theme-light mt-12">
                    {{ topic.label }}
                </h3>
                <div class="mt-2">
                    <display-input
                        class="mt-2 md:mt-0"
                        :data="contents[topic.name]"
                    />
                </div>
            </template>

            <h3 class="font-normal underline text-dark-theme-light mt-12">
                คำสั่งการรักษา
            </h3>
            <div
                class="mt-2 sm:grid grid-flow-col gap-2 lg:gap-3 xl:gap-4"
                :class="{
                    'grid-rows-1': filteredTreatments.length <= 3,
                    'grid-rows-2': filteredTreatments.length > 3
                }"
            >
                <display-input
                    v-for="(field, key) in filteredTreatments"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.treatments[field.name]"
                />
            </div>

            <tamplate v-if="contents.remark">
                <h3 class="font-normal underline text-dark-theme-light mt-12">
                    เพิ่มเติม
                </h3>
                <div class="mt-2">
                    <display-input
                        class="mt-2 md:mt-0"
                        :data="contents.remark"
                    />
                </div>
            </tamplate>
        </div>
    </div>
</template>

<script>
import DisplayInput from '../../Components/Helpers/DisplayInput.vue';
export default {
    components: { DisplayInput },
    props: {
        contents: { type: Object, required: true },
    },
    computed: {
        filteredTreatments () {
            let treatments = [];
            Object.keys(this.contents.treatments).forEach(key => {
                let index = this.configs.treatments.findIndex(t => t.name === key);
                if (index !== -1) {
                    treatments.push(this.configs.treatments[index]);
                }
            });
            return treatments;
        }
    },
    created () {
        this.configs = {
            patient: [
                { label: 'sat code', name: 'sat_code' },
                { label: 'สิทธิ์การรักษา', name: 'insurance' },
                { label: 'วันแรกที่มีอาการ', name: 'date_symptom_start', format: 'date' },
                { label: 'วันที่ตรวจพบเชื้อ', name: 'date_covid_infected', format: 'date' },
                { label: 'วันที่รับไว้ในโรงพยาบาล', name: 'date_admit_origin', format: 'date' },
                { label: 'วันที่ส่งผู้ป่วยไป Hospitel', name: 'date_refer', format: 'date' },
                { label: 'วันที่ครบกำหนดนอนใน hospitel', name: 'date_expect_discharge', format: 'date' },
                { label: 'วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน', name: 'date_quarantine_end', format: 'date' },
            ],
            vital_signs: [
                { label: 'Temp (℃)', name: 'temperature_celsius' },
                { label: 'Pulse (min)', name: 'pulse_per_minute' },
                { label: 'RR (min)', name: 'respiration_rate_per_minute' },
                { label: 'SBP (mmHg)', name: 'sbp' },
                { label: 'DBP (mmHg)', name: 'dbp' },
                { label: 'O₂ sat (% RA)', name: 'o2_sat' },
                { label: 'Level of consciousness', name: 'level_of_consciousness' },
                { label: 'emotional status', name: 'emotional_status' },
            ],
            topics: [
                { label: 'บันทึกอาการแสดง', name: 'symptoms' },
                { label: 'วินิจฉัย', name: 'diagnosis' },
                { label: 'ประวัติแพ้ยา/อาหาร ', name: 'adr' },
                { label: 'โรคประจำตัว ', name: 'comorbids' },
                { label: 'อาหาร', name: 'meal' },
                // { label: 'คำสั่งการรักษา ', name: 'treatments' },
                // { label: 'เพิ่มเติม', name: 'remark' },
                // { label: '', name: '' },
                // { label: '', name: '' },
            ],
            treatments: [
                { label: 'Temperature', name: 'temperature_per_day' },
                { label: 'Oxygen sat RA', name: 'oxygen_sat_RA_per_day' },
                { label: 'Favipiravir (วันที่เริ่มยา)', name: 'date_start_favipiravir' },
                { label: 'Favipiravir (กำหนดครบวันที่)', name: 'date_stop_favipiravir' },
                { label: 'นัดมาทำ NP swab ซ้ำ วันที่', name: 'date_repeat_NP_swap' },
            ]
        };
    },
};
</script>
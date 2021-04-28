<template>
    <div class="p-4">
        <h1 class="font-semibold text-lg underline text-center">
            DC summary
        </h1>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                ข้อมูลเบื้องต้น
            </h2>
            <form-input
                class="mt-2"
                name="sat_code"
                label="sat code"
                v-model="patient.sat_code"
            />
            <form-input
                class="mt-2"
                name="name"
                label="ชื่อผู้ป่วย"
                v-model="patient.name"
            />
            <form-input
                class="mt-2"
                name="hn"
                label="HN"
                v-model="patient.hn"
            />
            <form-input
                class="mt-2"
                name="an"
                label="AN"
                v-model="patient.an"
            />
            <form-datetime
                class="mt-2"
                label="วันที่รับไว้ใน Hospitel"
                v-model="patient.date_admit"
                name="date_admit"
            />
            <form-datetime
                class="mt-2"
                label="วันที่จำหน่ายออกจาก Hospitel"
                v-model="patient.date_discharge"
                name="date_discharge"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                ผลวินิจฉัย
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="diagnosis.asymptomatic"
                label="Asymptomatic COVID 19 infection"
                :toggler="true"
            />

            <div v-if="!diagnosis.asymptomatic">
                <form-checkbox
                    class="mt-2"
                    v-model="diagnosis.uri"
                    label="COVID 19 with URI"
                />

                <form-datetime
                    v-if="diagnosis.uri"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ uri"
                    v-model="diagnosis.date_uri"
                    name="date_uri"
                />

                <form-checkbox
                    class="mt-2"
                    v-model="diagnosis.pneumonia"
                    label="COVID 19 with Pneumonia"
                />

                <form-datetime
                    v-if="diagnosis.pneumonia"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ pneumonia"
                    v-model="diagnosis.date_pneumonia"
                    name="date_pneumonia"
                />

                <form-checkbox
                    class="mt-2"
                    v-model="diagnosis.gastroenteritis"
                    label="COVID 19 with Gastroenteritis"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                โรคประจำตัว
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="comorbid.none"
                label="ไม่มี"
                :toggler="true"
            />

            <div v-if="!comorbid.none">
                <form-checkbox
                    class="mt-2"
                    v-model="comorbid.dm"
                    label="เบาหวาน"
                />

                <form-checkbox
                    class="mt-2"
                    v-model="comorbid.ht"
                    label="ความดัน"
                />

                <form-input
                    class="mt-2"
                    placeholder="ระบุโรคประจำตัวอื่นๆ"
                    name="comorbid_others"
                    v-model="comorbid.others"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                Complication
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="complication.none"
                label="ไม่มี"
                :toggler="true"
            />

            <form-input
                v-if="!complication.none"
                class="mt-2"
                label="ระบุภาวะแทรกซ้อน"
                name="complication_detail"
                v-model="complication.detail"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                non OR procedure
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="none_OR_procedure.none"
                label="ไม่มี"
                :toggler="true"
            />

            <form-input
                v-if="!none_OR_procedure.none"
                class="mt-2"
                label="ระบุรายละเอียด"
                name="none_OR_procedure_detail"
                v-model="none_OR_procedure.detail"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                Vital Signs ล่าสุด
            </h2>

            <form-input
                class="mt-2"
                label="Temp (℃)"
                type="number"
                name="temperature_celsius"
                v-model="vital_signs.temperature_celsius"
            />

            <form-input
                class="mt-2"
                label="Pulse (min)"
                type="tel"
                name="pulse_per_minute"
                v-model="vital_signs.pulse_per_minute"
            />

            <form-input
                class="mt-2"
                label="RR (min)"
                type="tel"
                name="respiration_rate_per_minute"
                v-model="vital_signs.respiration_rate_per_minute"
            />

            <form-input
                class="mt-2"
                label="SBP (mmHg)"
                name="sbp"
                type="tel"
                v-model="vital_signs.sbp"
            />

            <form-input
                class="mt-2"
                label="DBP (mmHg)"
                name="dbp"
                type="tel"
                v-model="vital_signs.dbp"
            />

            <form-input
                class="mt-2"
                label="O₂ sat (% RA)"
                type="tel"
                name="o2_sat"
                v-model="vital_signs.o2_sat"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                อาการ/อาการแสดงวันจำหน่าย
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="symptoms.asymptomatic"
                label="Asymptomatic"
                :toggler="true"
            />

            <div v-if="!symptoms.asymptomatic">
                <form-checkbox
                    v-for="(symptom, key) in symptomSet"
                    :key="key"
                    class="mt-2"
                    v-model="symptoms[symptom.name]"
                    :label="symptom.label"
                />

                <form-input
                    class="mt-2"
                    placeholder="อาการอื่นๆ คือ"
                    name="symptoms_others"
                    v-model="symptoms.others"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                ปัญหาที่ต้องดูแลต่อเนื่อง
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="problem_list.none"
                label="ไม่มี"
                :toggler="true"
            />

            <div v-if="!problem_list.none">
                <form-checkbox
                    class="mt-2"
                    v-model="problem_list.quarantine"
                    label="ต้องกักตัวต่อที่บ้าน"
                />

                <form-datetime
                    v-if="problem_list.quarantine"
                    class="mt-2"
                    label="กักตัวต่อที่บ้านถึงวันที่"
                    v-model="problem_list.date_quarantine_end"
                    name="date_quarantine_end"
                />

                <form-input
                    class="mt-2"
                    label="ระบุปัญหาอื่นๆ"
                    name="problem_list_others"
                    v-model="problem_list.others"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                วันนัดครั้งต่อไป
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="appointment.none"
                label="ไม่มี"
                :toggler="true"
            />

            <div v-if="!appointment.none">
                <form-datetime
                    class="mt-2"
                    label="วันที่"
                    v-model="appointment.date"
                    name="date"
                />
            </div>
        </div>

        <button class="btn btn-dark w-full my-4">
            SUBMIT
        </button>
    </div>
</template>

<script>
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormDatetime from '@/Components/Controls/FormDatetime';
import FormInput from '@/Components/Controls/FormInput';
export default {
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
    },
    data () {
        return {
            patient: {
                name: '',
                hn: '',
                an: '',
                date_admit: '',
                date_discharge: '',
                sat_code: '',
            },
            diagnosis: {
                asymptomatic: false,
                uri: false,
                date_uri: '',
                pneumonia: false,
                date_pneumonia: '',
                gastroenteritis: false,
            },
            comorbid: {
                none: false,
                dm: false,
                ht: false,
                others: ''
            },
            complication: {
                none: true,
                detail: '',
            },
            none_OR_procedure: {
                none: true,
                detail: '',
            },
            vital_signs: {
                temperature_celsius: '',
                pulse_per_minute: '',
                respiration_rate_per_minute: '',
                sbp: '',
                dbp: '',
                o2_sat: '',
            },
            symptoms: {
                asymptomatic: false,
                fever: false,
                cough: false,
                sore_throat: false,
                rhinorrhoea: false,
                sputum: false,
                fatigue: false,
                anosmia: false,
                loss_of_taste: false,
                myalgia: false,
                diarrhea: false,
                others: '',
                adr: false,
                adr_detail: ''
            },
            symptomSet: [
                { label: 'ไข้', name: 'fever'},
                { label: 'ไอ', name: 'cough'},
                { label: 'เจ็บคอ', name: 'sore_throat'},
                { label: 'มีน้ำมูก', name: 'rhinorrhoea'},
                { label: 'มีเสมหะ', name: 'sputum'},
                { label: 'เหนื่อย', name: 'fatigue'},
                { label: 'จมูกไม่ได้กลิ่น', name: 'anosmia'},
                { label: 'ลิ้นไม่ได้รส', name: 'loss_of_taste'},
                { label: 'ปวดเมื่อยกล้ามเนื้อ', name: 'myalgia'},
                { label: 'ท้องเสีย', name: 'diarrhea'},
            ],
            problem_list: {
                none: false,
                quarantine: false,
                date_quarantine_end: '',
                others: ''
            },
            appointment: {
                none: false,
                date: '',
            },
        };
    },
    created () {
        document.title = 'Discharge Summary';
    },
    mounted() {
        this.$nextTick(function () {
            const pageLoadingIndicator = document.getElementById('page-loading-indicator');
            if (pageLoadingIndicator) {
                pageLoadingIndicator.remove();
            }
        });
    },
};
</script>
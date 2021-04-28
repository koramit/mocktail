<template>
    <div class="p-4">
        <h1 class="font-semibold text-lg underline text-center">
            Admission note โรงพยาบาลสนามใบหยก
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
            <form-select
                class="mt-2"
                label="สิทธิ์การรักษา"
                v-model="patient.insurrance"
                name="insurrance"
                :options="['กรมบัญชีกลาง', 'ประกันสังคม', '30 บาท', 'ชำระเงินเอง', 'อื่นๆ']"
            />
            <form-input
                v-if="patient.insurrance === 'อื่นๆ'"
                class="mt-2"
                name="insurrance_other"
                label="ระบุสิทธิ์การรักษาอื่นๆ"
                v-model="patient.insurrance_other"
            />
            <form-datetime
                class="mt-2"
                label="วันที่รับไว้ใน Hospitel"
                v-model="patient.date_admit"
                name="date_admit"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ตรวจพบเชื้อ"
                v-model="patient.date_dx_covid"
                name="date_dx_covid"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดกักตัว"
                v-model="patient.date_quarantine_completed"
                name="date_quarantine_completed"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                Vital Signs ล่าสุด (? แรกรับ รอถาม 🦜)
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
                อาการ
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
                ประวัติแพ้ยา/อาหาร
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="symptoms.adr"
                label="แพ้ยา/แพ้อาหาร"
                :toggler="true"
            />

            <form-input
                v-if="symptoms.adr"
                class="mt-2"
                label="ระบุชนิดของยา/อาหารที่แพ้"
                name="others"
                v-model="symptoms.adr_detail"
            />
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
                อาหาร
            </h2>
            <form-select
                class="mt-2"
                name="meal"
                v-model="patient.meal"
                :options="['ปกติ', 'อิสลาม', 'มังสวิรัติ', 'อื่นๆ']"
            />
            <form-input
                v-if="patient.meal === 'อื่นๆ'"
                class="mt-2"
                name="meal_other"
                label="ระบุอาหารที่ต้องการทานอื่นๆ"
                v-model="patient.meal_other"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold">
                คำสั่งการรักษา
            </h2>

            <form-select
                class="mt-2"
                label="Temperature"
                name="temperature_per_day"
                v-model="treatments.temperature_per_day"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
            />

            <form-select
                class="mt-2"
                label="Oxygen sat RA"
                name="oxygen_sat_RA"
                v-model="treatments.oxygen_sat_RA"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
            />

            <form-datetime
                class="mt-2"
                label="Favipiravir (วันที่เริ่มยา)"
                v-model="treatments.date_start_favipiravir"
                name="date_start_favipiravir"
            />

            <form-datetime
                class="mt-2"
                label="Favipiravir (กำหนดครบวันที่)"
                v-model="treatments.date_stop_favipiravir"
                name="date_stop_favipiravir"
            />

            <form-datetime
                class="mt-2"
                label="นัดมาทำ NP swab ซ้ำ วันที่"
                v-model="treatments.date_repeat_NP_swap"
                name="date_repeat_NP_swap"
            />
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
import FormSelect from '@/Components/Controls/FormSelect';
export default {
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
        FormSelect,
    },
    watch: {
        symptoms: {
            handler(val) {
                let symtomsChecked = Object.keys(val).filter(symptom => val[symptom] === true);

                if (!symtomsChecked.length) {
                    return;
                }

                if (symtomsChecked.indexOf('diarrhea') !== -1 && !this.diagnosis.gastroenteritis) {
                    this.diagnosis.gastroenteritis = true;
                }

                if (!this.diagnosis.uri) {
                    if (symtomsChecked.length > 1) {
                        this.diagnosis.uri = true;
                    } else if (symtomsChecked[0] !== 'diarrhea') {
                        this.diagnosis.uri = true;
                    }
                }
            },
            deep: true
        },
    },
    data () {
        return {
            patient: {
                name: '',
                hn: '',
                an: '',
                insurrance: '',
                insurrance_other: '',
                date_admit: '',
                date_transfer: '2021-04-27',
                date_dx_covid: '',
                date_quarantine_completed: '',
                meal: '',
                meal_other: '',
                sat_code: '',
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
            comorbid: {
                dm: false,
                ht: false,
                others: '',
            },
            treatments: {
                temperature_per_day: '',
                oxygen_sat_RA: '',
                date_start_favipiravir: '',
                date_stop_favipiravir: '',
                date_repeat_NP_swap: '',
            },
            diagnosis: {
                asymptomatic: false,
                uri: false,
                date_uri: '',
                pneumonia: false,
                date_pneumonia: '',
                gastroenteritis: false,
            }
        };
    },
    created () {
        document.title = 'Admission note';
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
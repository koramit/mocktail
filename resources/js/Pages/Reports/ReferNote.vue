<template>
    <div>
        <!-- preliminary data -->
        <div class="bg-white rounded shadow-sm p-4  mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ข้อมูลเบื้องต้น
            </h2>
            <form-input
                class="mt-2"
                name="sat_code"
                label="sat code"
                v-model="form.patient.sat_code"
            />
            <form-input
                class="mt-2"
                name="hn"
                type="tel"
                label="HN ศิริราช"
                v-model="form.patient.hn"
            />
            <form-input
                class="mt-2"
                name="name"
                label="ชื่อผู้ป่วย"
                v-model="form.patient.name"
            />
            <form-select
                class="mt-2"
                label="สิทธิ์การรักษา"
                v-model="form.patient.insurance"
                name="insurance"
                :options="configs.insurances"
                :allow-other="true"
                ref="insurance"
            />
            <form-datetime
                class="mt-2"
                label="วันแรกที่มีอาการ"
                v-model="form.patient.date_symptom_start"
                name="date_symptom_start"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ตรวจพบเชื้อ"
                v-model="form.patient.date_covid_infected"
                name="date_covid_infected"
            />
            <form-datetime
                class="mt-2"
                label="วันที่รับไว้ในโรงพยาบาล"
                v-model="form.patient.date_admit_origin"
                name="date_admit_origin"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ส่งผู้ป่วยไป Hospitel"
                v-model="form.patient.date_refer"
                name="date_refer"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดนอนใน hospitel"
                v-model="form.patient.date_expect_discharge"
                name="date_quarantine_end"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน"
                v-model="form.patient.date_quarantine_end"
                name="date_quarantine_end"
            />
        </div>

        <!-- vital signs -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                Vital Signs ล่าสุด
            </h2>
            <form-input
                class="mt-2"
                label="Temp (℃)"
                type="number"
                name="temperature_celsius"
                v-model="form.vital_signs.temperature_celsius"
            />
            <form-input
                class="mt-2"
                label="Pulse (min)"
                type="tel"
                name="pulse_per_minute"
                v-model="form.vital_signs.pulse_per_minute"
            />
            <form-input
                class="mt-2"
                label="RR (min)"
                type="tel"
                name="respiration_rate_per_minute"
                v-model="form.vital_signs.respiration_rate_per_minute"
            />
            <form-input
                class="mt-2"
                label="SBP (mmHg)"
                name="sbp"
                type="tel"
                v-model="form.vital_signs.sbp"
            />
            <form-input
                class="mt-2"
                label="DBP (mmHg)"
                name="dbp"
                type="tel"
                v-model="form.vital_signs.dbp"
            />
            <form-input
                class="mt-2"
                label="O₂ sat (% RA)"
                type="tel"
                name="o2_sat"
                v-model="form.vital_signs.o2_sat"
            />
        </div>

        <!-- symptoms -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                บันทึกอาการแสดง
            </h2>
            <small
                class="my-t text-red-700 text-sm"
                v-if="form.errors.symptoms"
            >{{ form.errors.symptoms }}</small>
            <form-checkbox
                class="mt-2"
                v-model="form.symptoms.asymptomatic_symptom"
                label="Asymptomatic"
                :toggler="true"
            />
            <div v-if="!form.symptoms.asymptomatic_symptom">
                <form-checkbox
                    v-for="(symptom, key) in configs.symptoms"
                    :key="key"
                    class="mt-2"
                    v-model="form.symptoms[symptom.name]"
                    :label="symptom.label"
                />
                <form-input
                    class="mt-2"
                    placeholder="อาการอื่นๆ คือ"
                    name="other_symptoms"
                    v-model="form.symptoms.other_symptoms"
                />
            </div>
            <form-select
                v-else
                class="mt-2"
                v-model="form.symptoms.asymptomatic_detail"
                name="asymptomatic_detail"
                :options="['ไม่มีอาการตั้งแต่ต้น', 'อาการดีขึ้นแล้ว']"
            />
        </div>

        <!-- diagnosis -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                วินิจฉัย
            </h2>
            <small
                class="my-t text-red-700 text-sm"
                v-if="form.errors.diagnosis"
            >{{ form.errors.diagnosis }}</small>
            <form-checkbox
                class="mt-2"
                v-model="form.diagnosis.asymptomatic_diagnosis"
                label="Asymptomatic COVID 19 infection"
                :toggler="true"
            />
            <div v-if="!form.diagnosis.asymptomatic_diagnosis">
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.uri"
                    label="COVID 19 with URI"
                />
                <form-datetime
                    v-if="form.diagnosis.uri"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ uri"
                    v-model="form.diagnosis.date_uri"
                    name="date_uri"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.pneumonia"
                    label="COVID 19 with Pneumonia"
                />
                <form-datetime
                    v-if="form.diagnosis.pneumonia"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ pneumonia"
                    v-model="form.diagnosis.date_pneumonia"
                    name="date_pneumonia"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.gastroenteritis"
                    label="COVID 19 with Gastroenteritis"
                />
                <form-input
                    class="mt-2"
                    name="other_diagnosis"
                    placeholder="วินิจฉัยอื่นๆ"
                    v-model="form.diagnosis.other_diagnosis"
                />
            </div>
        </div>

        <!-- ADR -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ประวัติแพ้ยา/อาหาร
            </h2>
            <form-checkbox
                class="mt-2"
                v-model="form.adr.no_adr"
                label="ไม่แพ้"
                :toggler="true"
            />
            <form-input
                v-if="!form.adr.no_adr"
                class="mt-2"
                placeholder="ระบุชนิดของยา/อาหารที่แพ้"
                name="adr_detail"
                v-model="form.adr.adr_detail"
            />
        </div>

        <!-- comorbids -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                โรคประจำตัว
            </h2>
            <small
                class="my-t text-red-700 text-sm"
                v-if="form.errors.comorbids"
            >{{ form.errors.comorbids }}</small>
            <form-checkbox
                class="mt-2"
                v-model="form.comorbids.no_comorbids"
                label="ไม่มี"
                :toggler="true"
            />
            <div v-if="!form.comorbids.no_comorbids">
                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.dm"
                    label="เบาหวาน"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.ht"
                    label="ความดันโลหิตสูง"
                />
                <form-input
                    class="mt-2"
                    placeholder="ระบุโรคประจำตัวอื่นๆ"
                    name="other_comorbids"
                    v-model="form.comorbids.other_comorbids"
                />
            </div>
        </div>

        <!-- meal -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                อาหาร
            </h2>
            <form-select
                class="mt-2"
                name="meal"
                v-model="form.patient.meal"
                :options="configs.meals"
                :allow-other="true"
                ref="meal"
            />
        </div>

        <!-- treatments -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                คำสั่งการรักษา
            </h2>
            <form-select
                class="mt-2"
                label="Temperature"
                name="temperature_per_day"
                v-model="form.treatments.temperature_per_day"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
            />
            <form-select
                class="mt-2"
                label="Oxygen sat RA"
                name="oxygen_sat_RA_per_day"
                v-model="form.treatments.oxygen_sat_RA_per_day"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
            />
            <form-checkbox
                class="mt-4"
                v-model="form.treatments.favipiravir"
                label="FAVIPIRAVIR"
                :toggler="true"
            />
            <template v-if="form.treatments.favipiravir">
                <form-datetime
                    class="mt-2"
                    label="Favipiravir (วันที่เริ่มยา)"
                    v-model="form.treatments.date_start_favipiravir"
                    name="date_start_favipiravir"
                />
                <form-datetime
                    class="mt-2"
                    label="Favipiravir (กำหนดครบวันที่)"
                    v-model="form.treatments.date_stop_favipiravir"
                    name="date_stop_favipiravir"
                />
            </template>
            <form-datetime
                class="mt-2"
                label="นัดมาทำ NP swab ซ้ำ วันที่"
                v-model="form.treatments.date_repeat_NP_swap"
                name="date_repeat_NP_swap"
            />
            <small class="text-md text-thick-theme-light italic">๏ กรณีบุคลากรทางการแพทย์ศิริราช</small>
        </div>

        <!-- uploads -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                แนบรูปถ่าย
            </h2>
            <image-uploader
                class="mt-2"
                label="1. Film Chest ล่าสุด"
                :filename="form.uploads.film"
                name="contents->uploads->film"
                :note-id="configs.note_id"
                v-model="form.uploads.film"
                :readonly="true"
            />
            <image-uploader
                class="mt-2"
                label="2. ใบรายงานผล COVID"
                :filename="form.uploads.lab"
                name="contents->uploads->lab"
                :note-id="configs.note_id"
                v-model="form.uploads.lab"
                :readonly="true"
            />
            <image-uploader
                class="mt-2"
                v-if="$page.props.user.center !== 'ศิริราช'"
                label="3. รูปถ่ายหน้าบัตรประชาชน (หากยังไม่มี HN ศิริราช)"
                :filename="form.uploads.id_document"
                name="contents->uploads->id_document"
                :note-id="configs.note_id"
                v-model="form.uploads.id_document"
                :readonly="true"
            />
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/Components/Layouts/Layout';
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormDatetime from '@/Components/Controls/FormDatetime';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import ImageUploader from '@/Components/Controls/ImageUploader';
export default {
    layout: Layout,
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
        FormSelect,
        ImageUploader,
    },
    props: {
        contents: { type: Object, required: true },
        formConfigs: { type: Object, required: true }
    },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            form: useForm({...this.contents}),
            configs: {...this.formConfigs},
            selectOtherPlaceholder: '',
            otherItem: '',
            otherItemAdded: false,
        };
    },
    created () {
        if (this.form.patient.insurance && !this.configs.insurances.includes(this.form.patient.insurance)) {
            this.configs.insurances.push(this.form.patient.insurance);
        }

        if (this.form.patient.meal && !this.configs.meals.includes(this.form.patient.meal)) {
            this.configs.meals.push(this.form.patient.meal);
        }
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
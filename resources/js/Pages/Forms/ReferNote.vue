<template>
    <div>
        <!-- preliminary data -->
        <div class="bg-white rounded shadow-sm p-4  mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ข้อมูลเบื้องต้น
            </h2>
            <!-- next features -->
            <!-- <form-checkbox
                class="mt-4"
                v-model="form.no_admit"
                label="ส่งตัวโดยไม่ได้รับไว้ในโรงพยาบาล"
                :toggler="true"
                @autosave="autosave('no_admit')"
            /> -->
            <form-input
                class="mt-2"
                name="sat_code"
                label="sat code"
                v-model="form.patient.sat_code"
                :error="form.errors.sat_code"
                @autosave="autosave('patient.sat_code')"
            />
            <div class="flex items-center mt-1">
                <alphanumeric-reader @recognized="(result) => form.patient.sat_code = result ? result : 'ขอโทษนะ เราอ่านไม่ออกล่ะ 😅'" />
                <button class="mx-2">
                    <small class="text-md text-dark-theme-light italic">ตัวช่วยอ่าน SAT CODE </small>
                    <icon
                        class="w-4 h-4 text-dark-theme-light inline"
                        name="question-circle"
                    />
                </button>
            </div>
            <form-input
                class="mt-2"
                name="tel_no"
                type="tel"
                label="หมายเลขโทรศัพท์ของผู้ป่วย"
                v-model="form.patient.tel_no"
                :error="form.errors.tel_no"
                @autosave="autosave('patient.tel_no')"
            />
            <form-input
                class="mt-2"
                name="hn"
                type="tel"
                label="HN ศิริราช"
                v-model="form.patient.hn"
                :error="form.errors.hn"
                @autosave="autosave('patient.hn')"
            />
            <form-input
                class="mt-2"
                name="name"
                label="ชื่อผู้ป่วย"
                v-model="form.patient.name"
                :error="form.errors.name"
                @autosave="autosave('patient.name')"
            />
            <form-select
                class="mt-2"
                label="สิทธิ์การรักษา"
                v-model="form.patient.insurance"
                :error="form.errors.insurance"
                name="insurance"
                :options="configs.insurances"
                :allow-other="true"
                ref="insurance"
                @autosave="autosave('patient.insurance')"
            />
            <!-- next feature -->
            <form-select
                v-if="$page.props.user.center === 'ศิริราช'"
                class="mt-2"
                label="หอผู้ป่วย"
                v-model="form.patient.ward"
                :error="form.errors.ward"
                name="ward"
                :options="configs.wards"
                :allow-other="true"
                ref="ward"
                @autosave="autosave('patient.ward')"
            />
            <form-datetime
                v-if="!form.no_admit"
                class="mt-2"
                label="วันแรกที่มีอาการ"
                v-model="form.patient.date_symptom_start"
                :error="form.errors.date_symptom_start"
                name="date_symptom_start"
                @autosave="autosave('patient.date_symptom_start')"
            />
            <small class="text-md text-thick-theme-light italic">๏ หากไม่มีอาการให้ใส่วันที่ตรวจพบเชื้อ</small>
            <form-datetime
                class="mt-2"
                label="วันที่ตรวจพบเชื้อ"
                v-model="form.patient.date_covid_infected"
                :error="form.errors.date_covid_infected"
                name="date_covid_infected"
                @autosave="autosave('patient.date_covid_infected')"
            />
            <form-datetime
                v-if="!form.no_admit"
                class="mt-2"
                label="วันที่รับไว้ในโรงพยาบาล"
                v-model="form.patient.date_admit_origin"
                :error="form.errors.date_admit_origin"
                name="date_admit_origin"
                @autosave="autosave('patient.date_admit_origin')"
            />
            <small
                v-if="$page.props.user.center === 'ศิริราช'"
                class="text-md text-thick-theme-light italic"
            >๏ กรณีไม่ได้รับไว้ในโรงพยาบาล ให้ใส่วันที่คัดกรอง</small>
            <form-datetime
                class="mt-2"
                label="วันที่ส่งผู้ป่วยไป Hospitel"
                v-model="form.patient.date_refer"
                :error="form.errors.date_refer"
                name="date_refer"
                @autosave="autosave('patient.date_refer')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดนอนใน hospitel"
                v-model="form.patient.date_expect_discharge"
                :error="form.errors.date_expect_discharge"
                name="date_quarantine_end"
                @autosave="autosave('patient.date_expect_discharge')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน"
                v-model="form.patient.date_quarantine_end"
                :error="form.errors.date_quarantine_end"
                name="date_quarantine_end"
                @autosave="autosave('patient.date_quarantine_end')"
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
                :error="form.errors.temperature_celsius"
                @autosave="autosave('vital_signs.temperature_celsius')"
            />
            <form-input
                class="mt-2"
                label="Pulse (min)"
                type="tel"
                name="pulse_per_minute"
                v-model="form.vital_signs.pulse_per_minute"
                :error="form.errors.pulse_per_minute"
                @autosave="autosave('vital_signs.pulse_per_minute')"
            />
            <form-input
                class="mt-2"
                label="RR (min)"
                type="tel"
                name="respiration_rate_per_minute"
                v-model="form.vital_signs.respiration_rate_per_minute"
                :error="form.errors.respiration_rate_per_minute"
                @autosave="autosave('vital_signs.respiration_rate_per_minute')"
            />
            <form-input
                class="mt-2"
                label="SBP (mmHg)"
                name="sbp"
                type="tel"
                v-model="form.vital_signs.sbp"
                :error="form.errors.sbp"
                @autosave="autosave('vital_signs.sbp')"
            />
            <form-input
                class="mt-2"
                label="DBP (mmHg)"
                name="dbp"
                type="tel"
                v-model="form.vital_signs.dbp"
                :error="form.errors.dbp"
                @autosave="autosave('vital_signs.dbp')"
            />
            <form-input
                class="mt-2"
                label="O₂ sat (% RA)"
                type="tel"
                name="o2_sat"
                v-model="form.vital_signs.o2_sat"
                :error="form.errors.o2_sat"
                @autosave="autosave('vital_signs.o2_sat')"
            />
            <form-input
                class="mt-2"
                label="Level of consciousness"
                name="level_of_consciousness"
                v-model="form.vital_signs.level_of_consciousness"
                :readonly="true"
            />
            <small class="text-md text-thick-theme-light italic">๏ ข้อมูลโดยปริยาย</small>
            <form-input
                class="mt-2"
                label="emotional status"
                name="emotional_status"
                v-model="form.vital_signs.emotional_status"
                :readonly="true"
            />
            <small class="text-md text-thick-theme-light italic">๏ ข้อมูลโดยปริยาย</small>
        </div>

        <template v-if="!form.no_admit">
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
                    @autosave="autosave('symptoms.asymptomatic_symptom')"
                />
                <div v-if="!form.symptoms.asymptomatic_symptom">
                    <form-checkbox
                        v-for="(symptom, key) in configs.symptoms"
                        :key="key"
                        class="mt-2"
                        v-model="form.symptoms[symptom.name]"
                        :label="symptom.label"
                        @autosave="autosave('symptoms.' + symptom.name)"
                    />
                    <form-input
                        class="mt-2"
                        placeholder="อาการอื่นๆ คือ"
                        name="other_symptoms"
                        v-model="form.symptoms.other_symptoms"
                        @autosave="autosave('symptoms.other_symptoms')"
                    />
                </div>
                <form-select
                    v-else
                    class="mt-2"
                    v-model="form.symptoms.asymptomatic_detail"
                    :error="form.errors.asymptomatic_detail"
                    name="asymptomatic_detail"
                    :options="['ไม่มีอาการตั้งแต่ต้น', 'อาการดีขึ้นแล้ว']"
                    @autosave="autosave('symptoms.asymptomatic_detail')"
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
                    @autosave="autosave('diagnosis.asymptomatic_diagnosis')"
                />
                <div v-if="!form.diagnosis.asymptomatic_diagnosis">
                    <form-checkbox
                        class="mt-2"
                        v-model="form.diagnosis.uri"
                        label="COVID 19 with URI"
                        @autosave="autosave('diagnosis.uri')"
                    />
                    <template v-if="form.diagnosis.uri">
                        <form-datetime
                            class="mt-2"
                            label="วันที่เริ่มมีอาการ uri"
                            v-model="form.diagnosis.date_uri"
                            :error="form.errors.date_uri"
                            name="date_uri"
                            @autosave="autosave('diagnosis.date_uri')"
                        />
                        <small class="text-md text-thick-theme-light italic">๏ กรณีมีอาการหลัง admit แล้ว ให้ใส่วันที่ตรวจพบเชื้อแทน</small>
                    </template>
                    <form-checkbox
                        class="mt-2"
                        v-model="form.diagnosis.pneumonia"
                        label="COVID 19 with Pneumonia"
                        @autosave="autosave('diagnosis.pneumonia')"
                    />
                    <template v-if="form.diagnosis.pneumonia">
                        <form-datetime
                            class="mt-2"
                            label="วันที่เริ่มมีอาการ pneumonia"
                            v-model="form.diagnosis.date_pneumonia"
                            :error="form.errors.date_pneumonia"
                            name="date_pneumonia"
                            @autosave="autosave('diagnosis.date_pneumonia')"
                        />
                        <small class="text-md text-thick-theme-light italic">๏ กรณีมีอาการหลัง admit แล้ว ให้ใส่วันที่ตรวจพบเชื้อแทน</small>
                    </template>
                    <form-checkbox
                        class="mt-2"
                        v-model="form.diagnosis.gastroenteritis"
                        label="COVID 19 with Gastroenteritis"
                        @autosave="autosave('diagnosis.gastroenteritis')"
                    />
                    <form-input
                        class="mt-2"
                        name="other_diagnosis"
                        placeholder="วินิจฉัยอื่นๆ"
                        v-model="form.diagnosis.other_diagnosis"
                        @autosave="autosave('diagnosis.other_diagnosis')"
                    />
                </div>
            </div>
        </template>
        <div
            class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12"
            v-else
        >
            <h2 class="font-semibold text-thick-theme-light">
                ข้อมูลบันทึกอาการแสดงและวินิจฉัย
            </h2>
            <div class="text-sm tracking-wide font-semibold">
                <p class="text-dark-theme-light mt-4">
                    ๏ บันทึกอาการแสดง: Asymptomatic
                </p>
                <p class="text-dark-theme-light mt-2">
                    ๏ วินิจฉัย: Asymptomatic COVID 19 infection
                </p>
                <p class="text-yellow-400 mt-4">
                    ลงให้อัตโนมัติเนื่องจากไม่ได้รับไว้ในโรงพยาบาล
                </p>
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
                @autosave="autosave('adr.no_adr')"
            />
            <form-input
                v-if="!form.adr.no_adr"
                class="mt-2"
                placeholder="ระบุชนิดของยา/อาหารที่แพ้"
                name="adr_detail"
                v-model="form.adr.adr_detail"
                :error="form.errors.adr_detail"
                @autosave="autosave('adr.adr_detail')"
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
                @autosave="autosave('comorbids.no_comorbids')"
            />
            <div v-if="!form.comorbids.no_comorbids">
                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.dm"
                    label="เบาหวาน"
                    @autosave="autosave('comorbids.dm')"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.ht"
                    label="ความดันโลหิตสูง"
                    @autosave="autosave('comorbids.ht')"
                />
                <form-input
                    class="mt-2"
                    placeholder="ระบุโรคประจำตัวอื่นๆ"
                    name="other_comorbids"
                    v-model="form.comorbids.other_comorbids"
                    @autosave="autosave('comorbids.other_comorbids')"
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
                :error="form.errors.meal"
                :options="configs.meals"
                :allow-other="true"
                ref="meal"
                @autosave="autosave('patient.meal')"
            />
        </div>

        <!-- treatments -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                คำสั่งการรักษา
            </h2>
            <template v-if="!form.no_admit">
                <form-select
                    class="mt-2"
                    label="Temperature"
                    name="temperature_per_day"
                    v-model="form.treatments.temperature_per_day"
                    :error="form.errors.temperature_per_day"
                    :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
                    @autosave="autosave('treatments.temperature_per_day')"
                />
                <form-select
                    class="mt-2"
                    label="Oxygen sat RA"
                    name="oxygen_sat_RA_per_day"
                    v-model="form.treatments.oxygen_sat_RA_per_day"
                    :error="form.errors.oxygen_sat_RA_per_day"
                    :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
                    @autosave="autosave('treatments.oxygen_sat_RA_per_day')"
                />
                <form-checkbox
                    class="mt-4"
                    v-model="form.treatments.favipiravir"
                    label="FAVIPIRAVIR"
                    :toggler="true"
                    @autosave="autosave('treatments.favipiravir')"
                />
                <template v-if="form.treatments.favipiravir">
                    <form-datetime
                        class="mt-2"
                        label="Favipiravir (วันที่เริ่มยา)"
                        v-model="form.treatments.date_start_favipiravir"
                        :error="form.errors.date_start_favipiravir"
                        name="date_start_favipiravir"
                        @autosave="autosave('treatments.date_start_favipiravir')"
                    />
                    <form-datetime
                        class="mt-2"
                        label="Favipiravir (กำหนดครบวันที่)"
                        v-model="form.treatments.date_stop_favipiravir"
                        :error="form.errors.date_stop_favipiravir"
                        name="date_stop_favipiravir"
                        @autosave="autosave('treatments.date_stop_favipiravir')"
                    />
                </template>
            </template>
            <form-datetime
                class="mt-2"
                label="นัดมาทำ NP swab ซ้ำ วันที่"
                v-model="form.treatments.date_repeat_NP_swap"
                :error="form.errors.date_repeat_NP_swap"
                name="date_repeat_NP_swap"
                @autosave="autosave('treatments.date_repeat_NP_swap')"
            />
            <small class="text-md text-thick-theme-light italic">๏ กรณีบุคลากรทางการแพทย์ศิริราช</small>
        </div>

        <!-- uploads -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                แนบรูปถ่าย
            </h2>
            <small
                class="my-t text-red-700 text-sm"
                v-if="$page.props.errors.file"
            >{{ $page.props.errors.file }}</small>
            <image-uploader
                class="mt-2"
                v-if="$page.props.user.center !== 'ศิริราช'"
                label="๏ Film Chest ล่าสุด"
                :filename="form.uploads.film"
                name="contents->uploads->film"
                :note-id="configs.note_id"
                v-model="form.uploads.film"
                :error="form.errors.film"
            />
            <image-uploader
                class="mt-2"
                label="๏ ใบรายงานผล COVID"
                :filename="form.uploads.lab"
                name="contents->uploads->lab"
                :note-id="configs.note_id"
                v-model="form.uploads.lab"
                :error="form.errors.lab"
            />
            <image-uploader
                class="mt-2"
                v-if="$page.props.user.center !== 'ศิริราช'"
                label="๏ รูปถ่ายหน้าบัตรประชาชน (กรณีไม่มี HN)"
                :filename="form.uploads.id_document"
                name="contents->uploads->id_document"
                :note-id="configs.note_id"
                v-model="form.uploads.id_document"
                :error="form.errors.id_document"
            />
        </div>

        <!-- remark -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                เพิ่มเติม
            </h2>
            <form-textarea
                class="mt-2"
                placeholder="ระบุข้อมูลอื่นๆ"
                name="remark"
                v-model="form.remark"
                @autosave="autosave('remark')"
            />
        </div>

        <spinner-button
            class="btn btn-bitter w-full mt-4 sm:mt-6 md:mt-12"
            :class="{
                'btn-dark': !form.submitted,
                'btn-bitter': form.submitted,
            }"
            @click="confirm"
            :spin="form.processing"
        >
            {{ form.submitted ? 'ยืนยัน':'ยืนยันการส่งต่อผู้ป่วย' }}
        </spinner-button>

        <form-select-other
            :placeholder="selectOtherPlaceholder"
            ref="selectOther"
            @closed="selectOtherClosed"
        />

        <confirm-refer
            ref="confirmRefer"
            :patient="form.patient.name"
            :version="contents.criterias.version"
            @confirmed="criteriaConfirmed"
        />
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import lodashGet from 'lodash/get';
import Layout from '@/Components/Layouts/Layout';
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormDatetime from '@/Components/Controls/FormDatetime';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import FormSelectOther from '@/Components/Controls/FormSelectOther';
import FormTextarea from '@/Components/Controls/FormTextarea';
import ImageUploader from '@/Components/Controls/ImageUploader';
import ConfirmRefer from '@/Components/Forms/ConfirmRefer';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import AlphanumericReader from '@/Components/Controls/AlphanumericReader';
import Icon from '@/Components/Helpers/Icon';
export default {
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
        FormSelect,
        FormSelectOther,
        FormTextarea,
        ImageUploader,
        ConfirmRefer,
        SpinnerButton,
        AlphanumericReader,
        Icon,
    },
    layout: Layout,
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
    watch: {
        'form.no_admit': {
            handler(val) {
                if (val) {
                    if (! this.form.symptoms.asymptomatic_symptom) {
                        this.form.symptoms.asymptomatic_symptom = true;
                    }

                    if (! this.form.diagnosis.asymptomatic_diagnosis) {
                        this.form.diagnosis.asymptomatic_diagnosis = true;
                    }
                }
            }
        },
        'form.symptoms.asymptomatic_symptom': {
            handler(val) {
                if (!val) {
                    this.form.symptoms.asymptomatic_detail = null;
                    this.form.diagnosis.asymptomatic_diagnosis = false;
                    return;
                }

                // reset others
                Object.keys(this.form.symptoms).map(symptom => {
                    if (symptom !== 'asymptomatic_symptom') {
                        this.form.symptoms[symptom] = typeof this.form.symptoms[symptom] === 'boolean' ? false : null;
                    }
                });
                this.form.diagnosis.asymptomatic_diagnosis = true;
            }
        },
        'form.symptoms': {
            handler(val) {
                let symptomsChecked = Object.keys(val).filter(symptom => val[symptom] === true);
                if (!symptomsChecked.length) {
                    return;
                }

                if (symptomsChecked.indexOf('diarrhea') !== -1 && !this.form.diagnosis.gastroenteritis) {
                    this.form.diagnosis.gastroenteritis = true;
                }

                if (!this.form.diagnosis.uri && !this.form.diagnosis.asymptomatic_diagnosis && (symptomsChecked.length > 1 || symptomsChecked[0] !== 'diarrhea')) {
                    this.form.diagnosis.uri = true;
                }
            },
            deep: true
        },
        'form.diagnosis.asymptomatic_diagnosis': {
            handler(val) {
                if (!val) {
                    this.form.symptoms.asymptomatic_symptom = false;
                    return;
                }
                // reset others
                Object.keys(this.form.diagnosis).map(field => {
                    if (field !== 'asymptomatic_diagnosis') {
                        this.form.diagnosis[field] = typeof this.form.diagnosis[field] === 'boolean' ? false : null;
                    }
                });
                this.form.symptoms.asymptomatic_symptom = true;
            }
        },
        'form.diagnosis.uri': {
            handler(val) {
                if (!val) {
                    this.form.diagnosis.date_uri = null;
                }
            }
        },
        'form.diagnosis.pneumonia': {
            handler(val) {
                if (!val) {
                    this.form.diagnosis.date_pneumonia = null;
                }
            }
        },
        'form.adr.no_adr': {
            handler (val) {
                if (val) {
                    this.form.adr.adr_detail = null;
                }
            },
        },
        'form.comorbids.no_comorbids': {
            handler(val) {
                if (!val) {
                    return;
                }
                // reset others
                Object.keys(this.form.comorbids).map(field => {
                    if (field !== 'no_comorbids') {
                        this.form.comorbids[field] = typeof this.form.comorbids[field] === 'boolean' ? false : null;
                    }
                });
            }
        },
        'form.patient.insurance': {
            handler (val) {
                if (val !== 'other') {
                    return;
                }

                this.selectOtherPlaceholder = 'ระบุสิทธิ์อื่นๆ';
                this.configsRef = 'insurances';
                this.formSelectRef = 'insurance';
                this.$refs.selectOther.open();
            }
        },
        'form.patient.ward': {
            handler (val) {
                if (val !== 'other') {
                    return;
                }

                this.selectOtherPlaceholder = 'ระบุหอผู้ป่วยอื่นๆ';
                this.configsRef = 'wards';
                this.formSelectRef = 'ward';
                this.$refs.selectOther.open();
            }
        },
        'form.patient.meal': {
            handler (val) {
                if (val !== 'other') {
                    return;
                }

                this.selectOtherPlaceholder = 'ระบุอาหารอื่นๆ';
                this.configsRef = 'meals';
                this.formSelectRef = 'meal';
                this.$refs.selectOther.open();
            }
        },
    },
    created () {
        if (this.form.patient.insurance && !this.configs.insurances.includes(this.form.patient.insurance)) {
            this.configs.insurances.push(this.form.patient.insurance);
        }

        if (this.form.patient.meal && !this.configs.meals.includes(this.form.patient.meal)) {
            this.configs.meals.push(this.form.patient.meal);
        }

        if (this.form.patient.ward && !this.configs.wards.includes(this.form.patient.ward)) {
            this.configs.wards.push(this.form.patient.ward);
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
    methods: {
        selectOtherClosed (val) {
            if (! val) {
                this.$refs[this.formSelectRef].setOther('');
                return;
            }

            this.configs[this.configsRef].push(val);
            this.$refs[this.formSelectRef].setOther(val);
        },
        autosave (field) {
            if (this.form.submitted) {
                return;
            }

            this.$nextTick(() => {
                let form = {};
                if (field.indexOf('symptoms') !== -1 || field.indexOf('diagnosis') !== -1) {
                    form['contents->symptoms'] = this.form.symptoms;
                    form['contents->diagnosis'] = this.form.diagnosis;
                } else if (field.indexOf('adr') !== -1) {
                    form['contents->adr'] = this.form.adr;
                } else if (field.indexOf('comorbids') !== -1) {
                    form['contents->comorbids'] = this.form.comorbids;
                } else if (field.indexOf('treatments') !== -1) {
                    form['contents->treatments'] = this.form.treatments;
                } else if (field === 'no_admit') {
                    form['contents->no_admit'] = lodashGet(this.form, field);
                    if (form['contents->no_admit']) {
                        form['contents->symptoms'] = this.form.symptoms;
                        form['contents->diagnosis'] = this.form.diagnosis;
                    }
                } else {
                    form['contents->' + (field.split('.').join('->'))] = lodashGet(this.form, field);
                }
                window.axios.patch(this.configs.patchEndpoint, form);
                if (field === 'patient.hn') {
                    this.updatePatient();
                }
            });
        },
        confirm () {
            this.form
                .transform(data => ({...data, remember: 'on'}))
                .post(`${this.baseUrl}/refer-cases/${this.configs.note_id}`, {
                    onSuccess: () => {
                        if (Object.keys(this.form.errors).length === 0 && ! this.form.criterias.diagnosis && !this.form.submitted) {
                            this.$refs.confirmRefer.open();
                        }
                    },
                    onFinish: () => this.form.processing = false,
                    replace: true,
                });
        },
        criteriaConfirmed (criterias) {
            this.form.criterias = {...criterias};
            this.confirm();
        },
        updatePatient () {
            if (this.form.patient.hn && this.form.patient.hn.length === 8 && Number.isInteger(parseInt(this.form.patient.hn))) {
                window.axios
                    .post(`${this.baseUrl}/front-api/patient`, { hn: this.form.patient.hn })
                    .then(response => {
                        if (response.data.found) {
                            this.form.patient.name = response.data.patient_name;
                        } else {
                            this.form.patient.name = 'ไม่พบ HN นี้ในระบบ';
                        }
                        this.autosave('patient.name');
                    });
            }
        }
    }
};
</script>
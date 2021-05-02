<template>
    <div class="lg:max-w-3xl lg:mx-auto">
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
                @autosave="autosave('patient.sat_code')"
            />
            <form-input
                class="mt-2"
                name="hn"
                type="tel"
                label="HN ศิริราช"
                v-model="form.patient.hn"
                @autosave="autosave('patient.hn')"
            />
            <form-input
                class="mt-2"
                name="name"
                label="ชื่อผู้ป่วย"
                v-model="form.patient.name"
                @autosave="autosave('patient.name')"
            />
            <form-select
                class="mt-2"
                label="สิทธิ์การรักษา"
                v-model="form.patient.insurance"
                name="insurance"
                :options="configs.insurances"
                :allow-other="true"
                ref="insurance"
                @autosave="autosave('patient.insurance')"
            />
            <form-datetime
                class="mt-2"
                label="วันแรกที่มีอาการ"
                v-model="form.patient.date_symptom_start"
                name="date_symptom_start"
                @autosave="autosave('patient.date_symptom_start')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ตรวจพบเชื้อ"
                v-model="form.patient.date_covid_infected"
                name="date_covid_infected"
                @autosave="autosave('patient.date_covid_infected')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่รับไว้ในโรงพยาบาล"
                v-model="form.patient.date_admit_origin"
                name="date_admit_origin"
                @autosave="autosave('patient.date_admit_origin')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ส่งผู้ป่วยไป Hospitel"
                v-model="form.patient.date_refer"
                name="date_refer"
                @autosave="autosave('patient.date_refer')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดนอนใน hospitel"
                v-model="form.patient.date_expect_discharge"
                name="date_quarantine_end"
                @autosave="autosave('patient.date_expect_discharge')"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน"
                v-model="form.patient.date_quarantine_end"
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
                @autosave="autosave('vital_signs.temperature_celsius')"
            />
            <form-input
                class="mt-2"
                label="Pulse (min)"
                type="tel"
                name="pulse_per_minute"
                v-model="form.vital_signs.pulse_per_minute"
                @autosave="autosave('vital_signs.pulse_per_minute')"
            />
            <form-input
                class="mt-2"
                label="RR (min)"
                type="tel"
                name="respiration_rate_per_minute"
                v-model="form.vital_signs.respiration_rate_per_minute"
                @autosave="autosave('vital_signs.respiration_rate_per_minute')"
            />
            <form-input
                class="mt-2"
                label="SBP (mmHg)"
                name="sbp"
                type="tel"
                v-model="form.vital_signs.sbp"
                @autosave="autosave('vital_signs.sbp')"
            />
            <form-input
                class="mt-2"
                label="DBP (mmHg)"
                name="dbp"
                type="tel"
                v-model="form.vital_signs.dbp"
                @autosave="autosave('vital_signs.dbp')"
            />
            <form-input
                class="mt-2"
                label="O₂ sat (% RA)"
                type="tel"
                name="o2_sat"
                v-model="form.vital_signs.o2_sat"
                @autosave="autosave('vital_signs.o2_sat')"
            />
        </div>

        <!-- symptoms -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                บันทึกอาการแสดง
            </h2>
            <form-checkbox
                class="mt-2"
                v-model="form.symptoms.asymptomatic"
                label="Asymptomatic"
                :toggler="true"
                @autosave="autosave('symptoms.asymptomatic')"
            />
            <div v-if="!form.symptoms.asymptomatic">
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
                    name="symptoms_others"
                    v-model="form.symptoms.others"
                    @autosave="autosave('symptoms.others')"
                />
            </div>
            <form-select
                v-else
                class="mt-2"
                v-model="form.symptoms.asymptomatic_detail"
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
            <form-checkbox
                class="mt-2"
                v-model="form.diagnosis.asymptomatic"
                label="Asymptomatic COVID 19 infection"
                :toggler="true"
                @autosave="autosave('diagnosis.asymptomatic')"
            />
            <div v-if="!form.diagnosis.asymptomatic">
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.uri"
                    label="COVID 19 with URI"
                    @autosave="autosave('diagnosis.uri')"
                />
                <form-datetime
                    v-if="form.diagnosis.uri"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ uri"
                    v-model="form.diagnosis.date_uri"
                    name="date_uri"
                    @autosave="autosave('diagnosis.date_uri')"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.pneumonia"
                    label="COVID 19 with Pneumonia"
                    @autosave="autosave('diagnosis.pneumonia')"
                />
                <form-datetime
                    v-if="form.diagnosis.pneumonia"
                    class="mt-2"
                    label="วันที่เริ่มมีอาการ pneumonia"
                    v-model="form.diagnosis.date_pneumonia"
                    name="date_pneumonia"
                    @autosave="autosave('diagnosis.date_pneumonia')"
                />
                <form-checkbox
                    class="mt-2"
                    v-model="form.diagnosis.gastroenteritis"
                    label="COVID 19 with Gastroenteritis"
                    @autosave="autosave('diagnosis.gastroenteritis')"
                />
                <form-input
                    class="mt-2"
                    name="diagnosis_others"
                    placeholder="วินิจฉัยอื่นๆ"
                    v-model="form.diagnosis.others"
                    @autosave="autosave('diagnosis.others')"
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
                v-model="form.adr.none"
                label="ไม่แพ้"
                :toggler="true"
                @autosave="autosave('adr.none')"
            />
            <form-input
                v-if="!form.adr.none"
                class="mt-2"
                placeholder="ระบุชนิดของยา/อาหารที่แพ้"
                name="adr_detail"
                v-model="form.adr.detail"
                @autosave="autosave('adr.detail')"
            />
        </div>

        <!-- comorbids -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                โรคประจำตัว
            </h2>
            <form-checkbox
                class="mt-2"
                v-model="form.comorbids.none"
                label="ไม่มี"
                :toggler="true"
                @autosave="autosave('comorbids.none')"
            />
            <div v-if="!form.comorbids.none">
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
                    name="comorbids_others"
                    v-model="form.comorbids.others"
                    @autosave="autosave('comorbids.others')"
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
                @autosave="autosave('patient.meal')"
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
                @autosave="autosave('treatments.temperature_per_day')"
            />
            <form-select
                class="mt-2"
                label="Oxygen sat RA"
                name="oxygen_sat_RA"
                v-model="form.treatments.oxygen_sat_RA"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
                @autosave="autosave('treatments.oxygen_sat_RA')"
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
                    name="date_start_favipiravir"
                    @autosave="autosave('treatments.date_start_favipiravir')"
                />
                <form-datetime
                    class="mt-2"
                    label="Favipiravir (กำหนดครบวันที่)"
                    v-model="form.treatments.date_stop_favipiravir"
                    name="date_stop_favipiravir"
                    @autosave="autosave('treatments.date_stop_favipiravir')"
                />
            </template>
            <form-datetime
                class="mt-2"
                label="นัดมาทำ NP swab ซ้ำ วันที่"
                v-model="form.treatments.date_repeat_NP_swap"
                name="date_repeat_NP_swap"
                @autosave="autosave('treatments.date_repeat_NP_swap')"
            />
            <small class="text-md text-thick-theme-light italic">๏ กรณีบุคลากรทางการแพทย์ศิริราช</small>
        </div>

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
            />
            <image-uploader
                class="mt-2"
                label="2. ใบรายงานผล COVID"
                :filename="form.uploads.lab"
                name="contents->uploads->lab"
                :note-id="configs.note_id"
                v-model="form.uploads.lab"
            />
            <image-uploader
                class="mt-2"
                v-if="$page.props.user.center !== 'ศิริราช'"
                label="3. รูปถ่ายหน้าบัตรประชาชน (หากยังไม่มี HN ศิริราช)"
                :filename="form.uploads.id_document"
                name="contents->uploads->id_document"
                :note-id="configs.note_id"
                v-model="form.uploads.id_document"
            />
        </div>

        <button
            class="btn btn-dark w-full mt-4 sm:mt-6 md:mt-12"
            @click="confirm"
        >
            ยืนยันการส่งต่อผู้ป่วย
        </button>

        <form-select-other
            :placeholder="selectOtherPlaceholder"
            ref="selectOther"
            @closed="selectOtherClosed"
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
import ImageUploader from '@/Components/Controls/ImageUploader';
import Icon from '@/Components/Helpers/Icon';
export default {
    layout: Layout,
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
        FormSelect,
        FormSelectOther,
        ImageUploader,
        Icon,
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
            film: useForm({
                file: null,
                name: 'contents->uploads->film',
            }),
        };
    },
    watch: {
        'form.symptoms.asymptomatic': {
            handler(val) {
                if (!val) {
                    this.form.symptoms.asymptomatic_detail = null;
                    this.form.diagnosis.asymptomatic = false;
                    return;
                }

                // reset others
                Object.keys(this.form.symptoms).map(symptom => {
                    if (symptom !== 'asymptomatic') {
                        this.form.symptoms[symptom] = typeof this.form.symptoms[symptom] === 'boolean' ? false : null;
                    }
                });
                this.form.diagnosis.asymptomatic = true;
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

                if (!this.form.diagnosis.uri && (symptomsChecked.length > 1 || symptomsChecked[0] !== 'diarrhea')) {
                    this.form.diagnosis.uri = true;
                }
            },
            deep: true
        },
        'form.diagnosis.asymptomatic': {
            handler(val) {
                if (!val) {
                    this.form.symptoms.asymptomatic = false;
                    return;
                }
                // reset others
                Object.keys(this.form.diagnosis).map(field => {
                    if (field !== 'asymptomatic') {
                        this.form.diagnosis[field] = typeof this.form.diagnosis[field] === 'boolean' ? false : null;
                    }
                });
                this.form.symptoms.asymptomatic = true;
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
                    this.form.diagnosis.pneumonia = null;
                }
            }
        },
        'form.adr.none': {
            handler (val) {
                if (val) {
                    val.detail = null;
                }
            },
        },
        'form.comorbids.none': {
            handler(val) {
                if (!val) {
                    return;
                }
                // reset others
                Object.keys(val).map(field => {
                    if (field !== 'none') {
                        val[field] = typeof val[field] === 'boolean' ? false : null;
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
        }
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
            this.$nextTick(() => {
                let form = {};
                if (field.indexOf('symptoms') !== -1 || field.indexOf('diagnosis') !== -1) {
                    form['contents->symptoms'] = this.form.symptoms;
                    form['contents->diagnosis'] = this.form.diagnosis;
                } else {
                    form['contents->' + (field.split('.').join('->'))] = lodashGet(this.form, field);
                }
                window.axios.patch(this.configs.patchEndpoint, form);
            });
        },
        confirm () {
            this.form
                .transform(data => ({...data, remember: 'on'}))
                .patch(`${this.baseUrl}/refer-cases/${this.configs.note_id}`, {
                    onFinish: () => console.log(this.$page.props.errors)
                });
        }
    }
};
</script>
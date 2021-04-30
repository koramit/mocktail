<template>
    <div class="lg:max-w-3xl lg:mx-auto">
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
                label="วันที่ครบกำหนดกักตัว"
                v-model="form.patient.date_quarantine_end"
                name="date_quarantine_end"
            />
        </div>

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

        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                บันทึกอาการแสดง
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="form.symptoms.asymptomatic"
                label="Asymptomatic"
                :toggler="true"
            />

            <div v-if="!form.symptoms.asymptomatic">
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
                    name="symptoms_others"
                    v-model="form.symptoms.others"
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

        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                วินิจฉัย
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="form.diagnosis.asymptomatic"
                label="Asymptomatic COVID 19 infection"
                :toggler="true"
            />

            <div v-if="!form.diagnosis.asymptomatic">
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
                    name="diagnosis_others"
                    placeholder="วินิจฉัยอื่นๆ"
                    v-model="form.diagnosis.others"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ประวัติแพ้ยา/อาหาร
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="form.adr.none"
                label="ไม่แพ้"
                :toggler="true"
            />

            <form-input
                v-if="!form.adr.none"
                class="mt-2"
                placeholder="ระบุชนิดของยา/อาหารที่แพ้"
                name="adr_detail"
                v-model="form.adr.detail"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                โรคประจำตัว
            </h2>

            <form-checkbox
                class="mt-2"
                v-model="form.comorbids.none"
                label="ไม่มี"
                :toggler="true"
            />

            <div v-if="!form.comorbids.none">
                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.dm"
                    label="เบาหวาน"
                />

                <form-checkbox
                    class="mt-2"
                    v-model="form.comorbids.ht"
                    label="ความดัน"
                />

                <form-input
                    class="mt-2"
                    placeholder="ระบุโรคประจำตัวอื่นๆ"
                    name="comorbids_others"
                    v-model="form.comorbids.others"
                />
            </div>
        </div>

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
                name="oxygen_sat_RA"
                v-model="form.treatments.oxygen_sat_RA"
                :options="['วันละครั้ง', 'วันละสองครั้งเช้าเย็น']"
            />

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

            <form-datetime
                class="mt-2"
                label="นัดมาทำ NP swab ซ้ำ วันที่"
                v-model="form.treatments.date_repeat_NP_swap"
                name="date_repeat_NP_swap"
            />
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                เอกสารแนบ
            </h2>

            <label class="form-label">1. รูปถ่ายหน้าบัตรประชาชน</label>
            <input
                type="file"
                id="imageFile"
                capture="environment"
                accept="image/*"
            >
            <label class="form-label mt-2">2. ใบรายงานผล COVID</label>
            <input
                type="file"
                id="imageFile"
                capture="environment"
                accept="image/*"
            >
            <label class="form-label mt-2">3. Film Chest ล่าสุด</label>
            <input
                type="file"
                id="imageFile"
                capture="environment"
                accept="image/*"
            >
        </div>

        <button class="btn btn-bitter w-full mt-4 sm:mt-6 md:mt-12">
            บันทึกร่าง
        </button>

        <button class="btn btn-dark w-full mt-4 sm:mt-6 md:mt-12">
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
import Layout from '@/Components/Layouts/Layout';
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormDatetime from '@/Components/Controls/FormDatetime';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import FormSelectOther from '@/Components/Controls/FormSelectOther';
export default {
    layout: Layout,
    components: {
        FormCheckbox,
        FormDatetime,
        FormInput,
        FormSelect,
        FormSelectOther,
    },
    props: {
        contents: { type: Object, required: true },
        formConfigs: { type: Object, required: true }
    },
    watch: {
        'form.symptoms': {
            handler(val) {
                if (val.asymptomatic) {
                    // reset others
                    Object.keys(val).map(symptom => {
                        if (symptom !== 'asymptomatic') {
                            val[symptom] = typeof val[symptom] === 'boolean' ? false : '';
                        }
                    });
                    return;
                } else {
                    val.asymptomatic_detail = '';
                }

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
        'form.diagnosis': {
            handler(val) {
                if (val.asymptomatic) {
                    // reset others
                    Object.keys(val).map(field => {
                        if (field !== 'asymptomatic') {
                            val[field] = typeof val[field] === 'boolean' ? false : '';
                        }
                    });
                    return;
                }

                if (val.uri === false) {
                    val.date_uri = '';
                }

                if (val.pneumonia === false) {
                    val.date_pneumonia = '';
                }
            },
            deep: true
        },
        'form.adr': {
            handler (val) {
                if (val.none) {
                    val.detail = '';
                }
            },
            deep: true
        },
        'form.comorbids': {
            handler(val) {
                if (val.none) {
                    // reset others
                    Object.keys(val).map(field => {
                        if (field !== 'none') {
                            val[field] = typeof val[field] === 'boolean' ? false : '';
                        }
                    });
                }
            },
            deep: true
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
        document.title = 'Refer note';
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
    }
};
</script>
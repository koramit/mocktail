<template>
    <div>
        <!-- admission data -->
        <div class="bg-white rounded shadow-sm p-4  mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ข้อมูลการแอดมิท
            </h2>
            <form-input
                class="mt-2"
                name="hn"
                label="HN"
                v-model="form.admission.hn"
                :readonly="true"
            />
            <form-input
                class="mt-2"
                name="name"
                label="ชื่อผู้ป่วย"
                v-model="form.admission.name"
                :readonly="true"
            />
            <form-input
                class="mt-2"
                name="an"
                label="AN"
                v-model="form.admission.an"
                :readonly="true"
            />
            <form-input
                class="mt-2"
                name="encountered_at"
                label="วันเวลาที่แอดมิท"
                v-model="form.admission.encountered_at"
                :readonly="true"
            />
        </div>
        <!-- preliminary data -->
        <div class="bg-white rounded shadow-sm p-4  mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ข้อมูลจากใบส่งตัว
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
                :readonly="true"
            />
            <form-select
                class="mt-2"
                label="สิทธิ์การรักษา"
                v-model="form.patient.insurance"
                name="insurance"
                :options="configs.insurances"
                :disabled="true"
            />
            <form-datetime
                v-if="!form.no_admit"
                class="mt-2"
                label="วันแรกที่มีอาการ"
                v-model="form.patient.date_symptom_start"
                name="date_symptom_start"
                :disabled="true"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ตรวจพบเชื้อ"
                v-model="form.patient.date_covid_infected"
                name="date_covid_infected"
                :disabled="true"
            />
            <form-datetime
                v-if="!form.no_admit"
                class="mt-2"
                label="วันที่รับไว้ในโรงพยาบาล"
                v-model="form.patient.date_admit_origin"
                name="date_admit_origin"
                :disabled="true"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ส่งผู้ป่วยไป Hospitel"
                v-model="form.patient.date_refer"
                name="date_refer"
                :disabled="true"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดนอนใน hospitel"
                v-model="form.patient.date_expect_discharge"
                name="date_quarantine_end"
                :disabled="true"
            />
            <form-datetime
                class="mt-2"
                label="วันที่ครบกำหนดกำหนดกักตัวต่อที่บ้าน"
                v-model="form.patient.date_quarantine_end"
                name="date_quarantine_end"
                :disabled="true"
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
                        <!-- <small class="text-md text-thick-theme-light italic">๏ กรณีมีอาการหลัง admit แล้ว ให้ลงวันที่ตรวจพบเชื้อแทน</small> -->
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
                        <!-- <small class="text-md text-thick-theme-light italic">๏ กรณีมีอาการหลัง admit แล้ว ให้ลงวันที่ตรวจพบเชื้อแทน</small> -->
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
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            form: useForm({...this.contents}),
            configs: {...this.formConfigs},
            selectOtherPlaceholder: '',
            otherItem: '',
            otherItemAdded: false,
            criterias: null,
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

                if (!this.form.diagnosis.uri && (symptomsChecked.length > 1 || symptomsChecked[0] !== 'diarrhea')) {
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
            });
        },
        confirm () {
            this.form
                .transform(data => ({...data, remember: 'on', criterias: this.criterias}))
                .post(`${this.baseUrl}/refer-cases/${this.configs.note_id}`, {
                    onSuccess: () => {
                        if (Object.keys(this.form.errors).length === 0 && !this.criterias && !this.form.submitted) {
                            this.$refs.confirmRefer.open();
                            this.criterias = null;
                        }
                    },
                    replace: true,
                });
        },
    }
};
</script>
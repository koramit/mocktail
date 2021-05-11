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
            <form-input
                class="mt-2"
                name="dismissed_at"
                label="วันเวลาที่จำหน่าย"
                v-model="form.admission.dismissed_at"
                :readonly="true"
            />
            <form-input
                class="mt-2"
                name="length_of_stay"
                label="จำนวนวันนอน"
                v-model="form.admission.length_of_stay"
                :readonly="true"
            />
            <form-select
                class="mt-2"
                label="สถานะ"
                name="discharge_status"
                v-model="form.discharge.discharge_status"
                :error="form.errors.discharge_status"
                :options="configs.discharge_status"
                @autosave="autosave('discharge.discharge_status')"
            />
            <form-select
                class="mt-2"
                label="ประเภท"
                name="discharge_type"
                v-model="form.discharge.discharge_type"
                :error="form.errors.discharge_type"
                :options="configs.discharge_type"
                @autosave="autosave('discharge.discharge_type')"
            />
            <form-input
                v-if="form.discharge.discharge_type === 'BY REFER'"
                class="mt-2"
                label="โรงพยาบาลที่ส่งไป"
                name="refer_to"
                v-model="form.discharge.refer_to"
                :error="form.errors.refer_to"
                @autosave="autosave('discharge.refer_to')"
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

        <!-- complications -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                ภาวะแทรกซ้อน
            </h2>
            <small
                class="my-t text-red-700 text-sm"
                v-if="form.errors.complications"
            >{{ form.errors.complications }}</small>
            <form-checkbox
                class="mt-2"
                v-model="form.complications.no_complications"
                label="ไม่มี"
                :toggler="true"
                @autosave="autosave('complications.no_complications')"
            />
            <div v-if="!form.complications.no_complications">
                <form-checkbox
                    v-for="(complication, key) in configs.complications"
                    :key="key"
                    class="mt-2"
                    v-model="form.complications[complication.name]"
                    :label="complication.label"
                    @autosave="autosave(`complications.${complication.name}`)"
                />
                <form-select
                    v-if="form.complications.desaturation"
                    class="mt-2"
                    name="desaturation_specific"
                    v-model="form.complications.desaturation_specific"
                    :error="form.errors.desaturation_specific"
                    :options="['Desaturation at rest', 'Desaturation on excertion']"
                    @autosave="autosave('complications.desaturation_specific')"
                />
                <form-input
                    class="mt-2"
                    placeholder="ระบุภาวะแทรกซ้อนอื่นๆ"
                    name="other_complications"
                    v-model="form.complications.other_complications"
                    @autosave="autosave('complications.other_complications')"
                />
            </div>
        </div>

        <!-- non OR procedures -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                Non-OR procedures
            </h2>
            <form-checkbox
                class="mt-2"
                v-model="form.non_OR_procedures.no_non_OR_procedures"
                label="ไม่มี"
                :toggler="true"
                @autosave="autosave('non_OR_procedures.no_non_OR_procedures')"
            />
            <div v-if="!form.non_OR_procedures.no_non_OR_procedures">
                <form-input
                    class="mt-2"
                    placeholder="ระบุรายละเอียด"
                    name="non_OR_procedures_detail"
                    v-model="form.non_OR_procedures.non_OR_procedures_detail"
                    :error="form.errors.non_OR_procedures_detail"
                    @autosave="autosave('non_OR_procedures.non_OR_procedures_detail')"
                />
            </div>
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

        <!-- symptoms -->
        <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
            <h2 class="font-semibold text-thick-theme-light">
                อาการแสดงวันจำหน่าย
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

        <template v-if="['BY REFER', 'BY ESCAPED'].indexOf(form.discharge.discharge_type) === -1">
            <!-- problem list -->
            <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
                <h2 class="font-semibold text-thick-theme-light">
                    ปัญหาที่ต้องดูแลต่อเนื่อง
                </h2>
                <small
                    class="my-t text-red-700 text-sm"
                    v-if="form.errors.problem_list"
                >{{ form.errors.problem_list }}</small>
                <form-checkbox
                    class="mt-2"
                    v-model="form.problem_list.no_problem_list"
                    label="ไม่มี"
                    :toggler="true"
                    @autosave="autosave('problem_list.no_problem_list')"
                />
                <div v-if="!form.problem_list.no_problem_list">
                    <form-checkbox
                        class="mt-2"
                        v-model="form.problem_list.quarantine"
                        label="ต้องกัก🧩ที่บ้าน"
                        @autosave="autosave('problem_list.quarantine')"
                    />
                    <form-input
                        class="mt-2"
                        placeholder="ระบุโรคปัญหาอื่นๆ"
                        name="other_problem_list"
                        v-model="form.problem_list.other_problem_list"
                        @autosave="autosave('problem_list.other_problem_list')"
                    />
                </div>
            </div>

            <!-- appointment -->
            <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
                <h2 class="font-semibold text-thick-theme-light">
                    วันนัดครั้งต่อไป
                </h2>
                <small
                    class="my-t text-red-700 text-sm"
                    v-if="form.errors.appointment"
                >{{ form.errors.appointment }}</small>
                <form-checkbox
                    class="mt-2"
                    v-model="form.appointment.no_appointment"
                    label="ไม่นัด"
                    :toggler="true"
                    @autosave="autosave('appointment.no_appointment')"
                />
                <div v-if="!form.appointment.no_appointment">
                    <form-datetime
                        class="mt-2"
                        label="วันที่นัด"
                        v-model="form.appointment.date_appointment"
                        :error="form.errors.date_appointment"
                        name="date_appointment"
                        @autosave="autosave('appointment.date_appointment')"
                    />
                    <form-input
                        class="mt-2"
                        label="สถานที่นัด"
                        name="appointment_at"
                        v-model="form.appointment.appointment_at"
                        :error="form.errors.appointment_at"
                        @autosave="autosave('appointment.appointment_at')"
                    />
                </div>
            </div>

            <!-- repeat_NP_swab -->
            <div class="bg-white rounded shadow-sm p-4 mt-4 sm:mt-6 md:mt-12">
                <h2 class="font-semibold text-thick-theme-light">
                    นัดมาทำ NP swab ซ้ำ
                </h2>
                <small
                    class="my-t text-red-700 text-sm"
                    v-if="form.errors.repeat_NP_swab"
                >{{ form.errors.repeat_NP_swab }}</small>
                <form-checkbox
                    class="mt-2"
                    v-model="form.repeat_NP_swab.no_repeat_NP_swab"
                    label="ไม่นัด"
                    :toggler="true"
                    @autosave="autosave('repeat_NP_swab.no_repeat_NP_swab')"
                />
                <div v-if="!form.repeat_NP_swab.no_repeat_NP_swab">
                    <form-datetime
                        class="mt-2"
                        label="วันที่"
                        v-model="form.repeat_NP_swab.date_repeat_NP_swab"
                        :error="form.errors.date_repeat_NP_swab"
                        name="date_repeat_NP_swab"
                        @autosave="autosave('repeat_NP_swab.date_repeat_NP_swab')"
                    />
                </div>
            </div>
        </template>

        <spinner-button
            class="btn btn-bitter w-full mt-4 sm:mt-6 md:mt-12"
            :class="{
                'btn-dark': !form.submitted,
                'btn-bitter': form.submitted,
            }"
            @click="confirm"
            :spin="form.processing"
        >
            {{ form.submitted ? 'ปรับปรุง':'เผยแพร่โน๊ต' }}
        </spinner-button>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import lodashGet from 'lodash/get';
import Layout from '@/Components/Layouts/Layout';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormDatetime from '@/Components/Controls/FormDatetime';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
export default {
    components: { FormInput, FormSelect, FormCheckbox, FormDatetime, SpinnerButton },
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
        'form.discharge.discharge_status': {
            handler (val) {
                if (val === 'NOT IMPROVED') {
                    this.form.diagnosis.asymptomatic_diagnosis = false;
                }
            }
        },
        'form.discharge.discharge_type': {
            handler (val) {
                if (val === 'BY REFER') {
                    this.form.discharge.refer_to = this.configs.center;
                } else {
                    this.form.discharge.refer_to = null;
                }
            }
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
        'form.complications.no_complications': {
            handler (val) {
                if (val) {
                    // reset other
                    Object.keys(this.form.complications).map(field => {
                        if (field !== 'no_complications') {
                            this.form.complications[field] = typeof this.form.complications[field] === 'boolean' ? false : null;
                        }
                    });
                }
            }
        },
        'form.complications.desaturation': {
            handler (val) {
                if (!val) {
                    this.form.complications.desaturation_specific = null;
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
    },
    methods: {
        autosave (field) {
            if (this.form.submitted) {
                return;
            }

            this.$nextTick(() => {
                let form = {};
                if (field.indexOf('discharge') !== -1) {
                    form['contents->discharge'] = this.form.discharge;
                    form['contents->diagnosis'] = this.form.diagnosis;
                } else if (field.indexOf('diagnosis') !== -1 || field.indexOf('symptoms') !== -1) {
                    form['contents->diagnosis'] = this.form.diagnosis;
                    form['contents->symptoms'] = this.form.symptoms;
                } else if (field.indexOf('complications') !== -1) {
                    form['contents->complications'] = this.form.complications;
                } else {
                    form['contents->' + (field.split('.').join('->'))] = lodashGet(this.form, field);
                }

                window.axios.patch(this.configs.patchEndpoint, form);
            });
        },
        confirm () {
            this.form
                .transform(data => ({...data, remember: 'on'}))
                .post(`${this.baseUrl}/discharge-summary-notes/${this.configs.note_id}`, {
                    onFinish: () => this.form.processing = false,
                    replace: true,
                });
        },
    }
};
</script>
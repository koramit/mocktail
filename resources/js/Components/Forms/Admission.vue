<template>
    <teleport to="body">
        <modal
            ref="modal"
            width-mode="form-cols-1"
            @closed="$emit('closed')"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    รับแอดมิทที่ {{ wardName }}
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
                    <template v-if="state === 'need_sat_code'">
                        <form-input
                            v-model="form.sat_code"
                            label="sat code"
                            name="sat_code"
                            :readonly="true"
                        />
                        <form-input
                            v-model="form.nation"
                            label="สัญชาติ"
                            name="nation"
                            :readonly="true"
                        />
                    </template>
                    <form-input
                        v-model="form.hn"
                        label="hn"
                        name="hn"
                        type="tel"
                        :error="$page.props.errors.hn"
                    />
                    <form-input
                        v-model="form.refer_name"
                        label="ชื่อในใบส่งตัว"
                        name="refer_name"
                        :readonly="true"
                    />
                    <form-input
                        v-if="form.name"
                        v-model="form.name"
                        label="ชื่อในระบบเวชระเบียน"
                        name="name"
                        :readonly="true"
                    />
                    <form-input
                        v-if="state === 'try_later'"
                        v-model="try_later"
                        label="ข้อมูลแอดมิทล่าสุดในระบบเวชระเบียน"
                        name="try_later"
                        :readonly="true"
                    />
                    <template v-if="state && state === 'confirm'">
                        <form-input
                            v-model="form.an"
                            label="an"
                            name="an"
                            type="tel"
                            :readonly="true"
                            :error="$page.props.errors.an"
                        />
                        <form-input
                            v-model="form.room_number"
                            label="หมายเลขห้อง"
                            name="room_number"
                            :error="$page.props.errors.room_number"
                        />
                    </template>
                </div>
            </template>
            <template #footer>
                <spinner-button
                    :spin="busy"
                    class="btn-dark w-full mt-6"
                    v-if="state === 'confirm' || state === null"
                    @click="perform"
                    :disabled="!form.hn || (state && !form.room_number)"
                >
                    {{ !state ? 'ค้น HN':'ยืนยัน' }}
                </spinner-button>
                <p
                    v-else-if="state === 'try_later'"
                    class="text-sm text-yellow-400 w-full text-center"
                >
                    <span class="font-semibold">HN</span> นี้ยังไม่มีข้อมูลแอดมิทใน <span class="font-semibold">{{ wardNameCheck }}</span><br class="sm:hidden"> โปรดลองใหม่ภายหลัง
                </p>
                <p
                    v-else-if="state === 'need_sat_code'"
                    class="text-sm text-yellow-400 w-full text-center"
                >
                    จำเป็นต้องมี <span class="font-semibold">SAT CODE</span> สำหรับชาวต่างชาติ โปรดแจ้งผู้ส่งเคส
                </p>
                <icon
                    v-if="state !== null && state !== 'confirm' && busy "
                    class="w-4 h-4 animate-spin"
                    name="circle-notch"
                />
            </template>
        </modal>
    </teleport>
</template>
<script>
import { useForm } from '@inertiajs/inertia-vue3';
import FormInput from '@/Components/Controls/FormInput';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import Modal from '@/Components/Helpers/Modal';
import Icon from '@/Components/Helpers/Icon';
export default {
    emits: ['closed'],
    components: { FormInput, Modal, Icon, SpinnerButton },
    data () {
        return {
            form: {
                id: null,
                hn: null,
                name: null,
                refer_name: null,
                an: null,
                room_number: null,
            },
            try_later: null,
            busy: false,
            needConfirm: false,
            state: null,
            wardNameCheck: null,
            wardName: null,
        };
    },
    methods: {
        open (id, hn, referName, satCode, ward) {
            this.state = null;
            this.form = {};
            this.form.id = id;
            this.form.refer_name = referName;
            if (! hn) {
                this.$refs.modal.open();
                return;
            }
            this.busy = true;
            this.form.hn = hn;
            this.form.sat_code = satCode;
            this.wardName = ward;
            this.wardNameCheck = ward === 'Baiyoke' ? 'ศูนย์เฉพาะกิจ (ใบหยก1)':'ศูนย์เฉพาะกิจ(ริเวอร์ไซด์)';
            this.checkAdmission();
            this.$refs.modal.open();
        },
        perform () {
            if (!this.state) {
                // sarch hn
                this.checkAdmission();
            } else if (this.state === 'need_sat_code') {
                //
            } else if (this.state === 'confirm') {
                this.busy = true;
                let form = useForm({...this.form, remember: 'on'});
                form.post(`${this.$page.props.app.baseUrl}/admissions`, {
                    onSuccess: () => this.$refs.modal.close(),
                    onFinish: () => this.busy = false,
                });
            }
        },
        checkAdmission () {
            this.busy = true;
            window.axios
                .post(`${this.$page.props.app.baseUrl}/front-api/patient-rencently-admission`, this.form)
                .then((response) => {
                    if (! response.data.found) {
                        this.state = 'try_later';
                        this.try_later = 'ไม่พบข้อมูลการแอดมิท';
                        if (response.data.patient.found) {
                            this.form.name = response.data.patient.patient_name;
                        }
                        return;
                    }

                    this.form.name = response.data.patient_name;
                    if (response.data.ward_name !== this.wardNameCheck) {
                        this.try_later = response.data.ward_name;
                        this.state = 'try_later';
                        return;
                    }
                    let patient = response.data.patient;
                    if (patient.nation !== 'ไทย' && (! patient.document_id || patient.document_id.length !== 13) && ! this.form.sat_code) {
                        this.form.nation = patient.nation;
                        this.state = 'need_sat_code';
                    } else {
                        this.state = 'confirm';
                    }
                    this.form.an = response.data.an;
                })
                .finally(() => this.busy = false);
        }
    }
};
</script>
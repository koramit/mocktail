<template>
    <teleport to="body">
        <!-- @opened="checkAdmission" -->
        <modal
            ref="modal"
            width-mode="form-cols-1"
            @closed="$emit('closed')"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    รับแอดมิท
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
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
                    <template v-if="state && state !== 'try_later'">
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
                    v-if="state !== 'try_later'"
                    @click="perform"
                    :disabled="!form.hn || (state && !form.room_number)"
                >
                    {{ !state ? 'ค้น HN':'ยืนยัน' }}
                </spinner-button>
                <p
                    v-else
                    class="text-sm text-yellow-400 w-full text-center"
                >
                    <span class="font-semibold">HN</span> นี้ยังไม่มีข้อมูลแอดมิทใน <span class="font-semibold">{{ wardNameCheck }}</span><br class="sm:hidden"> โปรดลองใหม่ภายหลัง
                </p>
            </template>
        </modal>
    </teleport>
</template>
<script>
import { useForm } from '@inertiajs/inertia-vue3';
import FormInput from '@/Components/Controls/FormInput';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import Modal from '@/Components/Helpers/Modal';
export default {
    emits: ['closed'],
    components: { FormInput, Modal, SpinnerButton },
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
            wardNameCheck: 'ศูนย์เฉพาะกิจ',
        };
    },
    methods: {
        open (id, hn, referName) {
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
            this.checkAdmission();
            this.$refs.modal.open();
        },
        perform () {
            if (!this.state) {
                // sarch hn
                this.checkAdmission();
            } else if (this.state === 'comfirm') {
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
                    console.log(response.data);
                    if (! response.data.found) {
                        this.state = 'try_later';
                        this.try_later = 'ไม่พบข้อมูลการแอดมิท';
                        if (response.data.patient.found) {
                            console.log('found patient');
                            this.form.name = response.data.patient.patient_name;
                        }
                        return;
                    }

                    this.form.name = response.data.patient_name;
                    if (response.data.ward_name_short !== this.wardNameCheck) {
                        this.try_later = response.data.ward_name;
                        this.state = 'try_later';
                        return;
                    }
                    this.state = 'comfirm';
                    this.form.an = response.data.an;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => this.busy = false);
        }
    }
};
</script>
<template>
    <teleport to="body">
        <Modal
            ref="modal"
            width-mode="form-cols-1"
            @closed="$emit('closed')"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    เพิ่มเคสใหม่
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
                    <FormInput
                        class="mt-2"
                        v-model="form.hn"
                        name="hn"
                        type="tel"
                        :label="'hn' + (($page.props.user.center === 'ศิริราช') ? '':' ศิริราช (ถ้ามี)')"
                        :error="form.errors.hn"
                    />
                    <FormInput
                        class="mt-2"
                        v-model="form.patient_name"
                        name="patient_name"
                        :label="'ชื่อผู้ป่วย' + (($page.props.user.center === 'ศิริราช') ? '':' (กรณียังไม่มี HN ศิริราช)')"
                        :error="form.errors.patient_name"
                        :readonly="(form.hn && true) || $page.props.user.center === 'ศิริราช'"
                        v-if="$page.props.user.center !== 'ศิริราช' || confirmed"
                    />
                    <FormSelect
                        label="ส่งตัวที่"
                        class="mt-2"
                        v-model="form.refer_type"
                        :error="form.errors.refer_type"
                        :options="$page.props.user.center === 'ศิริราช' ? ['Baiyoke', 'Riverside', 'Home Isolation'] : ['Baiyoke', 'Riverside']"
                        name="refer_type"
                    />
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <SpinnerButton
                        :spin="busy"
                        class="btn-dark w-full mt-6"
                        @click="store"
                    >
                        {{ confirmed ? 'ยืนยัน':'เพิ่ม' }}
                    </SpinnerButton>
                </div>
            </template>
        </Modal>
    </teleport>
</template>

<script>
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import Modal from '@/Components/Helpers/Modal';
export default {
    emits: ['closed'],
    components: { FormInput, FormSelect, Modal, SpinnerButton },
    data () {
        return {
            form: {
                patient_name: null,
                hn: null,
                refer_type: 'Hospitel',
                errors: {},
            },
            busy: false,
            confirmed: null,
            endpoint: `${this.$page.props.app.baseUrl}/refer-cases`,
        };
    },
    methods: {
        open () {
            this.form = {
                patient_name: null,
                hn: null,
                refer_type: null,
                errors: {},
            };
            this.form.errors = {};
            this.$refs.modal.open();
            this.confirmed = null;
        },
        store () {
            this.busy = true;
            this.form.errors = {};
            window.axios
                .post(this.endpoint, {...this.form, confirmed: this.confirmed !== null })
                .then((response) => {
                    if (response.data.url !== undefined) {
                        this.$inertia.visit(response.data.url);
                        return;
                    }
                    if (response.data.confirmed !== undefined) {
                        this.confirmed = this.form.patient_name = response.data.confirmed;
                        return;
                    }
                    this.form.errors = response.data;
                    return;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.busy = false;
                });
        }
    }
};
</script>
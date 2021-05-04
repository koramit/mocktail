<template>
    <teleport to="body">
        <modal
            ref="modal"
            @opened="$refs.satCode.focus()"
            @closed="$emit('closed')"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    เพิ่มเคสใหม่
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
                    <form-input
                        v-model="form.sat_code"
                        label="sat code"
                        name="sat_code"
                        ref="satCode"
                        :error="form.errors.sat_code"
                    />
                    <form-datetime
                        class="mt-2"
                        v-model="form.date_admit_origin"
                        name="date_admit_origin"
                        label="วันที่รับไว้ในโรงพยาบาล"
                        :error="form.errors.date_admit_origin"
                    />
                    <form-input
                        class="mt-2"
                        v-model="form.hn"
                        name="hn"
                        type="tel"
                        :label="'hn' + (($page.props.user.center === 'ศิริราช') ? '':' ศิริราช (ถ้ามี)')"
                        :error="form.errors.hn"
                    />
                    <small class="mt-2 text-sm font-semibold text-dark-theme-light">{{ form.errors.confirmed }}</small>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <spinner-button
                        :spin="form.processing"
                        class="btn-dark w-full mt-6"
                        @click="store"
                    >
                        {{ form.errors.confirmed ? 'ยืนยัน':'เพิ่ม' }}
                    </spinner-button>
                </div>
            </template>
        </modal>
    </teleport>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import FormDatetime from '@/Components/Controls/FormDatetime';
import FormInput from '@/Components/Controls/FormInput';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import Modal from '@/Components/Helpers/Modal';
export default {
    emits: ['closed'],
    components: { FormDatetime, FormInput, Modal, SpinnerButton },
    data () {
        return {
            form: useForm({
                sat_code: null,
                date_admit_origin: null,
                hn: null,
            }),
            busy: false,
            needConfirm: false,
        };
    },
    methods: {
        open () {
            this.form.reset();
            if (this.form.hasErrors) {
                this.form.clearErrors();
            }
            this.$refs.modal.open();
        },
        store () {
            this.form
                .transform(data => ({
                    ...data,
                    remember: 'on',
                    confirmed: this.form.errors.confirmed ? true:false
                }))
                .post(`${this.$page.props.app.baseUrl}/refer-cases`, {
                    onFinish: () => this.form.processing = false,
                });
        }
    }
};
</script>
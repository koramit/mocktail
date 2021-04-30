<template>
    <div class="flex flex-col justify-center items-center w-full min-h-screen my-6">
        <div
            class="flex flex-col justify-center items-center w-28 h-28 rounded-full border-soft-theme-light border-4 font-semibold bg-dark-theme-light z-10"
        >
            <div class=" text-white text-3xl">
                SiMED
            </div>
            <div class=" text-soft-theme-light text-xl italic">
                Mocktail
            </div>
        </div>
        <div class="mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow transform -translate-y-20">
            <span class="block font-semibold text-xl text-thick-theme-light mt-12 text-center">ลงทะเบียน</span>
            <span
                v-if="form.errors.login"
                class="block font-semibold text-sm text-red-400 mt-6 text-center"
            >
                {{ profile.org_id === undefined ? 'อีเมล์นี้ถูกใช้เป็นชื่อบัญชีแล้ว โปรดเลือกใหม่' : 'ชื่อบัญชีนี้ถูกใช้แล้ว โปรดติดต่อผู้ดูแลระบบ' }}
            </span>
            <div
                class="mt-6"
                v-if="profile.org_id === undefined"
            >
                <form-select
                    class="mt-2"
                    label="ศูนย์"
                    v-model="form.center"
                    name="center"
                    :error="form.errors.center"
                    :options="['ปิยมหาราชการุณย์', 'ศูนย์การแพทย์กาญจนาภิเษก']"
                />
                <form-input
                    class="mt-2"
                    name="email"
                    tyoe="email"
                    label="อีเมล์"
                    v-model="form.email"
                    :error="form.errors.email"
                    placeholder="ใช้เป็นชื่อบัญชีเข้าใช้งาน"
                />
                <form-input
                    class="mt-2"
                    name="password"
                    type="password"
                    label="รหัสผ่าน"
                    v-model="form.password"
                    :error="form.errors.password"
                />
            </div>
            <div
                class="mt-4"
                v-else
            />
            <form-input
                class="mt-2"
                name="name"
                label="นามแสดง"
                v-model="form.name"
                :error="form.errors.name"
                placeholder="ชื่อที่ใช้แสดงในระบบ (ชื่อเล่น นามแฝง)"
            />
            <form-input
                class="mt-2"
                name="full_name"
                label="ชื่อเต็ม"
                placeholder="คำนำหน้า ชื่อ สกุล"
                v-model="form.full_name"
                :error="form.errors.full_name"
                :readonly="profile.org_id !== undefined"
            />
            <form-input
                class="mt-2"
                type="tel"
                name="tel_no"
                label="หมายเลขโทรศัพท์ที่สามารถติดต่อได้"
                v-model="form.tel_no"
                :error="form.errors.tel_no"
                placeholder="ใช้เพื่อติดต่อเมื่อจำเป็นเท่านั้น"
            />
            <form-input
                class="mt-2"
                type="tel"
                name="pln"
                label="เลข ว. (กรณีแพทย์)"
                v-model="form.pln"
                :error="form.errors.pln"
                placeholder="ใช้พิมพ์ออกมาพร้อมเอกสารเวชระเบียน"
            />
            <form-checkbox
                class="mt-2"
                v-model="form.agreement_accepted"
                label="ยอมรับนโยบายความเป็นส่วนตัวและข้อตกลงการใช้งาน"
                :toggler="true"
            />
            <a
                :href="`${baseUrl}/policies`"
                class="mt-2 block text-bitter-theme-light"
                target="_blank"
            >อ่านนโยบายและข้อตกลง</a>
            <spinner-button
                :spin="form.processing"
                class="btn-dark w-full mt-4"
                @click="login"
                :disabled="!form.agreement_accepted || (profile.org_id === undefined && form.center === '')"
            >
                ลงทะเบียน
            </spinner-button>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import FormCheckbox from '@/Components/Controls/FormCheckbox.vue';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
export default {
    components: { FormCheckbox, FormInput, FormSelect, SpinnerButton },
    props: {
        profile: { type: Object, required: true }
    },
    data () {
        return {
            form: useForm({
                name: '',
                full_name: '',
                tel_no: '',
                pln: '',
                center: '',
                email: '',
                password: '',
                agreement_accepted: false,
                remember: true
            }),
            baseUrl: this.$page.props.app.baseUrl,
        };
    },
    created () {
        document.title = 'Register';
        if (this.profile.org_id !== undefined) {
            this.form.full_name = this.profile.name;
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
        login () {
            this.form
                .transform(data => ({
                    ...data,
                    login: this.profile.org_id !== undefined ? this.profile.username : data.email,
                    remember: data.remember ? 'on' : '',
                }))
                .post(`${this.baseUrl}/register`, {
                    onFinish: () => this.form.processing = false,
                });
        }
    }
};
</script>
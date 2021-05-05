<template>
    <div class="flex flex-col justify-center items-center w-full min-h-screen">
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
        <div class="mt-4 px-4 py-8 w-80 bg-white rounded shadow transform -translate-y-20">
            <span class="block font-semibold text-xl text-thick-theme-light mt-12 text-center">🧙‍♀️ Ad hoc Ward 🧙‍♂️</span>
            <form-input
                class="mt-4"
                label="ชื่อบัญชี"
                name="login"
                v-model="form.login"
                :error="form.errors.login"
            />
            <form-input
                class="mt-2"
                type="password"
                label="รหัสผ่าน"
                name="password"
                v-model="form.password"
                :error="form.errors.password"
            />
            <spinner-button
                :spin="form.processing"
                class="btn-dark w-full mt-6"
                @click="login"
            >
                เข้าใช้งาน
            </spinner-button>
            <!-- <a
                :href="`${$page.props.app.baseUrl}/login/line`"
                class="flex justify-center items-center mt-8 cursor-pointer w-full rounded-sm shadow-sm bg-line text-center text-gray-100 p-2"
            >
                <icon
                    name="line-app"
                    class="w-6 h-6 mr-2"
                />Log in with LINE
            </a> -->
            <inertia-link
                :href="`${baseUrl}/register`"
                class="mt-6 text-bitter-theme-light block"
            >
                สร้างบัญชีใหม่
            </inertia-link>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import FormInput from '@/Components/Controls/FormInput';
import SpinnerButton from '@/Components/Controls/SpinnerButton';
export default {
    components: { FormInput, SpinnerButton },
    data () {
        return {
            form: useForm({
                login: null,
                password: null,
                remember: true
            }),
            baseUrl: this.$page.props.app.baseUrl,
        };
    },
    created () {
        document.title = 'Login';
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
                    remember: data.remember ? 'on' : '',
                }))
                .post(`${this.baseUrl}/login`, {
                    replace: true,
                    onFinish: () => this.form.processing = false,
                });
        }
    }
};
</script>

<style scoped>
    .bg-line {
        background-color: #00b900;
    }
    .bg-telegram {
        background-color: #54a9eb;
    }
</style>
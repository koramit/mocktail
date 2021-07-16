<template>
    <div class="">
        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                รายการหลัก
            </h2>
            <div
                class="mt-4"
                v-if="abilities.length === 0"
            >
                กรุณาติดต่อผู้แทนศูนย์หรือผู้ดูแลระบบเพื่อเปิดสิทธิ์การใช้งาน
            </div>
            <div
                class="mt-4"
                v-else
            >
                <button
                    class="btn btn-bitter block w-full text-center"
                    v-if="abilities.includes('refer_case')"
                    @click="$refs.createCase.open()"
                >
                    เพิ่มเคสใหม่
                </button>
                <Link
                    class="btn btn-bitter block text-center"
                    :href="`${baseUrl}/refer-cases`"
                    v-if="abilities.includes('refer_case') || abilities.includes('admit_patient')"
                >
                    รายการเคส
                </Link>
                <!-- next features -->
                <!-- <Link
                    class="btn btn-bitter block text-center"
                    :href="`${baseUrl}/users`"
                    v-if="abilities.includes('grant_user')"
                >
                    เปิดสิทธิ์ผู้ใช้งาน
                </Link>
                <Link
                    class="btn btn-bitter block text-center"
                    :href="`${baseUrl}/users`"
                    v-if="abilities.includes('grant_teammate')"
                >
                    เปิดสิทธิ์เพื่อนร่วมงาน
                </Link> -->
            </div>
        </div>

        <!-- next features -->
        <!-- <div class="bg-white rounded shadow-sm p-4 mt-8 md:mt-16">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                ตั้งค่าหน้าแรก
            </h2>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-8 md:mt-16">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                เพิ่มเพื่อนใน LINE
            </h2>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-8 md:mt-16">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                แจ้งปัญหา
            </h2>
        </div> -->

        <create-case ref="createCase" />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import CreateCase from '@/Components/Forms/CreateCase';
import { Link } from '@inertiajs/inertia-vue3';
export default {
    layout: Layout,
    components: { CreateCase, Link },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
        };
    },
    created() {
        this.abilities = this.$page.props.user.abilities;
    }
};
</script>
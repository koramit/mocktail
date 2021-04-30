<template>
    <div class="p-4 sm:p-8 md:p-16 lg:py-16 lg:max-w-3xl lg:mx-auto">
        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                รายการหลัก
            </h2>
            <div class="mt-4">
                <inertia-link
                    class="btn btn-bitter block text-center"
                    href="/logout"
                    v-if="abilities.includes('refer_case')"
                >
                    เพิ่มเคส
                </inertia-link>
                <inertia-link
                    class="btn btn-bitter block text-center"
                    href="/logout"
                    v-if="abilities.includes('view_cases')"
                >
                    รายการเคส
                </inertia-link>
                <inertia-link
                    class="btn btn-bitter block text-center"
                    href="/logout"
                    v-if="abilities.includes('grant_user')"
                >
                    รายการผู้ใช้งาน
                </inertia-link>
                <inertia-link
                    class="btn btn-bitter block text-center"
                    href="/logout"
                    v-if="abilities.includes('grant_teammate')"
                >
                    รายการเพื่อนร่วมงาน
                </inertia-link>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4 mt-8 md:mt-16">
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
        </div>
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
export default {
    layout: Layout,
    created () {
        this.abilities = this.$page.props.user.abilities;
    }
};
</script>
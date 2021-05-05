<template>
    <div>
        <!-- card -->
        <div
            class="rounded bg-white shadow-sm my-1 p-1 flex"
            v-for="(referCase, key) in cases"
            :key="key"
        >
            <!-- left detail -->
            <div class="w-3/4">
                <p class="p-1 pb-0 text-thick-theme-light">
                    <span
                        class="font-semibold mr-1"
                        v-if="$page.props.user.center === 'ศิริราช'"
                    >{{ referCase.center }}</span>
                    <span
                        class="text-sm shadow-sm italic px-2 rounded-xl"
                        :class="{
                            'bg-gray-200 text-thick-theme-light': referCase.status === 'draft',
                            'bg-yellow-200 text-yellow-400': referCase.status === 'submitted',
                        }"
                    >
                        {{ referCase.status_label }}</span>
                </p>
                <p class="p-1 text-lg pt-0">
                    {{ referCase.patient_name }}
                </p>
                <p class="px-1 text-xs text-dark-theme-light italic">
                    โดย {{ referCase.referer }} เมื่อ {{ referCase.updated_at_for_humans }}
                </p>
            </div>
            <!-- right menu -->
            <div class="w-1/4 text-sm p-1 grid justify-items-center ">
                <!-- write -->
                <inertia-link
                    class="w-full flex text-yellow-200 justify-start"
                    v-if="userCan('write', referCase)"
                    :href="`${$page.props.app.baseUrl}/forms/${referCase.note_slug}/edit`"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="edit"
                    />
                    <span class="block font-normal text-thick-theme-light">เขียนต่อ</span>
                </inertia-link>

                <!-- edit -->
                <inertia-link
                    class="w-full flex text-alt-theme-light justify-start"
                    v-if="userCan('edit', referCase)"
                    :href="`${$page.props.app.baseUrl}/forms/${referCase.note_slug}/edit`"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="eraser"
                    />
                    <span class="block font-normal text-thick-theme-light">แก้ไข</span>
                </inertia-link>

                <!-- read -->
                <inertia-link
                    class="w-full flex text-alt-theme-light justify-start"
                    v-if="userCan('read', referCase)"
                    :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="readme"
                    />
                    <span class="block font-normal text-thick-theme-light">อ่าน</span>
                </inertia-link>

                <!-- admit -->
                <button
                    v-if="userCan('admit', referCase)"
                    class="w-full flex items-center text-green-200 justify-start"
                    :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                    @click="this.$refs.admission.open(referCase.id, referCase.hn, referCase.patient_name)"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="procedure"
                    />
                    <span class="block font-normal text-thick-theme-light">แอดมิด</span>
                </button>

                <!-- cancel -->
                <button
                    v-if="abilities.includes('admit_patient') || referCase.referer === $page.props.user.name"
                    class="w-full flex text-red-200 justify-start"
                    :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="trash-alt"
                    />
                    <span class="block font-normal text-thick-theme-light">ยกเลิก</span>
                </button>
            </div>
        </div>

        <create-case ref="createCase" />
        <admission ref="admission" />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import CreateCase from '@/Components/Forms/CreateCase';
import Admission from '@/Components/Forms/Admission';
import Icon from '@/Components/Helpers/Icon';
export default {
    layout: Layout,
    components: { Admission, CreateCase, Icon },
    props: {
        cases: { type: Array, required: true },
    },
    computed: {
        abilities () {
            return this.$page.props.user.abilities;
        },
    },
    created () {
        this.eventBus.on('action-clicked', (action) => {
            if (action === 'create-new-case') {
                // in prod, code run very fast and it run on the layout then
                // this.$refs.createCase is not available at the time
                // so, we wait until the component has switched
                // this is 100% speculation 🤣
                setTimeout(() => this.$nextTick(() => this.$refs.createCase.open()), 300);
            }
        });
    },
    methods: {
        handleAction (action) {
            switch (action) {
            case 'create-new-case':
                this.$nextTick(() => this.$nextTick(() => this.$refs.createCase.open()));
                break;

            default:
                break;
            }
        },
        userCan(ability, referCase) {
            switch (ability) {
            case 'write':
                return referCase.referer === this.$page.props.user.name && referCase.status === 'draft';
            case 'edit':
                return referCase.referer === this.$page.props.user.name && referCase.status === 'submitted';
            case 'read':
                return referCase.referer !== this.$page.props.user.name;
            case 'admit':
                return this.abilities.includes('admit_patient') && referCase.status === 'submitted';
            default:
                return false;
            }
        }
    }
};
</script>
<template>
    <div>
        <div
            class="rounded bg-white shadow-sm my-1 p-2 flex"
            v-for="(referCase, key) in cases"
            :key="key"
        >
            <div class="w-3/4">
                <p
                    class="p-2 pb-1"
                    v-if="$page.props.user.center === 'ศิริราช'"
                >
                    {{ referCase.center }}
                </p>
                <p class="p-2 text-lg pt-0">
                    {{ referCase.patient_name ?? 'ยังไม่มีชื่อ' }}
                </p>
            </div>
            <div class="w-1/4 text-sm">
                <p class="p-2 rounded-md bg-thick-theme-light text-center">
                    {{ referCase.status }}
                </p>
                <p class="p-2 flex justify-end">
                    <inertia-link
                        class="underline italic text-yellow-200"
                        v-if="referCase.referer === $page.props.user.name"
                        :href="`${$page.props.app.baseUrl}/forms/${referCase.note_slug}/edit`"
                    >
                        เขียนต่อ
                    </inertia-link>
                </p>
            </div>
        </div>

        <create-case ref="createCase" />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import CreateCase from '@/Components/Forms/CreateCase';
export default {
    layout: Layout,
    components: { CreateCase },
    props: {
        cases: { type: Array, required: true },
    },
    created () {
        this.eventBus.on('action-clicked', this.handleAction);
    },
    methods: {
        handleAction (action) {
            switch (action) {
            case 'create-new-case':
                this.$refs.createCase.open();
                break;

            default:
                break;
            }
        }
    }
};
</script>
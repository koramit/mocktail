<template>
    <div>
        <!-- reset filters -->
        <inertia-link
            v-if="!cases.data.length && isFiltered"
            :href="`${baseUrl}/refer-cases?remember=forget`"
            class="text-yellow-400 text-semibold mt-2"
            :replace="true"
            as="button"
        >
            ยกเลิกตัวกรอง
        </inertia-link>
        <!-- card -->
        <div
            class="rounded bg-white shadow-sm my-1 p-1 flex"
            v-for="(referCase, key) in cases.data"
            :key="key"
        >
            <!-- left detail -->
            <div class="w-3/4">
                <p class="p-1 pb-0 text-thick-theme-light">
                    <button
                        class="font-semibold mr-1"
                        v-if="$page.props.user.center === 'ศิริราช'"
                        @click="applyFilters('center', referCase.center)"
                    >
                        {{ referCase.center }}
                        <icon
                            class="inline w-2 h-2"
                            name="filter"
                            v-if="referCase.center === filters.center"
                        />
                    </button>
                    <button
                        class="text-sm shadow-sm italic px-2 rounded-xl"
                        :class="{
                            'bg-gray-200 text-thick-theme-light': referCase.status === 'draft',
                            'bg-yellow-200 text-yellow-400': referCase.status === 'submitted',
                            'bg-green-200 text-green-400': referCase.status === 'admitted',
                            'bg-red-200 text-red-400': referCase.status === 'canceled',
                        }"
                        @click="applyFilters('status', referCase.status)"
                    >
                        {{ referCase.status_label }}
                        <icon
                            class="inline w-2 h-2"
                            name="filter"
                            v-if="referCase.status === filters.status"
                        />
                    </button>
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

                <!-- next features -->
                <dropdown v-if="userCan('note', referCase)">
                    <template #default>
                        <button
                            class="w-full flex text-alt-theme-light justify-start"
                        >
                            <icon
                                class="w-4 h-4 mr-1"
                                name="notes-medical"
                            />
                            <span class="block font-normal text-thick-theme-light">เขียนโน๊ต</span>
                        </button>
                    </template>
                    <template #dropdown>
                        <div class="mt-2 p-2 shadow-xl min-w-max bg-white text-thick-theme-light cursor-pointer rounded text-sm">
                            <inertia-link
                                class="w-full flex text-alt-theme-light justify-start my-2"
                                :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                            >
                                <icon
                                    class="w-4 h-4 mr-1"
                                    name="edit"
                                />
                                <span class="block font-normal text-thick-theme-light">ฟอร์มปรอท</span>
                            </inertia-link>
                            <inertia-link
                                class="w-full flex text-alt-theme-light justify-start my-2"
                                :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                            >
                                <icon
                                    class="w-4 h-4 mr-1"
                                    name="edit"
                                />
                                <span class="block font-normal text-thick-theme-light">admission note</span>
                            </inertia-link>
                            <inertia-link
                                class="w-full flex text-alt-theme-light justify-start my-2"
                                :href="`${$page.props.app.baseUrl}/reports/${referCase.note_slug}`"
                            >
                                <icon
                                    class="w-4 h-4 mr-1"
                                    name="edit"
                                />
                                <span class="block font-normal text-thick-theme-light">discharge summary</span>
                            </inertia-link>
                        </div>
                    </template>
                </dropdown>

                <!-- next features -->
                <!-- cancel -->
                <button
                    v-if="userCan('delete', referCase)"
                    class="w-full flex text-red-200 justify-start"
                    @click="cancel(referCase)"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="trash-alt"
                    />
                    <span class="block font-normal text-thick-theme-light">ยกเลิก</span>
                </button>
            </div>
        </div>

        <div v-if="cases.links.length > 3">
            <div class="flex flex-wrap -mb-1 mt-4">
                <template v-for="(link, key) in cases.links">
                    <div
                        v-if="link.url === null"
                        :key="key"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 bg-gray-200 text-gray-400 border rounded cursor-not-allowed"
                        v-html="link.label"
                    />
                    <inertia-link
                        v-else
                        :key="key+'theLink'"
                        class="mr-1 mb-1 px-4 py-3 text-sm text-dark-theme-light leading-4 border border-alt-theme-light rounded hover:bg-white focus:border-dark-theme-light focus:text-dark-theme-light transition-colors"
                        :class="{ 'bg-alt-theme-light cursor-not-allowed hover:bg-alt-theme-light': link.active }"
                        :href="link.url"
                        as="button"
                        :disabled="link.active"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>

        <create-case ref="createCase" />
        <admission ref="admission" />
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/Components/Layouts/Layout';
import CreateCase from '@/Components/Forms/CreateCase';
import Admission from '@/Components/Forms/Admission';
import Icon from '@/Components/Helpers/Icon';
import Dropdown from '@/Components/Helpers/Dropdown';
export default {
    layout: Layout,
    emits: ['need-confirm'],
    components: { Admission, CreateCase, Icon, Dropdown },
    props: {
        cases: { type: Object, required: true },
        filters: { type: Object, required: true },
    },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            currentConfirm: {}
        };
    },
    computed: {
        abilities () {
            return this.$page.props.user.abilities;
        },
        isFiltered () {
            return location.search.substr(1).length > 0;
        }
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
        this.eventBus.on('confirmed', (text) => {
            if (this.currentConfirm.action === 'cancel') {
                let form = useForm({ reason: text ?? 'owner' });
                form.delete(`${this.baseUrl}/refer-cases/${this.currentConfirm.resource_id}`, {
                    preserveScroll: true,
                });
            }
        });
    },
    methods: {
        userCan(ability, referCase) {
            switch (ability) {
            case 'write':
                return referCase.referer === this.$page.props.user.name && referCase.status === 'draft';
            case 'edit':
                return referCase.referer === this.$page.props.user.name && referCase.status === 'submitted';
            case 'read':
                return referCase.status !== 'canceled' && (referCase.referer !== this.$page.props.user.name || referCase.status === 'admitted');
            case 'admit':
                return this.abilities.includes('admit_patient') && referCase.status === 'submitted';
            case 'note':
                return false; //this.abilities.includes('admit_patient') && referCase.status === 'admitted'; // demo only
            case 'delete':
                return !['admitted', 'discharged', 'canceled'].includes(referCase.status) && (this.abilities.includes('admit_patient') || referCase.referer === this.$page.props.user.name);
            default:
                return false;
            }
        },
        cancel (referCase) {
            this.currentConfirm.action = 'cancel';
            this.currentConfirm.resource_id = referCase.id;
            this.eventBus.emit('need-confirm', { confirmText: 'ยกเลิกใบส่งตัว ' + referCase.patient_name, needReason: referCase.referer !== this.$page.props.user.name });
        },
        applyFilters (filter, value) {
            let filters = {...this.filters};
            console.log(filters);
            filters[filter] = filters[filter] ? null : value;
            console.log(filters);
            let query = Object.keys(filters)
                .filter(key => filters[key])
                .map(key => `${key}=${filters[key]}`)
                .join('&');
            query = query ? query : 'remember=forget';
            console.log(query);
            this.$inertia.replace(`${this.baseUrl}/refer-cases?${query}`);
        }
    }
};
</script>
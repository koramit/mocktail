<template>
    <div>
        <!-- reset filters -->
        <!-- <Link
            v-if="!cases.data.length && isFiltered"
            :href="`${baseUrl}/refer-cases?remember=forget`"
            class="text-yellow-400 text-semibold mt-2"
            :replace="true"
            as="button"
        >
            ยกเลิกตัวกรอง
        </Link> -->

        <!-- download spreedsheet -->
        <div class="md:flex">
            <a
                class="flex items-center text-green-600 mb-2 mr-4"
                :href="`${baseUrl}/reports/refer-cases-hi-dos`"
                v-if="$page.props.user.abilities.includes('export_hi_dos')"
            >
                <Icon
                    class="w-4 h-4 mr-1"
                    name="file-excel"
                />
                <span class="block font-normal text-thick-theme-light">ข้อมูลทำ DOS สำหรับ HI</span>
            </a>
            <a
                class="flex items-center text-green-600 mb-2 mr-4"
                :href="`${baseUrl}/reports/refer-cases-admit-order`"
                v-if="$page.props.user.abilities.includes('export_hi_dos')"
            >
                <Icon
                    class="w-4 h-4 mr-1"
                    name="file-excel"
                />
                <span class="block font-normal text-thick-theme-light">ข้อมูลทำคำสั่ง Admit</span>
            </a>
        </div>
        <div
            class="flex justify-between mb-2"
        >
            <div class="w-2/3">
                <input
                    autocomplete="off"
                    type="text"
                    name="search"
                    :placeholder="$page.props.user.center === 'ศิริราช' ? '🔍 ด้วย HN AN ชื่อ ห้อง แพทย์':'🔍 ด้วย ชื่อ'"
                    v-model="search"
                    class="form-input w-full"
                >
            </div>
            <a
                class="flex items-center text-green-600"
                :href="`${baseUrl}/reports/refer-cases${reportUrl}`"
            >
                <Icon
                    class="w-4 h-4 mr-1"
                    name="file-excel"
                />
                <span class="block font-normal text-thick-theme-light">รายงาน</span>
            </a>
        </div>

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
                        <Icon
                            class="inline w-2 h-2"
                            name="filter"
                            v-if="referCase.center === filters.center"
                        />
                    </button>
                    <button
                        class="text-sm shadow-sm italic px-2 rounded-xl mr-1"
                        :class="{
                            'bg-purple-200 text-purple-400 ': referCase.status === 'discharged',
                            'bg-gray-200 text-thick-theme-light': referCase.status === 'draft',
                            'bg-yellow-200 text-yellow-400': referCase.status === 'submitted',
                            'bg-green-200 text-green-400': referCase.status === 'admitted',
                            'bg-red-200 text-red-400': referCase.status === 'canceled',
                        }"
                        @click="applyFilters('status', referCase.status)"
                    >
                        {{ referCase.status_label }}
                        <Icon
                            class="inline w-2 h-2"
                            name="filter"
                            v-if="referCase.status === filters.status"
                        />
                    </button>
                    <button
                        class="text-sm shadow-sm italic px-2 rounded-xl mr-1 bg-indigo-200 text-indigo-400"
                        v-if="referCase.meta.type === 'Home Isolation'"
                        @click="applyFilters('type', referCase.meta.type)"
                    >
                        <Icon
                            class="inline w-4 h-4"
                            name="house-user"
                        />
                        <Icon
                            class="ml-1 inline w-2 h-2"
                            name="filter"
                            v-if="referCase.meta.type === filters.type"
                        />
                    </button>
                    <template v-if="referCase.meta.type !== 'Home Isolation'">
                        <button
                            class="text-sm shadow-sm italic px-2 rounded-xl mr-1 text-soft-theme-light bg-bitter-theme-light"
                            @click="applyFilters('type', referCase.meta.type)"
                        >
                            <Icon
                                class="inline w-4 h-4"
                                name="hospital"
                            />
                            <Icon
                                class="ml-1 inline w-2 h-2"
                                name="filter"
                                v-if="referCase.meta.type === filters.type"
                            />
                        </button>
                        <button
                            class="text-sm shadow-sm italic px-2 rounded-xl mr-1"
                            :class="{
                                'text-green-200 bg-green-400': referCase.meta.ward === 'Baiyoke',
                                'bg-soft-theme-light text-bitter-theme-light': referCase.meta.ward === 'Riverside'
                            }"
                            @click="applyFilters('ward', referCase.meta.ward)"
                        >
                            {{ referCase.meta.ward }}
                            <Icon
                                class="ml-1 inline w-2 h-2"
                                name="filter"
                                v-if="(referCase.meta.ward) === filters.ward"
                            />
                        </button>
                    </template>
                    <span
                        v-if="referCase.status === 'admitted' && referCase.meta.type !== 'Home Isolation'"
                        class="text-sm text-thick-theme-light"
                    >
                        #{{ referCase.room_number }}
                    </span>
                </p>
                <div class="flex items-baseline">
                    <p class="p-1 text-lg pt-0">
                        {{ referCase.patient_name }}
                    </p>
                </div>
                <p class="px-1 text-xs text-dark-theme-light italic">
                    ปรับปรุงล่าสุด {{ referCase.updated_at_for_humans }}
                </p>
            </div>
            <!-- right menu -->
            <div class="w-1/4 text-sm p-1 grid justify-items-center ">
                <!-- write -->
                <Link
                    class="w-full flex text-yellow-200 justify-start"
                    v-if="userCan('write', referCase)"
                    :href="`${baseUrl}/forms/${referCase.note_slug}/edit`"
                >
                    <Icon
                        class="w-4 h-4 mr-1"
                        name="edit"
                    />
                    <span class="block font-normal text-thick-theme-light">เขียนต่อ</span>
                </Link>

                <!-- edit -->
                <Link
                    class="w-full flex text-alt-theme-light justify-start"
                    v-if="userCan('edit', referCase)"
                    :href="`${baseUrl}/forms/${referCase.note_slug}/edit`"
                >
                    <Icon
                        class="w-4 h-4 mr-1"
                        name="eraser"
                    />
                    <span class="block font-normal text-thick-theme-light">แก้ไข</span>
                </Link>

                <!-- read -->
                <Link
                    class="w-full flex text-alt-theme-light justify-start"
                    v-if="userCan('read', referCase)"
                    :href="`${baseUrl}/reports/${referCase.slug}`"
                >
                    <Icon
                        class="w-4 h-4 mr-1"
                        name="readme"
                    />
                    <span class="block font-normal text-thick-theme-light">อ่าน</span>
                </Link>

                <!-- admit -->
                <button
                    v-if="userCan('admit', referCase)"
                    class="w-full flex items-center text-green-200 justify-start"
                    @click="this.$refs.admission.open(referCase.id, referCase.hn, referCase.patient_name, referCase.sat_code, referCase.meta.ward)"
                >
                    <Icon
                        class="w-4 h-4 mr-1"
                        name="procedure"
                    />
                    <span class="block font-normal text-thick-theme-light">แอดมิท</span>
                </button>

                <!-- notes -->
                <Link
                    class="w-full flex text-dark-theme-light justify-start"
                    v-if="userCan('note', referCase)"
                    :href="`${baseUrl}/refer-cases/${referCase.slug}/notes`"
                >
                    <Icon
                        class="w-4 h-4 mr-1"
                        name="clipboard-list"
                    />
                    <span class="block font-normal text-thick-theme-light">โน๊ต</span>
                </Link>

                <!-- cancel -->
                <button
                    v-if="userCan('delete', referCase)"
                    class="w-full flex text-red-200 justify-start"
                    @click="cancel(referCase)"
                >
                    <Icon
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
                    <Link
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

        <CreateCase ref="createCase" />
        <Admission ref="admission" />
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import throttle from 'lodash/throttle';
import Layout from '@/Components/Layouts/Layout';
import CreateCase from '@/Components/Forms/CreateCase';
import Admission from '@/Components/Forms/Admission';
import Icon from '@/Components/Helpers/Icon';
import { Link } from '@inertiajs/inertia-vue3';
export default {
    components: { Admission, CreateCase, Icon, Link },
    layout: Layout,
    emits: ['need-confirm'],
    props: {
        cases: { type: Object, required: true },
        filters: { type: Object, required: true },
    },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            currentConfirm: {},
            search: this.filters.search
        };
    },
    watch: {
        search: {
            handler: throttle(function() {
                this.applyFilters('search', this.search);
            }, 450),
        }
    },
    computed: {
        abilities () {
            return this.$page.props.user.abilities;
        },
        isFiltered () {
            return location.search.substr(1).length > 0;
        },
        reportUrl () {
            let query = Object.keys(this.filters)
                .filter(key => this.filters[key])
                .map(key => `${key}=${this.filters[key]}`)
                .join('&');
            return query ? `?${query}` : '';
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
                return referCase.status !== 'canceled';
            case 'admit':
                return this.abilities.includes('admit_patient') && referCase.status === 'submitted' && referCase.meta.type !== 'Home Isolation';
            case 'note':
                return (
                    this.$page.props.user.roles.indexOf('md') !== -1
                    || (referCase.meta.type === 'Home Isolation' && this.$page.props.user.roles.indexOf('home_nurse') !== -1)
                )
                    && ['admitted', 'discharged'].includes(referCase.status); // && referCase.meta.type !== 'Home Isolation' nurse not write note, for now
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
            if (filter === 'search') {
                filters[filter] = value;
            } else {
                filters[filter] = filters[filter] ? null : value;
            }
            let query = Object.keys(filters)
                .filter(key => filters[key])
                .map(key => `${key}=${filters[key]}`)
                .join('&');
            query = query ? query : 'remember=forget';
            this.$inertia.visit(`${this.baseUrl}/refer-cases?${query}`, { preserveState: true });
        }
    }
};
</script>
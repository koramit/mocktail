<template>
    <!-- table  -->
    <div
        class="hidden md:block rounded-md shadow overflow-x-auto overflow-y-scroll"
        style="max-height: 90%;"
    >
        <table class="w-full whitespace-nowrap relative bg-white">
            <tr class="text-left font-semibold">
                <th
                    class="px-3 pt-4 pb-2 sticky top-0 text-white bg-thick-theme-light"
                    :class="{'z-20': column === 'แพทย์'}"
                    v-for="column in headrows"
                    :key="column"
                    :colspan="column === 'แพทย์' ? 2:1"
                    v-text="column"
                />
            </tr>
            <tr
                v-for="referCase in cases"
                :key="referCase.slug"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
                <td
                    class="border-t"
                    v-for="field in ['hn', 'patient_name', 'ward', 'date_refer', 'referer']"
                    :key="field"
                >
                    <span class="px-3 py-2">{{ referCase[field] }}</span>
                </td>
                <td class="border-t">
                    <Link
                        method="post"
                        :href="`${baseUrl}/transit-cases/${referCase.slug}`"
                        as="button"
                        class="flex text-yellow-200 items-center"
                    >
                        <Icon
                            class="w-4 h-4 mr-1"
                            name="edit"
                        />
                        <span class="block font-normal text-thick-theme-light">เขียนต่อ</span>
                    </Link>
                </td>
            </tr>
        </table>
    </div>

    <!-- card  -->
    <div class="md:hidden">
        <div
            class="rounded shadow bg-white mb-2 p-4 flex items-start"
            v-for="referCase in cases"
            :key="referCase.slug"
        >
            <div class="w-3/4">
                <p class="font-semibold text-thick-theme-light">
                    {{ referCase.ward }}
                </p>
                <p class="text-lg">
                    HN: {{ referCase.hn }}
                </p>
                <p class="text-lg">
                    {{ referCase.patient_name }}
                </p>
                <p class="italic text-xs text-dark-theme-light">
                    MD: {{ referCase.referer }}
                </p>
            </div>
            <Link
                method="post"
                :href="`${baseUrl}/transit-cases/${referCase.slug}`"
                as="button"
                class="flex text-yellow-200 items-center text-sm"
            >
                <Icon
                    class="w-4 h-4 mr-1"
                    name="edit"
                />
                <span class="block font-normal text-thick-theme-light">เขียนต่อ</span>
            </Link>
        </div>
    </div>
</template>
<script>
import Layout from '@/Components/Layouts/Layout';
import { Link } from '@inertiajs/inertia-vue3';
import Icon from '@/Components/Helpers/Icon';
export default {
    layout: Layout,
    components: { Link, Icon },
    props: {
        cases: { type: Array, required: true }
    },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            headrows: ['HN', 'ชื่อ', 'หอผู้ป่วย', 'วันที่ส่งตัว', 'แพทย์'],
        };
    }
};
</script>
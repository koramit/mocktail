<template>
    <paper>
        <template #default>
            <div class="px-12 py-6 print-p-0">
                <h2 class="font-semibold pb-2 border-b-2 border-dashed text-xl text-center">
                    Admission Note
                </h2>

                <h3 class="font-normal underline mt-4">
                    ข้อมูลการแอดมิท
                </h3>
                <div class="mt-1 grid grid-rows-3 grid-flow-col gap-2">
                    <display-input
                        v-for="(field, key) in configs.admission"
                        :key="key"
                        :label="field.label"
                        :data="contents.admission[field.name]"
                        :format="field.format ?? ''"
                    />
                </div>

                <h3 class="font-normal underline mt-4">
                    ข้อมูลจากใบส่งตัว
                </h3>
                <div class="mt-1 grid grid-rows-2 grid-flow-col gap-2">
                    <display-input
                        v-for="(field, key) in configs.patient"
                        :key="key"
                        :label="field.label"
                        :data="contents.patient[field.name]"
                        :format="field.format ?? ''"
                    />
                </div>

                <h3 class="font-normal underline mt-4">
                    Vital Signs ล่าสุด
                </h3>
                <div class="mt-1 grid grid-rows-3 grid-flow-col gap-1">
                    <display-input
                        v-for="(field, key) in configs.vital_signs"
                        :key="key"
                        :label="field.label"
                        :data="contents.vital_signs[field.name]"
                    />
                </div>

                <template
                    v-for="(topic, key) in configs.topics"
                    :key="key"
                >
                    <h3 class="font-normal underline mt-4">
                        {{ topic.label }}
                    </h3>
                    <div class="mt-1">
                        <display-input :data="contents[topic.name]" />
                    </div>
                </template>

                <h3 class="font-normal underline mt-4">
                    คำสั่งการรักษา
                </h3>
                <div
                    class="mt-1 grid grid-flow-col gap-2"
                    :class="{
                        'grid-rows-1': filteredTreatments.length <= 3,
                        'grid-rows-2': filteredTreatments.length > 3
                    }"
                >
                    <display-input
                        v-for="(field, key) in filteredTreatments"
                        :key="key"
                        :label="field.label"
                        :data="contents.treatments[field.name]"
                    />
                </div>

                <div
                    v-if="contents.remark"
                    class="avoid-page-break"
                >
                    <h3 class="font-normal underline mt-4">
                        เพิ่มเติม
                    </h3>
                    <display-input
                        :data="contents.remark"
                    />
                </div>

                <div class="new-page">
                    <criteria-v-1
                        v-if="contents.criterias.version === 1"
                        :criterias="contents.criterias"
                    />
                    <criteria-v-2
                        v-else-if="contents.criterias.version === 2"
                        :criterias="contents.criterias"
                    />
                </div>
            </div>
        </template>
        <template #footer-right>
            <p class="text-print-size">
                Electronic Signed by {{ contents.author.name }} ว. {{ contents.author.pln }} เมื่อ {{ contents.author.updated_at }}
            </p>
        </template>
    </paper>
</template>

<script>
import DisplayInput from '@/Components/Helpers/DisplayInput';
import Plain from '@/Components/Layouts/Plain';
import Paper from '@/Components/Layouts/Paper';
import CriteriaV1 from '@/Components/Printouts/CriteriaV1';
import CriteriaV2 from '@/Components/Printouts/CriteriaV2';
export default {
    layout: Plain,
    components: { DisplayInput, Paper, CriteriaV1, CriteriaV2 },
    props: {
        contents: { type: Object, required: true },
        configs: { type: Object, required: true },
    },
    computed: {
        filteredTreatments () {
            let treatments = [];
            Object.keys(this.contents.treatments).forEach(key => {
                let index = this.configs.treatments.findIndex(t => t.name === key);
                if (index !== -1) {
                    treatments.push(this.configs.treatments[index]);
                }
            });
            return treatments;
        }
    },
};
</script>
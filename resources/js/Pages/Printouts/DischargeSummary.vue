<template>
    <paper>
        <template #default>
            <div class="px-12 py-6 print-p-0">
                <h2 class="font-semibold pb-2 border-b-2 border-dashed text-xl text-center">
                    Discharge Summary
                </h2>

                <h3 class="font-normal underline mt-4">
                    ข้อมูลการแอดมิท
                </h3>
                <div class="mt-1 grid grid-rows-4 grid-flow-col gap-2">
                    <display-input
                        v-for="(field, key) in configs.admission"
                        :key="key"
                        :label="field.label"
                        :data="contents.admission[field.name]"
                        :format="field.format ?? ''"
                    />
                </div>

                <template
                    v-for="(topic, key) in configs.topics_a"
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
                    v-for="(topic, key) in configs.topics_b"
                    :key="key"
                >
                    <h3 class="font-normal underline mt-4">
                        {{ topic.label }}
                    </h3>
                    <div class="mt-1">
                        <display-input :data="contents[topic.name]" />
                    </div>
                </template>

                <template v-if="contents.remark">
                    <h3 class="font-normal underline mt-4">
                        เพิ่มเติม
                    </h3>
                    <display-input :data="contents.remark" />
                </template>
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
export default {
    layout: Plain,
    components: { DisplayInput, Paper },
    props: {
        contents: { type: Object, required: true },
        configs: { type: Object, required: true },
    },
};
</script>
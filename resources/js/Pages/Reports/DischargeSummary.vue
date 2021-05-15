<template>
    <div>
        <div class="bg-white rounded shadow-sm p-4 mt-8">
            <h2 class="font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-xl flex justify-center items-baseline">
                <p>Discharge Summary</p>
                <p
                    v-if="! contents.submitted"
                    class="ml-6 text-sm font-normal"
                >
                    (ยังเขียนไม่เสร็จ)
                </p>
                <a
                    v-else
                    :href="`${$page.props.app.baseUrl}/printouts/${configs.note_slug}`"
                    class="ml-6 text-sm font-normal text-dark-theme-light flex"
                    target="_blank"
                >
                    <icon
                        class="mr-2 h-4 w-4"
                        name="print"
                    />
                    พิมพ์
                </a>
            </h2>

            <h3 class="font-normal underline text-dark-theme-light mt-6">
                ข้อมูลการแอดมิท
            </h3>
            <div class="mt-2 sm:grid grid-rows-6 xl:grid-rows-4 grid-flow-col gap-2 lg:gap-3 xl:gap-4">
                <display-input
                    v-for="(field, key) in configs.admission"
                    class="mt-2 md:mt-0"
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
                <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                    {{ topic.label }}
                </h3>
                <div class="mt-2">
                    <display-input
                        class="mt-2 md:mt-0"
                        :data="contents[topic.name]"
                    />
                </div>
            </template>

            <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                Vital Signs ล่าสุด
            </h3>
            <div class="mt-2 grid grid-rows-4 sm:grid-rows-3 grid-flow-col gap-1 sm:gap-2 lg:gap-3 xl:gap-4">
                <display-input
                    v-for="(field, key) in configs.vital_signs"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.vital_signs[field.name]"
                />
            </div>

            <template
                v-for="(topic, key) in configs.topics_b"
                :key="key"
            >
                <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                    {{ topic.label }}
                </h3>
                <div class="mt-2">
                    <display-input
                        class="mt-2 md:mt-0"
                        :data="contents[topic.name]"
                    />
                </div>
            </template>

            <template v-if="contents.remark">
                <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                    เพิ่มเติม
                </h3>
                <display-input
                    class="mt-2 md:mt-0"
                    :data="contents.remark"
                />
            </template>

            <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                ผู้เขียน
            </h3>
            <contact-card :contact="contents.author" />
        </div>
    </div>
</template>

<script>
import DisplayInput from '@/Components/Helpers/DisplayInput';
import ContactCard from '@/Components/Helpers/ContactCard';
import Icon from '@/Components/Helpers/Icon';
export default {
    components: { DisplayInput, ContactCard, Icon },
    props: {
        contents: { type: Object, required: true },
        configs: { type: Object, required: true },
    },
};
</script>
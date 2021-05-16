<template>
    <div>
        <div class="bg-white rounded shadow-sm p-4 mt-8">
            <h2 class="font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-xl flex justify-center items-baseline">
                <p>ใบส่งตัว</p>
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
                ข้อมูลเบื้องต้น
            </h3>
            <div class="mt-2 sm:grid grid-rows-6 xl:grid-rows-4 grid-flow-col gap-2 lg:gap-3 xl:gap-4">
                <display-input
                    v-for="(field, key) in configs.patient"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.patient[field.name]"
                    :format="field.format ?? ''"
                />
            </div>

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
                v-for="(topic, key) in configs.topics"
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
                คำสั่งการรักษา
            </h3>
            <div
                class="mt-2 grid grid-flow-col gap-2 lg:gap-3 xl:gap-4"
                :class="{
                    'grid-rows-1': filteredTreatments.length <= 2,
                    'grid-rows-2 sm:grid-rows-1': filteredTreatments.length === 3,
                    'grid-rows-3 sm:grid-rows-2': filteredTreatments.length > 3
                }"
            >
                <display-input
                    v-for="(field, key) in filteredTreatments"
                    class="mt-2 md:mt-0"
                    :key="key"
                    :label="field.label"
                    :data="contents.treatments[field.name]"
                />
            </div>

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
                เอกสารแนบ
            </h3>
            <image-uploader
                class="mt-2"
                v-if="contents.patient.center !== 'ศิริราช'"
                label="๏  Film Chest ล่าสุด"
                name="contents->uploads->film"
                :note-id="0"
                v-model="uploads.film"
                :readonly="true"
            />
            <image-uploader
                class="mt-2"
                label="๏  ใบรายงานผล COVID"
                name="contents->uploads->lab"
                :note-id="0"
                v-model="uploads.lab"
                :readonly="true"
            />

            <template v-if="contents.patient.tel_no">
                <h3 class="font-normal underline text-dark-theme-light mt-8 md:mt-12">
                    หมายเลขโทรศัพท์ของผู้ป่วย
                </h3>
                <contact-card :contact="{name: '', tel_no: contents.patient.tel_no }" />
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
import ImageUploader from '@/Components/Controls/ImageUploader';
import Icon from '@/Components/Helpers/Icon';
export default {
    components: { DisplayInput, ContactCard, ImageUploader, Icon },
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
    data () {
        return {
            uploads: this.contents.uploads,
        };
    },
};
</script>
<template>
    <div class="p-4">
        <h1 class="font-semibold text-lg underline text-center">
            เกณฑ์การรับผู้ป่วยเข้าในโรงพยาบาลสนามใบหยก (hospitel)
        </h1>

        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <form-checkbox
                class="mt-2"
                label="1. อายุ > 18 ปี สามารถช่วยเหลือตัวเองได้ และไม่มีความเสี่ยงทางจิตเวช"
                v-model="criterias.mature"
            />
            <form-checkbox
                class="mt-2"
                label="2. รักษาตัวในโรงพยาบาล"
                v-model="criterias.admit"
            />
            <!-- __อย่างน้อย 48 ชั่วโมงในผู้ป่วยที่ไม่มีปัจจัยเสี่ยง
   หรือ __ อย่างน้อย 96 ชั่วโมงในผู้ป่วยที่มีปัจจัยเสี่ยงต่อไปนี้อย่างน้อย 1 ข้อ
       อายุ > 60 ปี, โรคปอดเรื้อรัง, โรคหัวใจและหลอดเลือด, โรคหลอดเลือดสมอง, เบาหวานที่คุมไม่ได้ ,น้ำหนัก มากกว่า 90 กก, ตับแข็ง, ภาวะภูมิคุ้มกันต่ำ และ lymphocyte <1,000 /mm3  (ยกกำลัง3) -->
            <form-checkbox
                class="mt-2"
                label="3. วินิจฉัยเป็น"
                v-model="criterias.diag"
            />
            <form-checkbox
                class="mt-2"
                label="3. asymptomatic  COVID-19 infection  "
                v-model="criterias.diag"
            />
            <form-checkbox
                class="mt-2"
                label="3. วินิจฉัยเป็น"
                v-model="criterias.diag"
            />
        </div>
    </div>

    <!-- 3. วินิจฉัยเป็น
    __asymptomatic  COVID-19 infection
    หรือ __ COVID-19 with URI ที่อาการคงที่
    หรือ __ COVID-19 with pneumonia ที่อาการดีขึ้นหลังให้การรักษาอย่างน้อย 48 ชั่วโมง และไม่ได้ ออกซิเจน
    4.  มีสัญญาณชีพคงที่ครบทุกข้อดังนี้ COVID-19
    __ Body temperature < 37.8 C ต่อเนื่องกัน 48 ชั่วโมง
    __ Respiratory rate< 20/min
    __ Oxygen saturation room air > 96% at rest
    5__มีผล CXR ภายใน 48 ชั่วโมงที่ปกติ หรือคงที่
    6 __หากได้รับยา favipiravir ต้องได้ยามาแล้วอย่างน้อย 48 ชั่วโมง
    7 __ไม่ได้รับยาฉีด -->
</template>

<script>
import FormCheckbox from '@/Components/Controls/FormCheckbox';
export default {
    components: { FormCheckbox },
    data () {
        return {
            criterias: {}
        };
    },
    created () {
        document.title = 'Transfer Criteria Form';
    },
    mounted() {
        this.$nextTick(function () {
            const pageLoadingIndicator = document.getElementById('page-loading-indicator');
            if (pageLoadingIndicator) {
                pageLoadingIndicator.remove();
            }
        });
    },
};
</script>
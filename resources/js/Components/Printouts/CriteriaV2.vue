<template>
    <div class="px-12 py-6 print-p-0">
        <h2 class="font-semibold pb-2 border-b-2 border-dashed text-xl text-center">
            เกณฑ์การรับผู้ป่วยเข้าในหอผู้ป่วยเฉพาะกิจ (Hospitel)
        </h2>
        <p class="font-semibold text-dark-theme-light mt-4">
            {{ criterias.patient_label }} เข้าเกณฑ์ทุกข้อดังต่อไปนี้
        </p>
        <div class="flex my-2  text-sm font-normal">
            <p>1.</p>
            <p class="px-2 tracking-wide leading-5">
                สามารถช่วยเหลือตัวเองได้ ไม่มีความเสี่ยงทางจิตเวชและไม่มีประวัติใช้สารเสพติด
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>2.</p>
            <p class="px-2 tracking-wide leading-5">
                ได้รับการประเมินจากแพทย์แล้วว่าสามารถส่งตัวไปหอผู้ป่วยเฉพาะกิจได้ <br class="mb-2"> โดยต้องรักษาตัวอยู่ในโรงพยาบาลอย่างน้อย 96 ชม. กรณีที่ มีปัจจัยเสี่ยงต่อไปนี้อย่างน้อย 1 ข้อ ได้แก่
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                อายุมากกว่า 60 ปี
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                โรคปอดเรื้อรัง
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                โรคหัวใจและหลอดเลือด
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                โรคหลอดเลือดสมอง
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                เบาหวานที่คุมไม่ได้
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                น้ำหนักมากกว่า 90 กก.
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                ตับแข็ง
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                ภาระภูมิคุ้มกันต่ำ
            </p>
        </div>
        <div class="flex my-2 pl-10 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                Lymphocyte ﹤ 1,000/mm³
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>3.</p>
            <p class="px-2 tracking-wide leading-5">
                วินิจฉัยเป็น
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <icon
                    class="w-4 h-4"
                    :name="form.diagnosis === 'asymptomatic_or_stable_uri' ? 'check-circle':'circle'"
                />
            </p>
            <p class="px-2 tracking-wide leading-5 italic">
                Asymptomatic/URI ที่อาการคงที่ หรือ
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <icon
                    class="w-4 h-4"
                    :name="form.diagnosis === 'pneumonia' ? 'check-circle':'circle'"
                />
            </p>
            <p class="px-2 tracking-wide leading-5 italic">
                Pneumonia ที่อาการดีขึ้นหลังให้การรักษาอย่างน้อย 48 ชม. และไม่ได้ใช้ออกซิเจน
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>4.</p>
            <p class="px-2 tracking-wide leading-5">
                มีสัญญาชีพครบทุกข้อ ดังนี้
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                Body temperature ﹤ 37.8 ℃ ต่อเนื่องกัน 48 ชั่วโมง
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                Respiratory rate ﹤ 20/min
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>๏</p>
            <p class="px-2 tracking-wide leading-5 italic">
                Oxygen saturation room air ≥ 96% at rest
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>5.</p>
            <p class="px-2 tracking-wide leading-5">
                มีผล CXR ภายใน 48 ชม. ที่ปรกติหรือคงที่
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>6.</p>
            <p class="px-2 tracking-wide leading-5">
                หากได้ยา Favipiravir และ/หรือ dexamethasone ต้องได้ยามาแล้วอย่างน้อย 48 ชม.
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>7.</p>
            <p class="px-2 tracking-wide leading-5">
                ไม่ได้รับยาฉีด
            </p>
        </div>
        <div class="flex my-2  text-sm font-normal">
            <p>8.</p>
            <p class="px-2 tracking-wide leading-5">
                กรณีมีโรคประจำตัว ต้องสามารถควบคุมโรคได้ มีแต่ยารับประทานและสามารถจัดยาทางเองได้
            </p>
        </div>
    </div>
</template>

<script>
import Icon from '@/Components/Helpers/Icon';
export default {
    components: { Icon },
    props: {
        criterias: { type: Object, required: true },
    },
    data () {
        return {
            form: {...this.criterias},
        };
    },
};
</script>
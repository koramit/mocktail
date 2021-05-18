<template>
    <div class="max-h-70 overflow-y-scroll py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
        <p class="font-semibold underline">
            ใบส่งตัว {{ patient }}
        </p>
        <p class="font-semibold text-dark-theme-light mt-4">
            ต้องเข้าเกณฑ์ทุกข้อดังต่อไปนี้
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
                รักษาตัวอยู่ในโรงพยาบาล (<span class=" underline">โปรดเลือก</span>)
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <input
                    type="radio"
                    name="admit"
                    value="los_2_days"
                    v-model="form.admit"
                >
            </p>
            <p
                class="px-2 tracking-wide leading-5 italic cursor-pointer"
                @click="form.admit = 'los_2_days'"
            >
                อย่างน้อย 48 ชม. ในผู้ป่วยที่ไม่มีปัจจัยเสี่ยง หรือ
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <input
                    type="radio"
                    name="admit"
                    value="los_4_days"
                    v-model="form.admit"
                >
            </p>
            <p
                class="px-2 tracking-wide leading-5 italic cursor-pointer"
                @click="form.admit = 'los_4_days'"
            >
                อย่างน้อย 96 ชม. ในผู้ป่วยที่มีปัจจัยเสี่ยงต่อไปนี้อย่างน้อย 1 ข้อ
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
                วินิจฉัยเป็น (<span class=" underline">โปรดเลือก</span>)
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <input
                    type="radio"
                    name="diagnosis"
                    value="asymptomatic_or_stable_uri"
                    v-model="form.diagnosis"
                >
            </p>
            <p
                class="px-2 tracking-wide leading-5 italic cursor-pointer"
                @click="form.diagnosis = 'asymptomatic_or_stable_uri'"
            >
                Asymptomatic/URI ที่อาการคงที่ หรือ
            </p>
        </div>
        <div class="flex my-2 pl-4 text-sm font-normal">
            <p>
                <input
                    type="radio"
                    name="diagnosis"
                    value="pneumonia"
                    v-model="form.diagnosis"
                >
            </p>
            <p
                class="px-2 tracking-wide leading-5 italic cursor-pointer"
                @click="form.diagnosis = 'pneumonia'"
            >
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
                หากได้ยา Favipiravir ต้องได้ยามาแล้วอย่างน้อย 48 ชม.
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
export default {
    emits: ['updated'],
    props: {
        patient: { type: String, required: true },
    },
    data () {
        return {
            form: {
                version: 1,
                admit: null,
                diagnosis: null,
            }

        };
    },
    watch: {
        form: {
            deep: true,
            handler(val) {
                this.$emit('updated', val);
            }
        }
    }
};
</script>
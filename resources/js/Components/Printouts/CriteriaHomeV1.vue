<template>
    <div class="px-12 py-6 print-p-0">
        <h2 class="font-semibold pb-2 border-b-2 border-dashed text-xl text-center">
            การกักตัวเองที่บ้าน
        </h2>
        <p class="font-semibold text-dark-theme-light mt-4">
            {{ criterias.patient_label }}
            {{
                criterias.new_case
                    ? 'ผู้ป่วยวินิจฉัยใหม่ตามเกณฑ์ ดังต่อไปนี้'
                    : 'ผู้ป่วยโควิด-19 ที่ step down หลังเข้ารักษาในโรงพยาบาลหรือ hospitel'
            }}
        </p>
        <div class="flex my-2  text-sm font-normal">
            <p>1.</p>
            <p class="px-2 tracking-wide leading-5">
                ยินยอมทำ Home Isolation
            </p>
        </div>
        <template v-if="criterias.new_case">
            <div class="flex my-2  text-sm font-normal">
                <p>2.</p>
                <p class="px-2 tracking-wide leading-5">
                    อาการ: Asymptomatic หรือ Mild symptom และ Oxygen saturation (RA) ≥ 96%
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>3.</p>
                <p class="px-2 tracking-wide leading-5">
                    Normal chest-x-ray หรือ Film ไม่เปลี่ยนแปลง
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>4.</p>
                <p class="px-2 tracking-wide leading-5">
                    ปัจจัยเสี่ยงของโรครุนแรง: ไม่มีปัจจัยเสี่ยงต่อไปนี้
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    อายุเกิน 60 ปี
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    ภาวะอ้วน (น้ำหนักตัวเกิน 90 กก.หรือ BMI > 30)
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคปอดอุดกั้นเรื้อรัง
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคไตเรื้อรัง
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคเบาหวาน
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคหลอดเลือด
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคหลอดเลือดสมอง
                </p>
            </div>
            <div class="flex my-2 pl-4 text-sm font-normal">
                <p>๏</p>
                <p class="px-2 tracking-wide leading-5 italic">
                    โรคเบาหวานที่ควบคุมไม่ได้
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>5.</p>
                <p class="px-2 tracking-wide leading-5">
                    สถานที่: สามารถแยกห้องนอนและห้องน้ำกับคนที่ไม่ติดเชื้อได้
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>6.</p>
                <p class="px-2 tracking-wide leading-5">
                    เกณฑ์ด้านตรวจติดตาม: สามารถช่วยเหลือตัวเอง วัดไข้ วัดระดับออกซิเจน และใช้ mobile application ได้
                </p>
            </div>
        </template>
        <template v-else>
            <div class="flex my-2  text-sm font-normal">
                <p>2.</p>
                <p class="px-2 tracking-wide leading-5">
                    อาการ: Asymptomatic หรือ อาการดีขึ้น/คงที่แล้ว และ Oxygen saturation (RA) ≥ 94%
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>3.</p>
                <p class="px-2 tracking-wide leading-5">
                    สถานที่: สามารถแยกห้องนอนและห้องน้ำกับคนที่ไม่ติดเชื้อได้
                </p>
            </div>
            <div class="flex my-2  text-sm font-normal">
                <p>4.</p>
                <p class="px-2 tracking-wide leading-5">
                    เกณฑ์ด้านตรวจติดตาม: สามารถช่วยเหลือตัวเอง วัดไข้ วัดระดับออกซิเจน และใช้ mobile application ได้
                </p>
            </div>
        </template>
    </div>
</template>

<script>
export default {
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
<template>
    <div v-if="$page.props.user.center === 'ศิริราช'">
        <refer-note
            id="refer-note"
            :contents="notes.refer_note.contents"
            :configs="notes.refer_note.configs"
        />

        <admission-note
            v-if="notes.admission_note !== undefined"
            id="admission-note"
            :contents="notes.admission_note.contents"
            :configs="notes.admission_note.configs"
        />
        <div
            v-else
            id="admission-note"
            class="bg-white rounded shadow-sm p-4 mt-8"
        >
            <h2 class="font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-center text-xl">
                ยังไม่มี Admission Note
            </h2>
        </div>

        <discharge-summary
            v-if="notes.discharge_summary !== undefined"
            id="discharge-summary"
            :contents="notes.discharge_summary.contents"
            :configs="notes.discharge_summary.configs"
        />
        <div
            v-else
            id="discharge-summary"
            class="bg-white rounded shadow-sm p-4 mt-8"
        >
            <h2 class="font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-center text-xl">
                ยังไม่มี Discharge Summary
            </h2>
        </div>
    </div>
    <div v-else>
        <refer-note
            id="refer-note"
            :contents="notes.refer_note.contents"
            :configs="notes.refer_note.configs"
        />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import ReferNote from '@/Pages/Reports/ReferNote';
import AdmissionNote from '@/Pages/Reports/AdmissionNote';
import DischargeSummary from '@/Pages/Reports/DischargeSummary';
export default {
    components: { ReferNote, AdmissionNote, DischargeSummary },
    layout: Layout,
    props: {
        referCase: { type: Object, required: true },
        notes: { type: Object, required: true },
    }
};
</script>
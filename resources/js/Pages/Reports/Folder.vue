<template>
    <div>
        <refer-note
            id="refer-note"
            :contents="notes.refer_note.contents"
            :configs="notes.refer_note.configs"
        />

        <discharge-summary
            v-if="notes.discharge_summary !== undefined"
            id="discharge-summary"
            :contents="notes.discharge_summary.contents"
            :configs="notes.discharge_summary.configs"
        />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import ReferNote from '@/Pages/Reports/ReferNote';
import DischargeSummary from '@/Pages/Reports/DischargeSummary';
export default {
    components: { ReferNote, DischargeSummary },
    layout: Layout,
    props: {
        referCase: { type: Object, required: true },
        notes: { type: Object, required: true },
    }
};
</script>
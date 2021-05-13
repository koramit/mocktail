<template>
    <div>
        <refer-note :contents="notes.refer_note" />
    </div>
</template>

<script>
import Layout from '@/Components/Layouts/Layout';
import ReferNote from './ReferNote.vue';
export default {
    components: {ReferNote},
    layout: Layout,
    props: {
        referCase: { type: Object, required: true },
        notes: { type: Object, required: true },
    }
};
</script>
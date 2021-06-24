<template>
    <paper>
        <template #default>
            <criteria-v-1
                v-if="contents.criterias.version === 1"
                :criterias="contents.criterias"
            />
            <criteria-v-2
                v-else-if="contents.criterias.version === 2"
                :criterias="contents.criterias"
            />
            <criteria-v-3
                v-else-if="contents.criterias.version === 3"
                :criterias="contents.criterias"
            />
        </template>
        <template #footer-right>
            <p class="text-print-size">
                Electronic Signed by {{ contents.author.name }} ว. {{ contents.author.pln }} เมื่อ {{ contents.author.updated_at }}
            </p>
        </template>
    </paper>
</template>
<script>
import Plain from '@/Components/Layouts/Plain';
import Paper from '@/Components/Layouts/Paper';
import CriteriaV1 from '@/Components/Printouts/CriteriaV1';
import CriteriaV2 from '@/Components/Printouts/CriteriaV2';
import CriteriaV3 from '@/Components/Printouts/CriteriaV3';
export default {
    layout: Plain,
    components: { Paper, CriteriaV1, CriteriaV2, CriteriaV3 },
    props: {
        contents: { type: Object, required: true },
    }
};
</script>
<template>
    <div class="text-sm font-semibold text-dark-theme-light mt-4 flex items-center">
        <p>{{ contact.name }}</p>
        <button
            @click="showContact = !showContact"
            class="mx-4 text-alt-theme-light"
        >
            <icon
                class="w-4 h-4"
                name="phone-square"
            />
        </button>
        <p v-if="showContact">
            {{ contact.tel_no }}
        </p>
    </div>
</template>

<script>
import Icon from '@/Components/Helpers/Icon';
export default {
    components: { Icon },
    props: {
        contact: { type: Object, required: true }
    },
    data () {
        return {
            showContact: false,
        };
    }
};
</script>
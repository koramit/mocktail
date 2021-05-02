<template>
    <div>
        <div class="flex items-center">
            <p class="form-label">
                <span class="inline-block">{{ label }}</span>
                <icon
                    class="ml-1 w-4 h-4 inline-block opacity-25 animate-spin"
                    name="circle-notch"
                    v-if="form.processing"
                />
            </p>
            <button
                class="ml-4"
                @click="$refs.input.click()"
            >
                <icon
                    class="w-4 h-4 text-thick-theme-light"
                    :class="{'animate-pulse': form.processing}"
                    name="camera"
                />
            </button>
            <!-- <button
                class="ml-4"
                v-if="form.uploads.film !== undefined"
            >
                <icon
                    class="w-4 h-4 text-thick-theme-light"
                    name="image"
                />
            </button> -->
        </div>
        <img
            v-if="modelValue !== undefined"
            :src="`${baseUrl}/uploads/${modelValue}`"
            alt=""
        >
        <input
            class="hidden"
            type="file"
            ref="input"
            @input="fileInput"
            capture="environment"
            accept="image/*"
        >
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import Icon from '@/Components/Helpers/Icon';
export default {
    emits: ['update:modelValue', 'autosave'],
    components: { Icon },
    props: {
        // filename: { type: String, required: true },
        modelValue: { type: String, required: true },
        label: { type: String, required: true },
        name: { type: String, required: true },
        noteId: { type: Number, required: true },
    },
    data () {
        return {
            form: useForm({
                file: null,
                name: this.name,
            }),
            baseUrl: this.$page.props.app.baseUrl,
        };
    },
    methods: {
        fileInput (event) {
            this.form.file = event.target.files[0];
            this.form.transform(data => ({...data, note_id: this.noteId}))
                .post(`${this.baseUrl}/uploads`, {
                    preserveScroll: true,
                    // this is NOT A ERROR, but its only way (that I know) to make inertia accept response data when using visit api
                    // onFinish: () => this.form.uploads.film = this.film.errors.filename,
                    onFinish: () => this.$emit('update:modelValue', this.form.errors.filename)
                });
        }
    }
};
</script>
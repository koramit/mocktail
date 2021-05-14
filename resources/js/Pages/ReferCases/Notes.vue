<template>
    <div>
        <consult-guildline :referer="referer" />
        <div class="bg-white rounded shadow-sm p-4 mt-8">
            <h2 class="font-semibold pb-2 border-b border-dashed text-thick-theme-light">
                เอกสารหลัก
            </h2>
            <div
                class="flex items-center"
                v-for="(type, key) in noteTypes"
                :key="key"
            >
                <div class="w-2/4 mr-1">
                    {{ type.name }}
                </div>
                <div class="w-1/4">
                    <!-- write -->
                    <button
                        class="w-full flex text-yellow-200 justify-start items-center my-2 focus:outline-none"
                        @click="event => writeNote(event, type.name.toLowerCase())"
                        v-if="userCanWrite(type.name.toLowerCase())"
                    >
                        <icon
                            class="w-4 h-4 mr-1"
                            name="edit"
                        />
                        <span class="block font-normal text-thick-theme-light">เขียน</span>
                    </button>
                </div>
                <div class="w-1/4">
                    <!-- read -->
                    <inertia-link
                        class="w-full flex text-alt-theme-light justify-start items-center my-2"
                        :href="`${baseUrl}/reports/${referCase.slug}#${type.name.split(' ').join('-').toLowerCase()}`"
                        v-if="userCanRead(type.name.toLowerCase())"
                    >
                        <icon
                            class="w-4 h-4 mr-1"
                            name="readme"
                        />
                        <span class="block font-normal text-thick-theme-light">อ่าน</span>
                    </inertia-link>
                </div>
            </div>
        </div>

        <!-- progress notes -->
        <!-- <div class="bg-white rounded shadow-sm p-4 mt-8">
            <div class="flex justify-between items-center pb-2 border-b border-dashed text-thick-theme-light">
                <h2 class="font-semibold">
                    บันทึกความก้าวหน้าของผู้ป่วย
                </h2>
                <button
                    class="flex text-bitter-theme-light justify-start items-center"
                    @click="$inertia.visit(`${baseUrl}/soon`)"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="notes-medical"
                    />
                    <span class="block">เพิ่ม</span>
                </button>
            </div>
        </div> -->

        <!-- nurse notes -->
        <!-- <div class="bg-white rounded shadow-sm p-4 mt-8">
            <div class="flex justify-between items-center pb-2 border-b border-dashed text-thick-theme-light">
                <h2 class="font-semibold">
                    บันทึกทางการพยาบาล
                </h2>
                <button
                    class="flex text-bitter-theme-light justify-start items-center"
                    @click="$inertia.visit(`${baseUrl}/soon`)"
                >
                    <icon
                        class="w-4 h-4 mr-1"
                        name="notes-medical"
                    />
                    <span class="block">เพิ่ม</span>
                </button>
            </div>
        </div> -->
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/Components/Layouts/Layout';
import Icon from '@/Components/Helpers/Icon';
import ConsultGuildline from '@/Components/Helpers/ConsultGuildline';
export default {
    components: { ConsultGuildline, Icon },
    layout: Layout,
    emits: ['need-confirm'],
    props: {
        referCase: { type: Object, required: true },
        referer: { type: Object, required: true },
        notes: { type: Array, required: true }
    },
    created () {
        this.eventBus.on('confirmed', () => {
            if (this.currentConfirm.action === 'write admission note' || this.currentConfirm.action === 'write discharge summary') {
                let form = useForm({
                    refer_case_id: this.referCase.id,
                    type: this.currentConfirm.action.replace('write ', '').toLowerCase()
                });
                form.post(`${this.baseUrl}/notes`, {
                    replace: true,
                });
            }
        });
    },
    data () {
        return {
            baseUrl: this.$page.props.app.baseUrl,
            noteTypes: [
                { name: 'Refer Note' },
                { name: 'Admission Note' },
                { name: 'Discharge Summary' },
            ],
            currentConfirm: {},
        };
    },
    methods: {
        writeNote (event, type) {
            // user is author
            let notes = this.notes.filter(n => n.type === type);
            if (notes.length && notes[0].author_id === this.$page.props.user.id) {
                this.$inertia.visit(`${this.baseUrl}/forms/${notes[0].slug}/edit`);
                return;
            }

            this.currentConfirm.action = `write ${type}`;
            this.currentConfirm.resource_id = this.referCase.id;
            this.eventBus.emit('need-confirm', {
                confirmText: `เขียน ${type} AN: ${this.referCase.an} ${this.referCase.patient_name} <br>เมื่อเขียนแล้วโน๊ตจะเป็นของท่าน แพทย์ท่านอื่นจะไม่สามารถเขียนโน๊ตนี้ได้`,
                needReason: false
            });
        },
        userCanWrite (type) {
            if (type === 'refer note') {
                return false;
            }

            let notes = this.notes.filter(n => n.type === type);
            return this.$page.props.user.roles.indexOf('md') !== -1 && (notes.length === 0 || notes[0].author_id === this.$page.props.user.id);
        },
        userCanRead (type) {
            if (type === 'refer note') {
                return true;
            }

            return this.notes.filter(n => n.type === type).length;
        }
    }
};
</script>
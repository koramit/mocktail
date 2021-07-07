<template>
    <teleport to="body">
        <modal
            ref="modal"
            width-mode="form-cols-1"
            @closed="$emit('closed')"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    {{
                        referType === 'Hospitel'
                            ? 'เกณฑ์การรับผู้ป่วยเข้าในหอผู้ป่วยเฉพาะกิจ (Hospitel)'
                            : 'เกณฑ์รับผู้ป่วย Home Isolation'
                    }}
                </div>
            </template>
            <template #body>
                <template v-if="referType === 'Hospitel'">
                    <criteria-v-1
                        v-if="version === 1"
                        :patient="patient"
                        @updated="(data) => form = {...data}"
                    />
                    <criteria-v-2
                        v-else-if="version === 2"
                        :patient="patient"
                        @updated="(data) => form = {...data}"
                    />
                    <criteria-v-3
                        v-else-if="version === 3"
                        :patient="patient"
                        @updated="(data) => form = {...data}"
                    />
                </template>
                <template v-else>
                    <criteria-home-v-1
                        v-if="version === 1"
                        :patient="patient"
                        :new-case="newCase"
                        @updated="(data) => form = {...data}"
                    />
                </template>
            </template>
            <template #footer>
                <div
                    v-if="referType === 'Hospitel'"
                    class="flex justify-end items-center"
                >
                    <spinner-button
                        v-if="version === 1"
                        :spin="form.processing"
                        class="btn-dark w-full mt-6"
                        :disabled="!form.admit || !form.diagnosis"
                        @click="confirmed"
                    >
                        ยืนยัน
                    </spinner-button>
                    <spinner-button
                        v-else-if="version === 2"
                        :spin="form.processing"
                        class="btn-dark w-full mt-6"
                        :disabled="!form.diagnosis"
                        @click="confirmed"
                    >
                        ยืนยัน
                    </spinner-button>
                    <spinner-button
                        v-else-if="version === 3"
                        :spin="form.processing"
                        class="btn-dark w-full mt-6"
                        :disabled="!form.diagnosis"
                        @click="confirmed"
                    >
                        ยืนยัน
                    </spinner-button>
                </div>
                <div
                    v-else
                    class="flex justify-end items-center"
                >
                    <spinner-button
                        v-if="version === 1"
                        :spin="form.processing"
                        class="btn-dark w-full mt-6"
                        @click="confirmed"
                    >
                        ยืนยัน
                    </spinner-button>
                </div>
            </template>
        </modal>
    </teleport>
</template>

<script>
import SpinnerButton from '@/Components/Controls/SpinnerButton';
import Modal from '@/Components/Helpers/Modal';
import CriteriaV1 from '@/Components/Forms/CriteriaV1';
import CriteriaV2 from '@/Components/Forms/CriteriaV2';
import CriteriaV3 from '@/Components/Forms/CriteriaV3';
import CriteriaHomeV1 from '@/Components/Forms/CriteriaHomeV1';
export default {
    emits: ['closed', 'confirmed'],
    components: { Modal, SpinnerButton, CriteriaV1, CriteriaV2, CriteriaV3, CriteriaHomeV1 },
    props: {
        patient: { type: String, required: true },
        version: { type: Number, default: 1 },
        referType: { type: String, default: 'Hospitel' },
        newCase: { type: Boolean }
    },
    data () {
        return {
            form: {
                diagnosis: null,
                admit: null,
                version: 1
            },
            busy: false,
        };
    },
    methods: {
        open () {
            this.busy = false;
            this.form.diagnosis = null;
            this.form.admit = null;
            this.$refs.modal.open();
        },
        confirmed () {
            this.busy = true;
            this.$emit('confirmed', this.form);
        }
    }
};
</script>
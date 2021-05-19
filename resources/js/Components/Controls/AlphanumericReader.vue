<template>
    <div>
        <div class="flex items-center h-4">
            <button
                @click="$refs.useCamera.click()"
                v-if="!processing"
            >
                <icon
                    class="w-4 h-4 text-thick-theme-light"
                    name="camera"
                />
            </button>
            <p v-else>
                <!-- <span class="inline-block">{{ label }}</span> -->
                <icon
                    class="w-4 h-4 inline-block opacity-25 animate-spin"
                    name="circle-notch"
                />
            </p>
        </div>
        <input
            class="hidden"
            type="file"
            @input="fileInput"
            capture="environment"
            accept="image/*"
            ref="useCamera"
        >
    </div>
</template>

<script>
import { createWorker } from 'tesseract.js';
import Icon from '@/Components/Helpers/Icon';
export default {
    emits: ['recognized'],
    components: { Icon, },
    data () {
        return {
            processing: false,
        };
    },
    methods: {
        fileInput (event) {
            this.processing = true;
            let file = event.target.files[0];
            // const worker = createWorker({
            //     logger: m => console.log(m)
            // });
            const worker = createWorker();
            const regex = new RegExp(/^[a-z0-9]{18}$/i);

            (async () => {
                await worker.load();
                await worker.loadLanguage('eng');
                await worker.initialize('eng');
                const { data: { text } } = await worker.recognize(file);
                let words = [];
                text.split('\n').map(line => [...line.split(' ').map(word => words.push(word))]);
                let result = false;
                for(let i = 0; i < words.length; i++) {
                    // console.log(words[i]);
                    if (regex.test(words[i])) {
                        this.$emit('recognized', words[i]);
                        result = words[i];
                        break;
                    }
                }
                this.$emit('recognized', result);
                this.processing = false;
                this.speech(result);
                await worker.terminate();
            })();
        },
        speech (result) {
            result = result ? result.split('').join('-') : 'เศร้าใจจังครับ';
            const synth = window.speechSynthesis;
            const utterThis = new SpeechSynthesisUtterance(result);
            const voices = synth.getVoices();

            for(var i = 0; i < voices.length ; i++) {
                if(voices[i].name === 'Kanya') {
                    utterThis.voice = voices[i];
                    break;
                }
            }

            utterThis.pitch = 1;
            utterThis.rate = 1;
            // utterThis.voice = 'th-TH';
            synth.speak(utterThis);
        }
    }
};
</script>
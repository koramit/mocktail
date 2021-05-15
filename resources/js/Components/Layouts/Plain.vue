<template>
    <div>
        <slot />
    </div>
</template>

<script>
export default {
    watch: {
        '$page.props.flash': {
            immediate: true,
            deep: true,
            handler() { document.title = this.$page.props.flash.title; }
        },
    },
    created () {
        this.baseUrl = document.querySelector('meta[name=base-url]').content;
        var lastTimeCheckSessionTimeout = Date.now();
        const endpoint =  this.baseUrl + '/session-timeout';
        const sessionLifetimeSeconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content);
        window.addEventListener('focus', () => {
            let timeDiff = Date.now() - lastTimeCheckSessionTimeout;
            if ( (timeDiff) > (sessionLifetimeSeconds) ) {
                window.axios
                    .post(endpoint)
                    .then(() => lastTimeCheckSessionTimeout = Date.now())
                    .catch(() => location.reload());
            }
        });
        this.eventBus.on('need-confirm', (cinfigs) => {
            setTimeout(() => this.$nextTick(() => this.$refs.confirmForm.open(cinfigs)), 300);
        });
    },
    mounted () {
        this.$nextTick(() => {
            const pageLoadingIndicator = document.getElementById('page-loading-indicator');
            if (pageLoadingIndicator) {
                pageLoadingIndicator.remove();
            }

            setTimeout(() => window.print(), 500);
        });
    },
};
</script>
<style>
@media print {
    @page {
        /* margin: 0.5cm 1cm 0.5cm 1cm; */
        margin: 1cm;
        padding: 0;
    }

    body {
        margin: 0;
        width: 210mm;
        height: 296mm;
    }

    .print-p-0 {
        padding: 0;
    }

    .text-print-size {
        font-size: 10pt!important;
    }

    .avoid-page-break {
        display: block;
        page-break-inside: avoid;
    }

    label.form-label {
        font-size: 10pt!important;
        padding-bottom: .25rem;
        font-weight: 400;
    }
}
</style>
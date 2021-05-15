<template>
    <div>
        <slot />
        <div class="flex fixed w-full bottom-0">
            <div
                class="w-1/4"
                id="footer-left"
            >
                <slot name="footer-left" />
            </div>
            <div
                class="w-3/4 text-right"
                id="footer-right"
            >
                <slot name="footer-right" />
            </div>
        </div>
    </div>
</template>
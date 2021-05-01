<template>
    <div>
        <!-- main contailner, flex makes its childs extend full h -->
        <div class="md:h-screen md:flex md:flex-col">
            <!-- this is navbar, with no shrink (fixed width) -->
            <div class="md:flex md:flex-shrink-0 sticky top-0 z-30">
                <!-- left navbar on desktop and full bar on mobile -->
                <div class="bg-dark-theme-light text-white md:flex-shrink-0 md:w-56 xl:w-64 px-4 py-2 flex items-center justify-between md:justify-center">
                    <!-- the logo -->
                    <inertia-link
                        class=" inline-block"
                        :href="`${baseUrl}/home`"
                    >
                        <span class="font-bold text-lg md:text-4xl italic">Mocktail</span>
                    </inertia-link>
                    <!-- title display on mobile -->
                    <div class="text-soft-theme-light text-sm md:hidden">
                        {{ $page.props.flash.title }}
                    </div>
                    <!-- hotel menu on mobile -->
                    <button
                        class="md:hidden text-soft-theme-light"
                        @click="mobileMenuVisible = !mobileMenuVisible"
                    >
                        <svg
                            class="w-6 h-6"
                            :class="{ 'animate-spin': typing }"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 576 512"
                        ><path
                            fill="currentColor"
                            d="M560 64c8.84 0 16-7.16 16-16V16c0-8.84-7.16-16-16-16H16C7.16 0 0 7.16 0 16v32c0 8.84 7.16 16 16 16h15.98v384H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h240v-80c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v80h240c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16h-16V64h16zm-304 44.8c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zm0 96c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zm-128-96c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zM179.2 256h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8zM192 384c0-53.02 42.98-96 96-96s96 42.98 96 96H192zm256-140.8c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm0-96c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4z"
                        /></svg>
                    </button>
                </div>
                <!-- right navbar on desktop -->
                <div class="hidden md:flex w-full font-semibold text-dark-theme-light bg-alt-theme-light border-b sticky top-0 z-30 p-4 md:py-0 md:px-12 justify-between items-center">
                    <!-- title display on desktop -->
                    <div class="mt-1 mr-4">
                        {{ $page.props.flash.title }}
                    </div>
                    <!-- username and menu -->
                    <dropdown>
                        <template #default>
                            <div class="flex items-center cursor-pointer select-none group">
                                <div class="group-hover:text-bitter-theme-light focus:text-bitter-theme-light mr-1 whitespace-no-wrap">
                                    <span>{{ $page.props.user.name }}</span>
                                </div>
                                <icon
                                    class="w-4 h-4 group-hover:text-bitter-theme-light focus:text-bitter-theme-light"
                                    name="double-down"
                                />
                            </div>
                        </template>
                        <template #dropdown>
                            <div class="mt-2 py-2 shadow-xl min-w-max bg-thick-theme-light text-white cursor-pointer rounded text-sm">
                                <template v-if="hasRoles">
                                    <inertia-link
                                        class="block px-6 py-2 hover:bg-dark-theme-light hover:text-soft-theme-light"
                                        :href="`${baseUrl}/home`"
                                        v-if="! currentPage('home')"
                                    >
                                        หน้าหลัก
                                    </inertia-link>
                                </template>
                                <inertia-link
                                    class="w-full font-semibold text-left px-6 py-2 hover:bg-dark-theme-light hover:text-soft-theme-light"
                                    :href="`${baseUrl}/logout`"
                                    method="post"
                                    as="button"
                                    type="button"
                                >
                                    ออกจากระบบ
                                </inertia-link>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <!-- menu on mobile -->
                <div
                    class="md:hidden w-5/6 block fixed left-0 inset-y-0 overflow-y-scroll text-soft-theme-light bg-dark-theme-light rounded-tr rounded-br shadow-md transition-transform transform duration-300 ease-in-out"
                    :class="{ '-translate-x-full': !mobileMenuVisible }"
                >
                    <div class="p-4 relative min-h-full">
                        <!-- username and menu -->
                        <div
                            class="flex flex-col text-center"
                            @click="mobileMenuVisible = false"
                        >
                            <div class="flex justify-center mt-2">
                                <div
                                    class="w-12 h-12 rounded-full overflow-hidden border-bitter-theme-light border-2"
                                    v-if="!avatarSrcError"
                                >
                                    <img
                                        :src="`${$page.props.user.avatar}`"
                                        alt="C"
                                        @error="avatarSrcError = true"
                                    >
                                </div>
                            </div>
                            <span class="inline-block py-1 text-white">{{ $page.props.user.name }}</span>
                            <template v-if="hasRoles">
                                <inertia-link
                                    class="block py-1"
                                    :href="`${baseUrl}/home`"
                                    v-if="! currentPage('home')"
                                >
                                    หน้าหลัก
                                </inertia-link>
                            </template>
                            <inertia-link
                                class="block py-1"
                                :href="`${baseUrl}/logout`"
                                method="post"
                                as="button"
                                type="button"
                            >
                                ออกจากระบบ
                            </inertia-link>
                        </div>
                        <hr class="my-4">
                        <main-menu
                            @click="mobileMenuVisible = false"
                            :url="url()"
                        />
                        <action-menu @action-clicked="actionClicked" />
                    </div>
                    <!-- cookie-bite menu on mobile -->
                    <!-- <div class="sticky bottom-0 px-4 py-2 flex justify-items-center bg-dark-theme-light">
                        <button
                            class="block mx-auto text-bitter-theme-light"
                            @click="mobileMenuVisible = !mobileMenuVisible"
                        >
                            <svg
                                class="w-6 h-6"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            ><path
                                fill="currentColor"
                                d="M510.52 255.82c-69.97-.85-126.47-57.69-126.47-127.86-70.17 0-127-56.49-127.86-126.45-27.26-4.14-55.13.3-79.72 12.82l-69.13 35.22a132.221 132.221 0 0 0-57.79 57.81l-35.1 68.88a132.645 132.645 0 0 0-12.82 80.95l12.08 76.27a132.521 132.521 0 0 0 37.16 72.96l54.77 54.76a132.036 132.036 0 0 0 72.71 37.06l76.71 12.15c27.51 4.36 55.7-.11 80.53-12.76l69.13-35.21a132.273 132.273 0 0 0 57.79-57.81l35.1-68.88c12.56-24.64 17.01-52.58 12.91-79.91zM176 368c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm32-160c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm160 128c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"
                            /></svg>
                        </button>
                    </div> -->
                </div>
            </div>
            <!-- this is content -->
            <div class="md:flex md:flex-grow md:overflow-hidden">
                <!-- this is sidebar menu on desktop -->
                <main-menu
                    :url="url()"
                    class="hidden md:block bg-thick-theme-light flex-shrink-0 w-56 xl:w-64 py-12 px-6 overflow-y-auto"
                />
                <!-- this is main page -->
                <div
                    class="w-full p-4 md:overflow-y-auto sm:p-8 md:p-16"
                    scroll-region
                >
                    <!-- <flash-messages /> -->
                    <div
                        v-if="$page.props.flash.messages"
                        class="flex items-center rounded-tl-lg rounded-tr-lg border-8 border-t-0 border-l-0 border-r-0 shadow text-xs text-thick-theme-light p-4"
                        :class="{
                            'border-alt-theme-light': $page.props.flash.messages.status === 'info'
                        }"
                    >
                        <icon
                            class="block w-12 h-12 text-alt-theme-light"
                            name="info-circle"
                            v-if="$page.props.flash.messages.status === 'info'"
                        />
                        <div class="ml-4">
                            <div
                                class="flex my-1 text-thick-theme-light"
                                v-for="(message, key) in $page.props.flash.messages.messages"
                                :key="key"
                            >
                                <p>๏</p>
                                <p
                                    class="px-2"
                                    v-html="message"
                                />
                            </div>
                        </div>
                    </div>

                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Dropdown from '@/Components/Helpers/Dropdown';
import Icon from '@/Components/Helpers/Icon';
import MainMenu from '@/Components/Helpers/MainMenu';
import ActionMenu from '@/Components/Helpers/ActionMenu';
// import { onMounted } from 'vue';
// import axios from 'axios';
export default {
    components: { Dropdown, Icon, MainMenu, ActionMenu },
    computed: {
        hasRoles() {
            return this.$page.props.user.abilities.length;
        }
    },
    watch: {
        '$page.props.flash': {
            immediate: true,
            deep: true,
            handler() { document.title = this.$page.props.flash.title; }
        },
    },
    data () {
        return {
            mobileMenuVisible: false,
            avatarSrcError: false,
            typing: false,
        };
    },
    created () {
        this.baseUrl = document.querySelector('meta[name=base-url]').content;
        var lastTimeCheckSessionTimeout = Date.now();
        const endpoint =  this.baseUrl + '/session-timeout';
        const sessionLifetimeSeconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content);
        window.addEventListener('focus', () => {
            let timeDiff = Date.now() - lastTimeCheckSessionTimeout;
            if ( (timeDiff) > (sessionLifetimeSeconds) ) {
                axios.post(endpoint)
                    .then(() => lastTimeCheckSessionTimeout = Date.now())
                    .catch(() => location.reload());
            }
        });
        this.eventBus.on('typing', () => {
            if (! this.typing) {
                this.typing = true;
                console.log('roll the cookie');
            }
        });
        this.eventBus.on('typing-stopped', () => this.typing = false);
    },
    mounted () {
        this.$nextTick(() => {
            const pageLoadingIndicator = document.getElementById('page-loading-indicator');
            if (pageLoadingIndicator) {
                pageLoadingIndicator.remove();
            }
        });
    },
    // setup () {
    //     var lastTimeCheckSessionTimeout = Date.now();
    //     const endpoint = document.querySelector('meta[name=base-url]').content + '/session-timeout';
    //     const sessionLifetimeSeconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content);
    //     window.addEventListener('focus', () => {
    //         let timeDiff = Date.now() - lastTimeCheckSessionTimeout;
    //         if ( (timeDiff) > (sessionLifetimeSeconds) ) {
    //             axios.post(endpoint)
    //                 .then(() => lastTimeCheckSessionTimeout = Date.now())
    //                 .catch(() => location.reload());
    //         }
    //     });

    //     onMounted (() => {
    //         const pageLoadingIndicator = document.getElementById('page-loading-indicator');
    //         if (pageLoadingIndicator) {
    //             pageLoadingIndicator.remove();
    //         }
    //     });
    // },
    methods: {
        url() {
            return location.pathname.substr(1);
        },
        actionClicked (action) {
            this.mobileMenuVisible = false;

            setTimeout(() => {
                this.eventBus.emit('action-clicked', action);
            }, 300); // equal to animate duration
        },
        currentPage(route) {
            return location.pathname.substr(1) === route;
        }
    }
};
</script>
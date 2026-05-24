<script setup lang="ts">
import { useBodyClass } from '@/composables/useBodyClass';

useBodyClass('authentication-bg');
useBodyClass('position-relative');
</script>

<template>
    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="100%"
            height="100%"
            preserveAspectRatio="none"
            viewBox="0 0 1920 1024"
        >
            <g fill="none">
                <rect width="1920" height="1024" x="0" y="0" fill="url(#OpsAuthGradient)"></rect>
            </g>
            <defs>
                <linearGradient
                    id="OpsAuthGradient"
                    x1="11.67%"
                    y1="-21.87%"
                    x2="88.33%"
                    y2="121.88%"
                    gradientUnits="userSpaceOnUse"
                >
                    <stop stop-color="#0e2a47" offset="0"></stop>
                    <stop stop-color="#00459e" offset="1"></stop>
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header pt-4 text-center">
                            <div class="auth-brand ops-brand mb-0">
                                <span class="ops-brand-text">OPS Platform</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        <span class="text-white-50">{{ new Date().getFullYear() }} © OPS Platform</span>
    </footer>
</template>

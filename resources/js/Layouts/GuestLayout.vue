<script setup>
//import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import SecureCloudFile from '@/assets/SecureCloudFile.png'
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <img :src="SecureCloudFile" class="h-28 w-28 fill-current text-gray-500"></img>
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>

<script setup>
import Dialogue from '@/Components/Dialogue.vue';
import FileLister from '@/Components/FileLister.vue';
import FileUploadForm from '@/Components/FileUploadForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CloudArrowUpIcon } from '@heroicons/vue/24/outline'

import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
const open = ref(false);

function confirm() {
  alert('Confirmed!')
  open.value = false
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-screen-xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="w-full flex justify-end">
                            <button @click="open = true" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center gap-2">
                            <CloudArrowUpIcon class="w-5 h-5" />
                            Upload File
                            </button>
                        </div>
                        <Dialogue v-model="open" title="File Upload">
                            <!--Modal heree-->
                            <FileUploadForm @close-dialog="open = false" />
                            <!--Modal End heree-->
                            <template #footer>
                            <button @click="open = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                                Cancel
                            </button>
                            </template>
                        </Dialogue>
                        <FileLister/>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

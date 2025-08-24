<script setup>

import FileLister from '@/Components/FileLister.vue';
import FileUploadForm from '@/Components/FileUploadForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CloudArrowUpIcon } from '@heroicons/vue/24/outline'

import { Head } from '@inertiajs/vue3';
import { ref,watch } from 'vue'
const open = ref(false);

const searchQuery = ref('');

const dialogRef = ref(null)

watch(open, (val) => {
  if (val) dialogRef.value.showModal()
  else dialogRef.value.close()
})

function closeDialog() {
  open.value = false
  dialogRef.value.close()
}

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
                Your Files & Documents
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-screen-xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
    
                        <div class="top-bar">
                        <div class="search-upload-wrapper">
                            <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Search files..." 
                            class="search-input"
                            />
                            <button @click="open = true" class="btn-upload flex items-center gap-2" title="Upload File">
                            <CloudArrowUpIcon class="w-5 h-5" />
                            Upload
                            </button>
                        </div>
                        </div>

                        <dialog ref="dialogRef" class="dialog-element rounded-lg p-6 w-1/2 relative shadow-lg">
                            <!-- Header with title and close button -->
                            <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">File Upload</h2>
                            <button @click="closeDialog" class="text-gray-500 hover:text-gray-700">✕</button>
                            </div>

                            <!-- Modal content slot -->
                            <FileUploadForm @close-dialog="closeDialog" />

                            <!-- Footer slot -->
                            <div class="flex justify-end gap-2 mt-4">
                            <button @click="closeDialog" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                                Cancel
                            </button>
                            </div>
                        </dialog>
                        <FileLister :search-query="searchQuery"/>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.dialog-element::backdrop {
  background: rgba(0, 0, 0, 0.5);
}
.dialog-element {
  border: none;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.search-upload-wrapper {
  display: flex;
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #d1d5db;
  background-color: white;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.search-input {
  flex: 1;
  padding: 10px 14px;
  border: none;
  outline: none;
  font-size: 1rem;
}

.search-input::placeholder {
  color: #9ca3af;
}

.btn-upload {
  background-color: #3b82f6; /* blue-500 */
  color: white;
  border: none;
  padding: 0 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-upload:hover {
  background-color: #2563eb; /* blue-600 */
}
</style>

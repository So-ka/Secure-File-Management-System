<template>
  <div
    @dragover.prevent
    @dragenter.prevent
    @drop.prevent="handleDrop"
    class="border-2 border-dashed border-gray-400 p-6 rounded-lg text-center cursor-pointer"
  >
    <p>Drag and drop your files here, or click to select</p>
    <input type="file" multiple ref="fileInput" class="hidden" @change="handleFiles" />
    <button
      class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
      @click="$refs.fileInput.click()"
    >
      Select Files
    </button>
  </div>

  <div class="mt-6 gap-4">

    <div
      v-for="(file, index) in files"
      :key="index"
      class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-lg transition gap-2 mb-3"
    >
      <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-700 mr-4">
        <component :is="getIconComponent(file)" class="w-6 h-6"></component>
      </div>
      <div class="flex-1">
        <p class="text-sm font-medium truncate">{{ file.name }}</p>
        <p class="text-xs text-gray-500">{{ (file.size / 1024 / 1024).toFixed(2) }} MB</p>
      </div>
      <button @click="removeFile(index)" class="text-red-500 ml-3 hover:text-red-700">
        ✕
      </button>
    </div>
  </div>

  <button
    @click="uploadFiles"
    class="mt-6 bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition"
    :disabled="files.length === 0"
  >
    Upload
  </button>
</template>

<script setup>
import { ref } from 'vue'

// Use heroicons (or your own icons)
import { DocumentIcon, DocumentTextIcon, DocumentChartBarIcon, DocumentPlusIcon } from '@heroicons/vue/24/outline'

const files = ref([])
const emit = defineEmits(['close-dialog'])

const allowedTypes = [
  'application/pdf',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'image/png',
  'image/jpeg',
  'application/vnd.oasis.opendocument.text'
]

function handleFiles(event) {
  let selectedFiles = Array.from(event.target.files)

  if (files.value.length + selectedFiles.length > 5) {
    alert('You can upload maximum 5 files at a time.')
    return
  }

  selectedFiles.forEach(file => {
    if (!allowedTypes.includes(file.type)) {
      alert(`File type not allowed: ${file.name}`)
      return
    }
    files.value.push(file)
  })
}

function handleDrop(event) {
  const droppedFiles = Array.from(event.dataTransfer.files)
  const fakeEvent = { target: { files: droppedFiles } }
  handleFiles(fakeEvent)
}

function removeFile(index) {
  files.value.splice(index, 1)
}

// Map file extensions to icons
function getIconComponent(file) {
  const ext = file.name.split('.').pop().toLowerCase()
  switch (ext) {
    case 'pdf':
      return DocumentIcon
    case 'docx':
    case 'odt':
      return DocumentTextIcon
    case 'png':
    case 'jpg':
    case 'jpeg':
      return DocumentChartBarIcon
    default:
      return DocumentPlusIcon
  }
}

async function uploadFiles() {
  const formData = new FormData()
  files.value.forEach(file => formData.append('files[]', file))

  try {
    const response = await axios.post('/upload-files', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    emit('close-dialog');
    alert(response.data.message)
    files.value = []
  } catch (error) {
    console.error(error)
    alert('Upload failed!')
  }
  
}
</script>

<style scoped>
/* Optional: Add hover effects, shadows, etc. */
</style>

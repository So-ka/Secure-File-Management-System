<!-- components/Dialog.vue -->
<template>
  <transition name="fade">
    <div
      v-if="modelValue"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white rounded-lg p-6 w-1/2 relative shadow-lg">
        <!-- Header with optional title -->
        <div class="flex justify-between items-center mb-4">
          <h2 v-if="title" class="text-lg font-semibold">{{ title }}</h2>
          <button @click="$emit('update:modelValue', false)" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <!-- Slot for custom content -->
        <div class="mb-4">
          <slot />
        </div>

        <!-- Optional footer slot -->
        <div v-if="$slots.footer" class="flex justify-end gap-2">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
defineProps({
  modelValue: Boolean,
  title: String,
})
defineEmits(['update:modelValue'])
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

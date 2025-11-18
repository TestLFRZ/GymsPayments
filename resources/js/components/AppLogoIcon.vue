<script setup>
defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
  logoUrl: {
    type: String,
    default: '/weight.png',
  },
});
</script>

<template>
  <div
    class="flex aspect-square items-center justify-center rounded-xl bg-red-600 px-3 py-3 shadow-[0_6px_14px_rgba(0,0,0,0.35)] dark:bg-red-500"
    :class="props.class"
    v-bind="$attrs"
  >
    <!-- Imagen del logo -->
    <img
      :src="logoUrl"
      alt="Logo"
      class="h-6 w-6 object-contain"
    />
  </div>
</template>

<script setup>
defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
  label: {
    type: String,
    default: 'GP',
  },
});
</script>

<template>
  <div
    class="flex aspect-square items-center justify-center rounded-xl bg-red-600 px-3 py-3 text-xs font-black uppercase tracking-[0.2em] text-white shadow-[0_6px_14px_rgba(0,0,0,0.35)] dark:bg-red-500"
    :class="props.class"
    v-bind="$attrs"
  >
    {{ props.label }}
  </div>
</template>

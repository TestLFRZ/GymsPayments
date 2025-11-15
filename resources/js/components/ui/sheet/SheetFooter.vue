<script setup>
import { cn } from '@/lib/utils.js';

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <div
    data-slot="sheet-footer"
    :class="cn('mt-auto flex flex-col gap-2 p-4', props.class)
    "
  >
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/lib/utils.js';
import { DialogTitle } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <DialogTitle
    data-slot="dialog-title"
    v-bind="$attrs"
    :class="cn('text-lg leading-none font-semibold', props.class)"
  >
    <slot />
  </DialogTitle>
</template>

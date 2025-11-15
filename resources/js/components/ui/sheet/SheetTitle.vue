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
    data-slot="sheet-title"
    :class="cn('text-foreground font-semibold', props.class)"
    v-bind="$attrs"
  >
    <slot />
  </DialogTitle>
</template>

<script setup>
import { cn } from '@/lib/utils.js';
import { DialogDescription } from 'reka-ui';

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
  <DialogDescription
    data-slot="sheet-description"
    :class="cn('text-muted-foreground text-sm', props.class)"
    v-bind="$attrs"
  >
    <slot />
  </DialogDescription>
</template>

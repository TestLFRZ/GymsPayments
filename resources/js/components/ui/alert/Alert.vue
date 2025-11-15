<script setup>
import { cn } from '@/lib/utils.js';
import { alertVariants } from '.';

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
  variant: {
    type: String,
    default: 'default',
  },
});
</script>

<template>
  <div
    data-slot="alert"
    :class="cn(alertVariants({ variant: props.variant }), props.class)"
    role="alert"
  >
    <slot />
  </div>
</template>

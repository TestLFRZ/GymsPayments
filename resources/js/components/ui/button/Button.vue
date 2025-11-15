<script setup>
import { cn } from '@/lib/utils.js';
import { Primitive } from 'reka-ui';
import { buttonVariants } from '.';

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'button',
  },
  asChild: {
    type: Boolean,
    default: undefined,
  },
  variant: {
    type: String,
    default: undefined,
  },
  size: {
    type: String,
    default: undefined,
  },
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <Primitive
    data-slot="button"
    :as="props.as"
    :as-child="props.asChild"
    :class="cn(buttonVariants({ variant: props.variant, size: props.size }), props.class)"
  >
    <slot />
  </Primitive>
</template>

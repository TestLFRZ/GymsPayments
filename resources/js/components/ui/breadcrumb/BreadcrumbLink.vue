<script setup>
import { cn } from '@/lib/utils.js';
import { Primitive } from 'reka-ui';
import { useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'a',
  },
  asChild: {
    type: Boolean,
    default: false,
  },
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});

const attrs = useAttrs();
</script>

<template>
  <Primitive
    data-slot="breadcrumb-link"
    :as="props.as"
    :as-child="props.asChild"
    v-bind="attrs"
    :class="cn('hover:text-foreground transition-colors', props.class)"
  >
    <slot />
  </Primitive>
</template>

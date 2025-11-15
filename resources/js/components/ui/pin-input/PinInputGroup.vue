<script setup>
import { cn } from '@/lib/utils.js';
import { Primitive } from 'reka-ui';
import { useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});

const attrs = useAttrs();
</script>

<template>
  <Primitive
    data-slot="pin-input-group"
    v-bind="attrs"
    :class="cn('flex items-center', props.class)"
  >
    <slot />
  </Primitive>
</template>

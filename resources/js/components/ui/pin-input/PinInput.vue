<script setup>
import { cn } from '@/lib/utils.js';
import { PinInputRoot } from 'reka-ui';
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
  <PinInputRoot
    data-slot="pin-input"
    v-bind="attrs"
    :class="cn('flex items-center gap-2 has-disabled:opacity-50 disabled:cursor-not-allowed', props.class)"
  >
    <slot />
  </PinInputRoot>
</template>

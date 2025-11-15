<script setup>
import { TooltipProvider } from 'reka-ui';
import { useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  delayDuration: {
    type: Number,
    default: 0,
  },
});

const attrs = useAttrs();
</script>

<template>
  <TooltipProvider
    v-bind="attrs"
    :delay-duration="props.delayDuration"
  >
    <slot />
  </TooltipProvider>
</template>

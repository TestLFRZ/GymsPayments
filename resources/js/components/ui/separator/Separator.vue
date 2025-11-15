<script setup>
import { cn } from '@/lib/utils.js';
import { Separator } from 'reka-ui';
import { useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  orientation: {
    type: String,
    default: 'horizontal',
  },
  decorative: {
    type: Boolean,
    default: true,
  },
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});

const attrs = useAttrs();
</script>

<template>
  <Separator
    data-slot="separator-root"
    :orientation="props.orientation"
    :decorative="props.decorative"
    v-bind="attrs"
    :class="
      cn(
        `bg-border shrink-0 data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px`,
        props.class,
      )
    "
  />
</template>

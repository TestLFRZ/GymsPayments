<script setup>
import { cn } from '@/lib/utils.js';
import { PinInputInput } from 'reka-ui';
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
  <PinInputInput
    data-slot="pin-input-slot"
    v-bind="attrs"
    :class="cn('border-input focus:border-ring focus:ring-ring/50 focus:aria-invalid:ring-destructive/20 dark:bg-input/30 dark:focus:aria-invalid:ring-destructive/40 aria-invalid:border-destructive focus:aria-invalid:border-destructive relative flex h-9 w-9 items-center justify-center border-y border-r text-sm shadow-xs transition-all outline-none text-center first:rounded-l-md first:border-l last:rounded-r-md focus:z-10 focus:ring-[3px]', props.class)"
  />
</template>

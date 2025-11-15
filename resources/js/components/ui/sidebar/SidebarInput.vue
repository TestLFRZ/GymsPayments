<script setup>
import { cn } from '@/lib/utils.js';
import { Input } from '@/components/ui/input';

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
  <Input
    data-slot="sidebar-input"
    data-sidebar="input"
    v-bind="$attrs"
    :class="cn(
      'bg-background h-8 w-full shadow-none',
      props.class,
    )"
  >
    <slot />
  </Input>
</template>

<script setup>
import { cn } from '@/lib/utils.js';
import { DropdownMenuSeparator } from 'reka-ui';

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
  <DropdownMenuSeparator
    data-slot="dropdown-menu-separator"
    v-bind="$attrs"
    :class="cn('bg-border -mx-1 my-1 h-px', props.class)"
  />
</template>

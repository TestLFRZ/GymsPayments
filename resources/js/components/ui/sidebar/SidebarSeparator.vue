<script setup>
import { cn } from '@/lib/utils.js';
import { Separator } from '@/components/ui/separator';

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
  <Separator
    data-slot="sidebar-separator"
    data-sidebar="separator"
    v-bind="$attrs"
    :class="cn('bg-sidebar-border mx-2 w-auto', props.class)"
  >
    <slot />
  </Separator>
</template>

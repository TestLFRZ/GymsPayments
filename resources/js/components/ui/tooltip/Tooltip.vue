<script setup>
import { TooltipRoot } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <TooltipRoot
    data-slot="tooltip"
    v-bind="$attrs"
  >
    <slot />
  </TooltipRoot>
</template>

<script setup>
import { CollapsibleTrigger } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <CollapsibleTrigger
    data-slot="collapsible-trigger"
    v-bind="$attrs"
  >
    <slot />
  </CollapsibleTrigger>
</template>

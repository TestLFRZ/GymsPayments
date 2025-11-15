<script setup>
import { CollapsibleRoot } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <CollapsibleRoot
    v-slot="{ open }"
    data-slot="collapsible"
    v-bind="$attrs"
  >
    <slot :open="open" />
  </CollapsibleRoot>
</template>

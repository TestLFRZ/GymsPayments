<script setup>
import { DialogRoot } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DialogRoot
    data-slot="sheet"
    v-bind="$attrs"
  >
    <slot />
  </DialogRoot>
</template>

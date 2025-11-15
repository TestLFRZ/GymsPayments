<script setup>
import { DialogClose } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DialogClose
    data-slot="dialog-close"
    v-bind="$attrs"
  >
    <slot />
  </DialogClose>
</template>

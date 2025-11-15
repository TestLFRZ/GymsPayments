<script setup>
import { DialogTrigger } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DialogTrigger
    data-slot="dialog-trigger"
    v-bind="$attrs"
  >
    <slot />
  </DialogTrigger>
</template>

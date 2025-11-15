<script setup>
import { DropdownMenuRoot } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DropdownMenuRoot
    data-slot="dropdown-menu"
    v-bind="$attrs"
  >
    <slot />
  </DropdownMenuRoot>
</template>

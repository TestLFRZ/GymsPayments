<script setup>
import { DropdownMenuSub } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DropdownMenuSub data-slot="dropdown-menu-sub" v-bind="$attrs">
    <slot />
  </DropdownMenuSub>
</template>

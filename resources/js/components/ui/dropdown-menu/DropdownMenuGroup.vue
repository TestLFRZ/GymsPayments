<script setup>
import { DropdownMenuGroup } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DropdownMenuGroup
    data-slot="dropdown-menu-group"
    v-bind="$attrs"
  >
    <slot />
  </DropdownMenuGroup>
</template>

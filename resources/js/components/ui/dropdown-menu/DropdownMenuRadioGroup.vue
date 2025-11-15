<script setup>
import { DropdownMenuRadioGroup } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <DropdownMenuRadioGroup
    data-slot="dropdown-menu-radio-group"
    v-bind="$attrs"
  >
    <slot />
  </DropdownMenuRadioGroup>
</template>

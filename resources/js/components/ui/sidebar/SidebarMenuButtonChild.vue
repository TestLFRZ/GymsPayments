<script setup>
import { cn } from '@/lib/utils.js';
import { Primitive } from 'reka-ui';
import { sidebarMenuButtonVariants } from '.';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'button',
  },
  asChild: {
    type: Boolean,
    default: false,
  },
  variant: {
    type: String,
    default: 'default',
  },
  size: {
    type: String,
    default: 'default',
  },
  isActive: {
    type: Boolean,
    default: false,
  },
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <Primitive
    data-slot="sidebar-menu-button"
    data-sidebar="menu-button"
    :data-size="props.size"
    :data-active="props.isActive"
    :class="cn(sidebarMenuButtonVariants({ variant: props.variant, size: props.size }), props.class)"
    :as="props.as"
    :as-child="props.asChild"
    v-bind="$attrs"
  >
    <slot />
  </Primitive>
</template>

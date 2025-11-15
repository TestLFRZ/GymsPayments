<script setup>
import { cn } from '@/lib/utils.js';
import { Primitive } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'a',
  },
  asChild: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'md',
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
    data-slot="sidebar-menu-sub-button"
    data-sidebar="menu-sub-button"
    :as="props.as"
    :as-child="props.asChild"
    :data-size="props.size"
    :data-active="props.isActive"
    :class="cn(
      'text-sidebar-foreground ring-sidebar-ring hover:bg-sidebar-accent hover:text-sidebar-accent-foreground active:bg-sidebar-accent active:text-sidebar-accent-foreground [&>svg]:text-sidebar-accent-foreground flex h-7 min-w-0 -translate-x-px items-center gap-2 overflow-hidden rounded-md px-2 outline-hidden focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
      'data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground',
      props.size === 'sm' && 'text-xs',
      props.size === 'md' && 'text-sm',
      'group-data-[collapsible=icon]:hidden',
      props.class,
    )"
    v-bind="$attrs"
  >
    <slot />
  </Primitive>
</template>

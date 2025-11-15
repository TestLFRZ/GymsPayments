<script setup>
import { cn } from '@/lib/utils.js';
import { NavigationMenuItem } from 'reka-ui';
import { useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});

const attrs = useAttrs();
</script>

<template>
  <NavigationMenuItem
    data-slot="navigation-menu-item"
    v-bind="attrs"
    :class="cn('relative', props.class)"
  >
    <slot />
  </NavigationMenuItem>
</template>

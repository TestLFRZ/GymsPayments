<script setup>
import { cn } from '@/lib/utils.js';
import { NavigationMenuList } from 'reka-ui';
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
  <NavigationMenuList
    data-slot="navigation-menu-list"
    v-bind="attrs"
    :class="
      cn(
        'group flex flex-1 list-none items-center justify-center gap-1',
        props.class,
      )
    "
  >
    <slot />
  </NavigationMenuList>
</template>

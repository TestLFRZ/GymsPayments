<script setup>
import { cn } from '@/lib/utils.js';
import { ChevronDown } from 'lucide-vue-next';
import { NavigationMenuTrigger } from 'reka-ui';
import { useAttrs } from 'vue';
import { navigationMenuTriggerStyle } from '.';

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
  <NavigationMenuTrigger
    data-slot="navigation-menu-trigger"
    v-bind="attrs"
    :class="cn(navigationMenuTriggerStyle(), 'group', props.class)"
  >
    <slot />
    <ChevronDown
      class="relative top-[1px] ml-1 size-3 transition duration-300 group-data-[state=open]:rotate-180"
      aria-hidden="true"
    />
  </NavigationMenuTrigger>
</template>

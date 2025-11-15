<script setup>
import { cn } from '@/lib/utils.js';
import { reactiveOmit } from '@vueuse/core';
import { NavigationMenuRoot, useForwardPropsEmits } from 'reka-ui';
import NavigationMenuViewport from './NavigationMenuViewport.vue';

const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
  viewport: {
    type: Boolean,
    default: true,
  },
});

const delegatedProps = reactiveOmit(props, 'class', 'viewport');
const forwarded = useForwardPropsEmits(delegatedProps, defineEmits());
</script>

<template>
  <NavigationMenuRoot
    data-slot="navigation-menu"
    :data-viewport="viewport"
    v-bind="forwarded"
    :class="cn('group/navigation-menu relative flex max-w-max flex-1 items-center justify-center', props.class)"
  >
    <slot />
    <NavigationMenuViewport v-if="viewport" />
  </NavigationMenuRoot>
</template>

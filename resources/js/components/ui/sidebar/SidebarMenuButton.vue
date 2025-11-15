<script setup>
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { computed } from 'vue';
import SidebarMenuButtonChild from './SidebarMenuButtonChild.vue';
import { useSidebar } from './utils';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  tooltip: {
    type: [String, Object],
    default: undefined,
  },
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

const { isMobile, state } = useSidebar();

const delegatedProps = computed(() => {
  const { tooltip, ...delegated } = props;
  return delegated;
});
</script>

<template>
  <SidebarMenuButtonChild
    v-if="!props.tooltip"
    v-bind="{ ...delegatedProps, ...$attrs }"
  >
    <slot />
  </SidebarMenuButtonChild>

  <Tooltip v-else>
    <TooltipTrigger as-child>
      <SidebarMenuButtonChild v-bind="{ ...delegatedProps, ...$attrs }">
        <slot />
      </SidebarMenuButtonChild>
    </TooltipTrigger>
    <TooltipContent
      side="right"
      align="center"
      :hidden="state !== 'collapsed' || isMobile"
    >
      <template v-if="typeof props.tooltip === 'string'">
        {{ props.tooltip }}
      </template>
      <component
        :is="props.tooltip"
        v-else
      />
    </TooltipContent>
  </Tooltip>
</template>

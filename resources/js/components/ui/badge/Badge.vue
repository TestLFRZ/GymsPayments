<script setup>
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils.js";
import { badgeVariants } from ".";

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'span',
  },
  variant: {
    type: String,
    default: undefined,
  },
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <Primitive
    data-slot="badge"
    :as="props.as"
    :class="cn(badgeVariants({ variant: props.variant }), props.class)"
  >
    <slot />
  </Primitive>
</template>

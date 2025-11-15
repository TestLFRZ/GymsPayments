<script setup>
import { Minus } from 'lucide-vue-next';
import { Primitive } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <Primitive
    data-slot="pin-input-separator"
    v-bind="$attrs"
  >
    <slot>
      <Minus />
    </slot>
  </Primitive>
</template>

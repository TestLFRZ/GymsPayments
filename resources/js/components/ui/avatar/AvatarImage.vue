<script setup>
import { AvatarImage } from 'reka-ui';

defineOptions({
  inheritAttrs: false,
});
</script>

<template>
  <AvatarImage
    data-slot="avatar-image"
    v-bind="$attrs"
    class="aspect-square size-full"
  >
    <slot />
  </AvatarImage>
</template>

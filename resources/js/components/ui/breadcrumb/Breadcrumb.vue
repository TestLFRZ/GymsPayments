<script setup>
const props = defineProps({
  class: {
    type: [String, Array, Object],
    default: undefined,
  },
});
</script>

<template>
  <nav
    aria-label="breadcrumb"
    data-slot="breadcrumb"
    :class="props.class"
  >
    <slot />
  </nav>
</template>

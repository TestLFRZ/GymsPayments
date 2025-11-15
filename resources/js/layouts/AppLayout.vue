<script setup>
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useTenantStore } from '@/stores/tenant.js';

defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const tenantStore = useTenantStore();

watch(
    () => page.props.auth?.user?.tenant ?? null,
    (tenant) => {
        if (tenant) {
            tenantStore.setTenant(tenant);
        } else {
            tenantStore.clearTenant();
        }
    },
    { immediate: true },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>

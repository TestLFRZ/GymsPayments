<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { usePlanStore } from '@/stores/plans.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { computed, ref, watch } from 'vue';

const breadcrumbs = [
    { title: 'Panel', href: '/dashboard' },
    { title: 'Planes', href: '/plans' },
];

const planStore = usePlanStore();
const tenantStore = useTenantStore();
const { plans, isLoading } = storeToRefs(planStore);
const { currentTenant } = storeToRefs(tenantStore);

const showInactive = ref(false);

const visiblePlans = computed(() =>
    showInactive.value ? plans.value : plans.value.filter((plan) => plan.is_active),
);

watch(
    () => tenantStore.slug,
    (slug) => {
        if (slug) {
            planStore.fetchPlans(showInactive.value);
        }
    },
    { immediate: true },
);

watch(showInactive, () => {
    if (tenantStore.slug) {
        planStore.fetchPlans(showInactive.value);
    }
});
</script>

<template>
    <Head title="Planes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="space-y-6 p-6">
            <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">{{ currentTenant?.name ?? 'Espacio multi-tenant' }}</p>
                    <h1 class="text-2xl font-semibold">Planes de membresía</h1>
                    <p class="text-sm text-muted-foreground">
                        Crea y ajusta precios para cada segmento de clientes.
                    </p>
                </div>
                <button
                    class="inline-flex items-center rounded-lg border border-transparent bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm transition hover:bg-primary/90"
                    type="button"
                >
                    Nuevo plan
                </button>
            </header>

            <div class="flex items-center justify-between rounded-xl border bg-card p-4">
                <div>
                    <p class="text-xs uppercase text-muted-foreground">Planes publicados</p>
                    <p class="text-2xl font-semibold">{{ plans.filter((plan) => plan.is_active).length }}</p>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input v-model="showInactive" type="checkbox" class="rounded border" />
                    Mostrar planes inactivos
                </label>
            </div>

            <div v-if="isLoading" class="rounded-xl border bg-card p-6 text-center text-muted-foreground">
                Cargando planes…
            </div>
            <div v-else-if="!visiblePlans.length" class="rounded-xl border bg-card p-6 text-center text-muted-foreground">
                Sin planes para mostrar. ¡Crea tu primer plan!
            </div>
            <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="plan in visiblePlans"
                    :key="plan.id"
                    class="flex flex-col gap-3 rounded-2xl border bg-card p-5 shadow-sm"
                >
                    <header class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold">{{ plan.name }}</h3>
                            <p class="text-sm text-muted-foreground">{{ plan.description }}</p>
                        </div>
                        <span
                            class="rounded-full px-2 py-1 text-xs font-semibold"
                            :class="plan.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'"
                        >
                            {{ plan.is_active ? 'Activo' : 'Pausado' }}
                        </span>
                    </header>
                    <p class="text-3xl font-bold">
                        {{ new Intl.NumberFormat('en-US', { style: 'currency', currency: plan.currency || 'USD' }).format(Number(plan.price)) }}
                        <span class="text-base font-medium text-muted-foreground">/{{ plan.billing_interval }}</span>
                    </p>
                    <ul v-if="plan.features" class="list-disc space-y-1 pl-5 text-sm text-muted-foreground">
                        <li v-for="(value, key) in plan.features" :key="key">
                            {{ key }}: <span class="font-medium text-foreground">{{ value }}</span>
                        </li>
                    </ul>
                </article>
            </div>
        </section>
    </AppLayout>
</template>

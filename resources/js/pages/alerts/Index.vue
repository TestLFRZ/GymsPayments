<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useAlertStore } from '@/stores/alerts.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { computed, watch } from 'vue';

const breadcrumbs = [
    { title: 'Panel', href: '/dashboard' },
    { title: 'Alertas', href: '/alerts' },
];

const alertStore = useAlertStore();
const tenantStore = useTenantStore();
const { alerts } = storeToRefs(alertStore);
const { currentTenant } = storeToRefs(tenantStore);

const upcoming = computed(() =>
    alerts.value.slice().sort((a, b) => a.scheduled_for.localeCompare(b.scheduled_for)),
);

watch(
    () => tenantStore.slug,
    (slug) => {
        if (slug) {
            alertStore.fetchAlerts();
        }
    },
    { immediate: true },
);

watch(
    () => currentTenant.value?.id ?? null,
    (tenantId) => {
        if (tenantId) {
            alertStore.connect(tenantId);
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Alertas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="space-y-6 p-6">
            <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">{{ currentTenant?.name ?? 'Espacio multi-tenant' }}</p>
                    <h1 class="text-2xl font-semibold">Alertas y notificaciones</h1>
                    <p class="text-sm text-muted-foreground">
                        Monitorea recordatorios programados y eventos en tiempo real.
                    </p>
                </div>
                <button
                    class="inline-flex items-center rounded-lg border border-primary/40 px-4 py-2 text-sm font-medium transition hover:bg-primary/10"
                    type="button"
                >
                    Configurar canales
                </button>
            </header>

            <div class="rounded-xl border bg-card p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Próximas alertas</h2>
                <p class="text-sm text-muted-foreground">Próximos 10 eventos entre recordatorios y webhooks.</p>

                <ol class="mt-4 space-y-3">
                    <li v-if="!upcoming.length" class="text-sm text-muted-foreground">No hay alertas programadas.</li>
                    <li
                        v-for="alert in upcoming.slice(0, 10)"
                        :key="alert.id"
                        class="flex items-center justify-between rounded-lg border bg-muted/40 px-4 py-3 text-sm"
                    >
                        <div>
                            <p class="font-semibold">{{ alert.title }}</p>
                            <p class="text-xs text-muted-foreground">Tipo: {{ alert.type }} · Miembro #{{ alert.member_id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase text-muted-foreground">Programada para</p>
                            <p class="font-medium">
                                {{ new Date(alert.scheduled_for).toLocaleString() }}
                            </p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="rounded-xl border bg-card p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Eventos en vivo</h2>
                <p class="text-sm text-muted-foreground">Mantén esta vista abierta para ver alertas en tiempo real.</p>

                <div class="mt-4 space-y-3">
                    <article
                        v-for="alert in alerts"
                        :key="alert.id"
                        class="rounded-lg border bg-background px-4 py-3 shadow-sm"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-semibold">{{ alert.title }}</p>
                                <p class="text-xs text-muted-foreground">
                                    Miembro #{{ alert.member_id }} · {{ alert.type.toUpperCase() }}
                                </p>
                            </div>
                            <span
                                class="rounded-full px-2 py-1 text-xs font-medium"
                                :class="alert.delivered_at
                                    ? 'bg-emerald-100 text-emerald-600'
                                    : 'bg-amber-100 text-amber-700'"
                            >
                                {{ alert.delivered_at ? 'Enviado' : 'Pendiente' }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">{{ alert.body || '—' }}</p>
                    </article>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

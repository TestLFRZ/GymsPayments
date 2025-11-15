<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useAlertStore } from '@/stores/alerts.js';
import { useMemberStore } from '@/stores/members.js';
import { usePaymentStore } from '@/stores/payments.js';
import { usePlanStore } from '@/stores/plans.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { computed, watch } from 'vue';

const breadcrumbs = [
    {
        title: 'Panel',
        href: '/dashboard',
    },
];

const memberStore = useMemberStore();
const planStore = usePlanStore();
const paymentStore = usePaymentStore();
const alertStore = useAlertStore();
const tenantStore = useTenantStore();

const { members } = storeToRefs(memberStore);
const { plans } = storeToRefs(planStore);
const { payments } = storeToRefs(paymentStore);
const { alerts } = storeToRefs(alertStore);

watch(
    () => tenantStore.slug,
    (slug) => {
        if (slug) {
            memberStore.fetchMembers();
            planStore.fetchPlans();
            paymentStore.fetchPayments();
            alertStore.fetchAlerts();
        }
    },
    { immediate: true },
);

const stats = computed(() => [
    {
        label: 'Miembros',
        value: members.value.length,
        helper: `${members.value.filter((member) => member.status === 'active').length} activos`,
    },
    {
        label: 'Planes activos',
        value: plans.value.filter((plan) => plan.is_active).length,
        helper: `${plans.value.length} totales`,
    },
    {
        label: 'Pagos (30 días)',
        value: payments.value.slice(0, 10).filter((payment) => payment.status === 'completed').length,
        helper: 'Manual + Stripe recientes',
    },
    {
        label: 'Alertas pendientes',
        value: alerts.value.filter((alert) => !alert.delivered_at).length,
        helper: 'Próximas 24h',
    },
]);

const recentAlerts = computed(() => alerts.value.slice(0, 5));
const recentPayments = computed(() =>
    payments.value
        .slice()
        .sort((a, b) => (b.processed_at ?? '').localeCompare(a.processed_at ?? ''))
        .slice(0, 5),
);
</script>

<template>
    <Head title="Panel" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="space-y-6 p-6">
            <header>
                <p class="text-sm text-muted-foreground">
                    ¡Bienvenido de vuelta! Supervisa el rendimiento del gimnasio multi-tenant de un vistazo.
                </p>
                <h1 class="text-2xl font-semibold">Resumen operativo</h1>
            </header>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <article v-for="entry in stats" :key="entry.label" class="rounded-2xl border bg-card p-5 shadow-sm">
                    <p class="text-sm text-muted-foreground">{{ entry.label }}</p>
                    <p class="text-3xl font-bold">{{ entry.value }}</p>
                    <p class="text-xs text-muted-foreground">{{ entry.helper }}</p>
                </article>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <article class="rounded-2xl border bg-card p-5 shadow-sm">
                    <header class="mb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">Pagos recientes</h2>
                            <p class="text-sm text-muted-foreground">Manual + pasarela</p>
                        </div>
                        <a class="text-sm text-primary hover:underline" href="/payments">Ver todos</a>
                    </header>
                    <ul class="space-y-3">
                        <li
                            v-for="payment in recentPayments"
                            :key="payment.id"
                            class="flex items-center justify-between rounded-lg border px-4 py-3 text-sm"
                        >
                            <div>
                                <p class="font-semibold">
                                    {{ payment.amount }} {{ payment.currency }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    Miembro #{{ payment.member_id }} · {{ payment.method.toUpperCase() }}
                                </p>
                            </div>
                            <span
                                class="rounded-full px-2 py-1 text-xs font-semibold capitalize"
                                :class="payment.status === 'completed'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700'"
                            >
                                {{ payment.status }}
                            </span>
                        </li>
                        <li v-if="!recentPayments.length" class="text-sm text-muted-foreground">
                            Aún no hay pagos registrados.
                        </li>
                    </ul>
                </article>

                <article class="rounded-2xl border bg-card p-5 shadow-sm">
                    <header class="mb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">Cronología de alertas</h2>
                            <p class="text-sm text-muted-foreground">Vencimientos y recordatorios</p>
                        </div>
                        <a class="text-sm text-primary hover:underline" href="/alerts">Ver alertas</a>
                    </header>
                    <ul class="space-y-4">
                        <li
                            v-for="alert in recentAlerts"
                            :key="alert.id"
                            class="rounded-xl border bg-muted/40 p-4 text-sm"
                        >
                            <p class="font-semibold">{{ alert.title }}</p>
                            <p class="text-xs text-muted-foreground">
                                {{ new Date(alert.scheduled_for).toLocaleString() }} · Miembro #{{ alert.member_id }}
                            </p>
                            <p class="mt-2 text-muted-foreground">{{ alert.body ?? '—' }}</p>
                        </li>
                        <li v-if="!recentAlerts.length" class="text-sm text-muted-foreground">
                            No hay alertas en cola.
                        </li>
                    </ul>
                </article>
            </div>
        </section>
    </AppLayout>
</template>

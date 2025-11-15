<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useAlertStore } from '@/stores/alerts.js';
import { useMemberStore } from '@/stores/members.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { computed, reactive, ref, watch } from 'vue';

const breadcrumbs = [
    { title: 'Panel', href: '/dashboard' },
    { title: 'Alertas', href: '/alerts' },
];

const alertStore = useAlertStore();
const tenantStore = useTenantStore();
const memberStore = useMemberStore();

const { alerts } = storeToRefs(alertStore);
const { currentTenant } = storeToRefs(tenantStore);
const { members } = storeToRefs(memberStore);

const upcoming = computed(() =>
    alerts.value.slice().sort((a, b) => new Date(a.scheduled_for) - new Date(b.scheduled_for)),
);

const form = reactive({
    member_id: '',
    type: 'recordatorio',
    title: '',
    body: '',
    scheduled_for: '',
    delivery_channel: 'email',
});

const formMessage = ref(null);
const formError = ref(null);
const isSaving = ref(false);

const resetForm = () => {
    form.member_id = '';
    form.type = 'recordatorio';
    form.title = '';
    form.body = '';
    form.scheduled_for = '';
    form.delivery_channel = 'email';
};

const submitAlert = async () => {
    formError.value = null;
    formMessage.value = null;

    if (!form.member_id || !form.title || !form.scheduled_for) {
        formError.value = 'Miembro, título y fecha son obligatorios.';
        return;
    }

    isSaving.value = true;
    try {
        await alertStore.createAlert({
            member_id: Number(form.member_id),
            type: form.type,
            title: form.title,
            body: form.body || undefined,
            scheduled_for: new Date(form.scheduled_for).toISOString(),
            delivery_channel: form.delivery_channel,
        });
        formMessage.value = 'Alerta programada correctamente.';
        resetForm();
    } catch (error) {
        const response = error?.response?.data ?? {};
        formError.value =
            response.message ||
            Object.values(response.errors ?? {})[0]?.[0] ||
            'No se pudo crear la alerta. Verifica los datos.';
    } finally {
        isSaving.value = false;
    }
};

watch(
    () => tenantStore.slug,
    (slug) => {
        if (slug) {
            memberStore.fetchMembers();
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
                    <p class="text-sm text-muted-foreground">Monitorea recordatorios programados y eventos en tiempo real.</p>
                </div>
                <div class="text-sm text-muted-foreground">
                    Canal en vivo conectado a tenant: <span class="font-semibold">{{ currentTenant?.id ?? '—' }}</span>
                </div>
            </header>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-xl border bg-card p-5 shadow-sm lg:col-span-1">
                    <h2 class="text-lg font-semibold">Programar alerta</h2>
                    <p class="text-sm text-muted-foreground">Envía recordatorios por email/WhatsApp antes de un pago.</p>

                    <form class="mt-4 space-y-4" @submit.prevent="submitAlert">
                        <label class="block text-sm font-medium">
                            Miembro
                            <select v-model="form.member_id" class="mt-1 w-full rounded-lg border px-3 py-2">
                                <option value="">Selecciona un miembro</option>
                                <option v-for="member in members" :key="member.id" :value="member.id">
                                    {{ member.first_name }} {{ member.last_name }}
                                </option>
                            </select>
                        </label>

                        <label class="block text-sm font-medium">
                            Tipo
                            <select v-model="form.type" class="mt-1 w-full rounded-lg border px-3 py-2">
                                <option value="recordatorio">Recordatorio</option>
                                <option value="webhook">Webhook</option>
                                <option value="alerta">Alerta</option>
                            </select>
                        </label>

                        <label class="block text-sm font-medium">
                            Título
                            <input v-model="form.title" type="text" class="mt-1 w-full rounded-lg border px-3 py-2" />
                        </label>

                        <label class="block text-sm font-medium">
                            Mensaje
                            <textarea v-model="form.body" rows="3" class="mt-1 w-full rounded-lg border px-3 py-2" />
                        </label>

                        <label class="block text-sm font-medium">
                            Fecha y hora
                            <input v-model="form.scheduled_for" type="datetime-local" class="mt-1 w-full rounded-lg border px-3 py-2" />
                        </label>

                        <label class="block text-sm font-medium">
                            Canal
                            <select v-model="form.delivery_channel" class="mt-1 w-full rounded-lg border px-3 py-2">
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="webhook">Webhook</option>
                            </select>
                        </label>

                        <p v-if="formError" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">
                            {{ formError }}
                        </p>
                        <p v-if="formMessage" class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                            {{ formMessage }}
                        </p>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                            :disabled="isSaving"
                        >
                            {{ isSaving ? 'Guardando…' : 'Programar alerta' }}
                        </button>
                    </form>
                </div>

                <div class="space-y-6 rounded-xl border bg-card p-5 shadow-sm lg:col-span-2">
                    <div>
                        <h2 class="text-lg font-semibold">Próximas alertas</h2>
                        <p class="text-sm text-muted-foreground">Próximos 10 eventos (recordatorios o webhooks).</p>
                        <ol class="mt-4 space-y-3">
                            <li v-if="!upcoming.length" class="text-sm text-muted-foreground">No hay alertas programadas.</li>
                            <li
                                v-for="alert in upcoming.slice(0, 10)"
                                :key="alert.id"
                                class="flex flex-col gap-2 rounded-lg border bg-muted/40 px-4 py-3 text-sm md:flex-row md:items-center md:justify-between"
                            >
                                <div>
                                    <p class="font-semibold">{{ alert.title }}</p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ alert.type.toUpperCase() }} · Miembro #{{ alert.member_id }}
                                    </p>
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    Programada para
                                    <span class="font-semibold text-foreground">{{ new Date(alert.scheduled_for).toLocaleString() }}</span>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Eventos en vivo</h2>
                            <span class="text-xs uppercase text-muted-foreground">Streaming privado</span>
                        </div>
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
                            <p v-if="!alerts.length" class="text-sm text-muted-foreground">No hay eventos recientes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

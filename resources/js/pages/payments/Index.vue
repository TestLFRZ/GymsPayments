<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useMemberStore } from '@/stores/members.js';
import { usePaymentStore } from '@/stores/payments.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { reactive, ref, watch } from 'vue';

const breadcrumbs = [
    { title: 'Panel', href: '/dashboard' },
    { title: 'Pagos', href: '/payments' },
];

const tenantStore = useTenantStore();
const paymentStore = usePaymentStore();
const memberStore = useMemberStore();

const { payments, isLoading } = storeToRefs(paymentStore);
const { members } = storeToRefs(memberStore);

const message = ref(null);
const form = reactive({
    member_id: '',
    plan_id: '',
    amount: '',
    currency: 'USD',
});

const submitManualPayment = async () => {
    message.value = null;
    if (!form.member_id || !form.amount) {
        message.value = 'El miembro y el monto son obligatorios.';
        return;
    }
    try {
        await paymentStore.recordManualPayment({
            member_id: Number(form.member_id),
            plan_id: form.plan_id ? Number(form.plan_id) : undefined,
            amount: Number(form.amount),
            currency: form.currency || 'USD',
        });
        Object.assign(form, {
            member_id: '',
            plan_id: '',
            amount: '',
            currency: 'USD',
        });
        message.value = 'Pago manual registrado correctamente.';
        paymentStore.fetchPayments();
    } catch (error) {
        console.error(error);
        message.value = 'No se pudo guardar el pago. Revisa los identificadores.';
    }
};

watch(
    () => tenantStore.slug,
    (slug) => {
        if (slug) {
            memberStore.fetchMembers();
            paymentStore.fetchPayments();
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Pagos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="space-y-6 p-6">
            <header>
                <h1 class="text-2xl font-semibold">Pagos</h1>
                <p class="text-sm text-muted-foreground">Registra conciliaciones manuales y pagos por pasarela.</p>
            </header>

            <div class="grid gap-4 lg:grid-cols-3">
                <div class="rounded-xl border bg-card p-5 shadow-sm lg:col-span-1">
                    <h2 class="text-lg font-semibold">Registrar pago manual</h2>
                    <p class="mb-4 text-sm text-muted-foreground">
                        Úsalo cuando un miembro pague en efectivo o transferencia.
                    </p>

                    <form class="space-y-4" @submit.prevent="submitManualPayment">
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
                            ID del plan (opcional)
                            <input v-model="form.plan_id" type="number" class="mt-1 w-full rounded-lg border px-3 py-2" />
                        </label>

                        <label class="block text-sm font-medium">
                            Monto
                            <input v-model="form.amount" type="number" step="0.01" class="mt-1 w-full rounded-lg border px-3 py-2" />
                        </label>

                        <label class="block text-sm font-medium">
                            Moneda
                            <input v-model="form.currency" type="text" class="mt-1 w-full rounded-lg border px-3 py-2 uppercase" />
                        </label>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                        >
                            Guardar pago manual
                        </button>
                        <p v-if="message" class="text-sm text-muted-foreground">{{ message }}</p>
                    </form>
                </div>

                <div class="rounded-xl border bg-card p-5 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Pagos recientes</h2>
                        <button class="text-sm text-muted-foreground hover:text-foreground" @click="paymentStore.fetchPayments()">
                            Actualizar
                        </button>
                    </div>
                    <div class="mt-4 overflow-hidden rounded-lg border">
                        <table class="min-w-full divide-y text-sm">
                            <thead class="bg-muted/60">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium">Miembro</th>
                                    <th class="px-4 py-2 text-left font-medium">Monto</th>
                                    <th class="px-4 py-2 text-left font-medium">Estado</th>
                                    <th class="px-4 py-2 text-left font-medium">Método</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <tr v-if="isLoading">
                                    <td colspan="4" class="px-4 py-6 text-center text-muted-foreground">Cargando pagos…</td>
                                </tr>
                                <tr v-else-if="!payments.length">
                                    <td colspan="4" class="px-4 py-6 text-center text-muted-foreground">
                                        Aún no hay pagos registrados.
                                    </td>
                                </tr>
                                <tr v-for="payment in payments" :key="payment.id" class="hover:bg-muted/40">
                                    <td class="px-4 py-3">
                                        Miembro #{{ payment.member_id }}
                                        <p class="text-xs text-muted-foreground">
                                            Plan #{{ payment.plan_id ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 font-semibold">
                                        {{ payment.amount }} {{ payment.currency }}
                                    </td>
                                    <td class="px-4 py-3 capitalize">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="payment.status === 'completed'
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-amber-100 text-amber-700'"
                                        >
                                            {{ payment.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 uppercase">{{ payment.method }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

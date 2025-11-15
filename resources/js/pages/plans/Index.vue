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
const { plans, isLoading, isSaving, isUpdating, isDeleting } = storeToRefs(planStore);
const { currentTenant } = storeToRefs(tenantStore);

const showInactive = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const formError = ref(null);
const formSuccess = ref(null);
const formAlert = ref(null);
const createForm = ref({
    name: '',
    description: '',
    price: '',
    currency: 'USD',
    billing_interval: 'mensual',
    billing_duration: 1,
    featuresText: '',
    is_active: true,
});
const editForm = ref({
    id: null,
    name: '',
    description: '',
    price: '',
    currency: 'USD',
    billing_interval: 'mensual',
    billing_duration: 1,
    featuresText: '',
    is_active: true,
});
const planToDelete = ref(null);

const visiblePlans = computed(() =>
    showInactive.value ? plans.value : plans.value.filter((plan) => plan.is_active),
);

const resetForm = () => {
    createForm.value = {
        name: '',
        description: '',
        price: '',
        currency: 'USD',
        billing_interval: 'mensual',
        billing_duration: 1,
        featuresText: '',
        is_active: true,
    };
};

const openEditPlan = (plan) => {
    editForm.value = {
        id: plan.id,
        name: plan.name,
        description: plan.description || '',
        price: plan.price,
        currency: (plan.currency || 'USD').toString().toUpperCase(),
        billing_interval: plan.billing_interval || 'mensual',
        billing_duration: plan.billing_duration || 1,
        featuresText: Array.isArray(plan.features)
            ? plan.features.join('\n')
            : plan.features
              ? Object.values(plan.features).join('\n')
              : '',
        is_active: plan.is_active,
    };
    formError.value = null;
    formSuccess.value = null;
    showEditModal.value = true;
};

const handleCreatePlan = async () => {
    formError.value = null;
    formAlert.value = null;
    formSuccess.value = null;

    if (!createForm.value.name || !createForm.value.price) {
        formError.value = 'Nombre y precio son obligatorios.';
        return;
    }

    try {
        const currencyValue = (createForm.value.currency || '')
            .toString()
            .trim()
            .toUpperCase();
        const currency = currencyValue.length === 3 ? currencyValue : undefined;

        const payload = {
            name: createForm.value.name,
            description: createForm.value.description || undefined,
            price: Number(createForm.value.price),
            currency: currency ?? undefined,
            billing_interval: createForm.value.billing_interval,
            billing_duration: createForm.value.billing_duration || null,
            features: createForm.value.featuresText
                ? createForm.value.featuresText
                      .split('\n')
                      .map((line) => line.trim())
                      .filter(Boolean)
                : undefined,
            is_active: createForm.value.is_active,
        };

        await planStore.createPlan(payload);
        formAlert.value = 'Plan creado correctamente.';
        resetForm();
        showCreateModal.value = false;
    } catch (error) {
        const response = error?.response?.data ?? {};
        formError.value =
            response.message ||
            Object.values(response.errors ?? {})[0]?.[0] ||
            'No se pudo crear el plan. Intenta nuevamente.';
    }
};

const handleUpdatePlan = async () => {
    formError.value = null;
    formAlert.value = null;
    formSuccess.value = null;

    if (!editForm.value.name || !editForm.value.price) {
        formError.value = 'Nombre y precio son obligatorios.';
        return;
    }

    const currencyValue = (editForm.value.currency || '').toString().trim().toUpperCase();
    const currency = currencyValue.length === 3 ? currencyValue : undefined;

    try {
        const payload = {
            name: editForm.value.name,
            description: editForm.value.description || undefined,
            price: Number(editForm.value.price),
            currency: currency ?? undefined,
            billing_interval: editForm.value.billing_interval,
            billing_duration: editForm.value.billing_duration || null,
            features: editForm.value.featuresText
                ? editForm.value.featuresText
                      .split('\n')
                      .map((line) => line.trim())
                      .filter(Boolean)
                : undefined,
            is_active: editForm.value.is_active,
        };

        await planStore.updatePlan(editForm.value.id, payload);
        showEditModal.value = false;
        formAlert.value = 'Plan actualizado correctamente.';
    } catch (error) {
        const response = error?.response?.data ?? {};
        formError.value =
            response.message ||
            Object.values(response.errors ?? {})[0]?.[0] ||
            'No se pudo actualizar el plan. Intenta nuevamente.';
    }
};

const confirmDeletePlan = (plan) => {
    planToDelete.value = plan;
    showDeleteModal.value = true;
    formError.value = null;
};

const handleDeletePlan = async () => {
    if (!planToDelete.value) {
        return;
    }

    try {
        await planStore.deletePlan(planToDelete.value.id);
        formAlert.value = 'Plan eliminado correctamente.';
    } catch (error) {
        formError.value = 'No se pudo eliminar el plan.';
    } finally {
        showDeleteModal.value = false;
        planToDelete.value = null;
    }
};

const planFeatures = (plan) => {
    if (!plan.features) return [];
    if (Array.isArray(plan.features)) return plan.features;
    if (typeof plan.features === 'object') {
        return Object.entries(plan.features).map(([key, value]) => `${key}: ${value}`);
    }
    return [];
};

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
                    @click="
                        showCreateModal = true;
                        formError = null;
                        formSuccess = null;
                    "
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
            <div v-if="formAlert" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ formAlert }}
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
                    <ul v-if="planFeatures(plan).length" class="list-disc space-y-1 pl-5 text-sm text-muted-foreground">
                        <li v-for="(item, index) in planFeatures(plan)" :key="index">
                            {{ item }}
                        </li>
                    </ul>
                    <div class="mt-auto flex gap-2">
                        <button
                            class="rounded-lg border px-3 py-1 text-xs font-medium hover:bg-accent"
                            type="button"
                            @click="openEditPlan(plan)"
                        >
                            Editar
                        </button>
                        <button
                            class="rounded-lg border border-destructive px-3 py-1 text-xs font-medium text-destructive hover:bg-destructive/10"
                            type="button"
                            @click="confirmDeletePlan(plan)"
                        >
                            Eliminar
                        </button>
                    </div>
                </article>
            </div>
        </section>
    </AppLayout>

    <div
        v-if="showCreateModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
    >
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-[#161616]">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Nuevo plan</h2>
                    <p class="text-sm text-muted-foreground">Define nombre, precio y beneficios.</p>
                </div>
                <button
                    class="text-sm text-muted-foreground hover:text-foreground"
                    type="button"
                    @click="
                        showCreateModal = false;
                        resetForm();
                    "
                >
                    Cerrar
                </button>
            </div>

            <form class="space-y-4" @submit.prevent="handleCreatePlan">
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Nombre</label>
                    <input
                        v-model="createForm.name"
                        class="rounded-lg border px-3 py-2 text-sm"
                        placeholder="Acceso ilimitado"
                        required
                    />
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Descripción</label>
                    <textarea
                        v-model="createForm.description"
                        class="rounded-lg border px-3 py-2 text-sm"
                        rows="2"
                    />
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Precio</label>
                        <input
                            v-model="createForm.price"
                            type="number"
                            min="0"
                            step="0.01"
                            class="rounded-lg border px-3 py-2 text-sm"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Moneda</label>
                        <input
                            v-model="createForm.currency"
                            maxlength="3"
                            class="rounded-lg border px-3 py-2 text-sm uppercase"
                        />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Intervalo</label>
                        <select v-model="createForm.billing_interval" class="rounded-lg border px-3 py-2 text-sm">
                            <option value="mensual">Mensual</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Duración (opcional)</label>
                    <input
                        v-model="createForm.billing_duration"
                        type="number"
                        min="1"
                        class="rounded-lg border px-3 py-2 text-sm"
                    />
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Beneficios (uno por línea)</label>
                    <textarea
                        v-model="createForm.featuresText"
                        rows="3"
                        class="rounded-lg border px-3 py-2 text-sm"
                        placeholder="Acceso 24/7&#10;Clases ilimitadas"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input v-model="createForm.is_active" type="checkbox" class="rounded border" />
                    Plan activo
                </label>

                <p v-if="formError" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">
                    {{ formError }}
                </p>
                <p v-if="formSuccess" class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    {{ formSuccess }}
                </p>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="button"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
                        @click="
                            showCreateModal = false;
                            resetForm();
                        "
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-foreground shadow hover:bg-primary/90 disabled:opacity-60"
                        :disabled="isSaving"
                    >
                        {{ isSaving ? 'Guardando…' : 'Guardar plan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        v-if="showEditModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
    >
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-[#161616]">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Editar plan</h2>
                    <p class="text-sm text-muted-foreground">Actualiza los detalles del plan.</p>
                </div>
                <button
                    class="text-sm text-muted-foreground hover:text-foreground"
                    type="button"
                    @click="
                        showEditModal = false;
                        formError = null;
                    "
                >
                    Cerrar
                </button>
            </div>

            <form class="space-y-4" @submit.prevent="handleUpdatePlan">
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Nombre</label>
                    <input
                        v-model="editForm.name"
                        class="rounded-lg border px-3 py-2 text-sm"
                        required
                    />
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Descripción</label>
                    <textarea
                        v-model="editForm.description"
                        class="rounded-lg border px-3 py-2 text-sm"
                        rows="2"
                    />
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Precio</label>
                        <input
                            v-model="editForm.price"
                            type="number"
                            min="0"
                            step="0.01"
                            class="rounded-lg border px-3 py-2 text-sm"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Moneda</label>
                        <input
                            v-model="editForm.currency"
                            maxlength="3"
                            class="rounded-lg border px-3 py-2 text-sm uppercase"
                        />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Intervalo</label>
                        <select v-model="editForm.billing_interval" class="rounded-lg border px-3 py-2 text-sm">
                            <option value="mensual">Mensual</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Duración (opcional)</label>
                    <input
                        v-model="editForm.billing_duration"
                        type="number"
                        min="1"
                        class="rounded-lg border px-3 py-2 text-sm"
                    />
                </div>
                <div class="grid gap-3">
                    <label class="text-sm font-medium">Beneficios (uno por línea)</label>
                    <textarea
                        v-model="editForm.featuresText"
                        rows="3"
                        class="rounded-lg border px-3 py-2 text-sm"
                        placeholder="Acceso 24/7&#10;Clases ilimitadas"
                    />
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input v-model="editForm.is_active" type="checkbox" class="rounded border" />
                    Plan activo
                </label>

                <p v-if="formError" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">
                    {{ formError }}
                </p>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="button"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
                        @click="
                            showEditModal = false;
                            formError = null;
                        "
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-foreground shadow hover:bg-primary/90 disabled:opacity-60"
                        :disabled="isUpdating"
                    >
                        {{ isUpdating ? 'Guardando…' : 'Actualizar plan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        v-if="showDeleteModal && planToDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-[#181818]">
            <h3 class="text-lg font-semibold">Eliminar plan</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                ¿Estás seguro de eliminar el plan <strong>{{ planToDelete.name }}</strong>? Esta acción no se puede
                deshacer.
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
                    @click="
                        showDeleteModal = false;
                        planToDelete = null;
                    "
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    class="rounded-lg bg-destructive px-5 py-2 text-sm font-semibold text-destructive-foreground shadow hover:bg-destructive/90 disabled:opacity-60"
                    :disabled="isDeleting"
                    @click="handleDeletePlan"
                >
                    {{ isDeleting ? 'Eliminando…' : 'Eliminar' }}
                </button>
            </div>
        </div>
    </div>
</template>

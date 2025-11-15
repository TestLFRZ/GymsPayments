<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useMemberStore } from '@/stores/members.js';
import { useTenantStore } from '@/stores/tenant.js';
import { Head } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { computed, reactive, ref, watch } from 'vue';

const breadcrumbs = [
    { title: 'Panel', href: '/dashboard' },
    { title: 'Miembros', href: '/members' },
];

const memberStore = useMemberStore();
const tenantStore = useTenantStore();
const { members, isLoading, isSaving, isUpdating, isDeleting } = storeToRefs(memberStore);
const { currentTenant } = storeToRefs(tenantStore);

const search = ref('');
const statusFilter = ref('all');

const filteredMembers = computed(() => {
    const term = search.value.toLowerCase();
    return members.value.filter((member) => {
        const matchesStatus = statusFilter.value === 'all' ? true : member.status === statusFilter.value;
        const matchesSearch =
            member.first_name.toLowerCase().includes(term) ||
            member.last_name.toLowerCase().includes(term) ||
            member.email.toLowerCase().includes(term);
        return matchesStatus && matchesSearch;
    });
});

const loadMembers = () => {
    const status = statusFilter.value === 'all' ? undefined : statusFilter.value;
    memberStore.fetchMembers(status);
};

const showCreateModal = ref(false);
const formError = ref(null);
const formSuccess = ref(null);
const createForm = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    status: 'active',
});

const editForm = reactive({
    id: 0,
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    status: 'active',
});

const showEditModal = ref(false);
const memberToDelete = ref(null);

const resetForm = () => {
    createForm.first_name = '';
    createForm.last_name = '';
    createForm.email = '';
    createForm.phone = '';
    createForm.status = 'active';
};

const submitMember = async () => {
    formError.value = null;
    formSuccess.value = null;

    if (!createForm.first_name || !createForm.last_name || !createForm.email) {
        formError.value = 'Nombre, apellido y correo son obligatorios.';
        return;
    }

    try {
        await memberStore.createMember({
            first_name: createForm.first_name,
            last_name: createForm.last_name,
            email: createForm.email,
            phone: createForm.phone || undefined,
            status: createForm.status,
        });
        formSuccess.value = 'Miembro agregado correctamente.';
        resetForm();
        showCreateModal.value = false;
    } catch (error) {
        const response = error?.response?.data ?? {};
        formError.value =
            response.message ||
            Object.values(response.errors ?? {})[0]?.[0] ||
            'No se pudo crear el miembro. Intenta nuevamente.';
    }
};

const openEditModal = (member) => {
    editForm.id = member.id;
    editForm.first_name = member.first_name;
    editForm.last_name = member.last_name;
    editForm.email = member.email;
    editForm.phone = member.phone || '';
    editForm.status = member.status;
    formError.value = null;
    showEditModal.value = true;
};

const submitEdit = async () => {
    formError.value = null;
    formSuccess.value = null;

    if (!editForm.first_name || !editForm.last_name || !editForm.email) {
        formError.value = 'Nombre, apellido y correo son obligatorios.';
        return;
    }

    try {
        await memberStore.updateMember(editForm.id, {
            first_name: editForm.first_name,
            last_name: editForm.last_name,
            email: editForm.email,
            phone: editForm.phone || undefined,
            status: editForm.status,
        });
        formSuccess.value = 'Miembro actualizado correctamente.';
        showEditModal.value = false;
    } catch (error) {
        const response = error?.response?.data ?? {};
        formError.value =
            response.message ||
            Object.values(response.errors ?? {})[0]?.[0] ||
            'No se pudo actualizar el miembro. Intenta nuevamente.';
    }
};

const confirmDelete = async () => {
    if (!memberToDelete.value) return;
    formError.value = null;
    formSuccess.value = null;

    try {
        await memberStore.deleteMember(memberToDelete.value.id);
        formSuccess.value = 'Miembro eliminado.';
        memberToDelete.value = null;
    } catch (error) {
        formError.value = 'No se pudo eliminar el miembro.';
    }
};

watch(
    [() => tenantStore.slug, statusFilter],
    ([slug]) => {
        if (slug) {
            loadMembers();
        }
    },
    { immediate: true },
);

watch(
    statusFilter,
    () => {
        if (tenantStore.slug) {
            loadMembers();
        }
    },
);
</script>

<template>
    <Head title="Miembros" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="space-y-6 p-6">
            <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">{{ currentTenant?.name ?? 'Espacio multi-tenant' }}</p>
                    <h1 class="text-2xl font-semibold">Miembros</h1>
                    <p class="text-sm text-muted-foreground">
                        Administra a cada persona inscrita en este gimnasio.
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
                    Agregar miembro
                </button>
            </header>

            <div v-if="formSuccess" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ formSuccess }}
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border bg-card p-4">
                    <p class="text-xs uppercase text-muted-foreground">Total</p>
                    <p class="text-2xl font-semibold">{{ members.length }}</p>
                </div>
                <div class="rounded-xl border bg-card p-4">
                    <p class="text-xs uppercase text-muted-foreground">Activos</p>
                    <p class="text-2xl font-semibold">
                        {{ members.filter((member) => member.status === 'active').length }}
                    </p>
                </div>
                <div class="rounded-xl border bg-card p-4">
                    <p class="text-xs uppercase text-muted-foreground">Suspendidos</p>
                    <p class="text-2xl font-semibold">
                        {{ members.filter((member) => member.status !== 'active').length }}
                    </p>
                </div>
                <div class="rounded-xl border bg-card p-4">
                    <p class="text-xs uppercase text-muted-foreground">Gimnasio/Tenant</p>
                    <p class="text-2xl font-semibold">{{ currentTenant?.slug ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Busca por nombre o correo"
                    class="w-full rounded-lg border px-3 py-2 md:max-w-sm"
                />
                <select v-model="statusFilter" class="w-full rounded-lg border px-3 py-2 md:max-w-xs">
                    <option value="all">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="suspended">Suspendidos</option>
                </select>
            </div>

            <div class="overflow-hidden rounded-xl border bg-card">
                <table class="min-w-full divide-y text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Miembro</th>
                            <th class="px-4 py-3 text-left font-medium">Correo</th>
                            <th class="px-4 py-3 text-left font-medium">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="isLoading">
                            <td colspan="3" class="px-4 py-6 text-center text-muted-foreground">Cargando miembros…</td>
                        </tr>
                        <tr v-else-if="!filteredMembers.length">
                            <td colspan="3" class="px-4 py-6 text-center text-muted-foreground">No se encontraron miembros.</td>
                        </tr>
                        <tr v-for="member in filteredMembers" :key="member.id" class="hover:bg-muted/40">
                            <td class="px-4 py-3">
                                <p class="font-medium">
                                    {{ member.first_name }} {{ member.last_name }}
                                </p>
                                <p class="text-xs text-muted-foreground">ID: {{ member.id }}</p>
                            </td>
                            <td class="px-4 py-3">{{ member.email }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                    :class="member.status === 'active'
                                        ? 'bg-emerald-100 text-emerald-800'
                                        : 'bg-amber-100 text-amber-800'"
                                >
                                    {{ member.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button
                                        class="rounded-lg border px-3 py-1 text-xs font-medium"
                                        @click="openEditModal(member)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="rounded-lg border border-rose-400 px-3 py-1 text-xs font-medium text-rose-600"
                                        @click="memberToDelete = member"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div class="w-full max-w-2xl rounded-2xl bg-card p-6 shadow-2xl">
                <header class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Agregar miembro</h2>
                        <p class="text-sm text-muted-foreground">Completa los datos básicos del nuevo miembro.</p>
                    </div>
                    <button
                        class="text-sm text-muted-foreground hover:text-foreground"
                        @click="
                            showCreateModal = false;
                            formError = null;
                        "
                    >
                        Cerrar
                    </button>
                </header>

                <form class="space-y-4" @submit.prevent="submitMember">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="text-sm font-medium">
                            Nombre
                            <input
                                v-model="createForm.first_name"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2"
                            />
                        </label>
                        <label class="text-sm font-medium">
                            Apellido
                            <input
                                v-model="createForm.last_name"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2"
                            />
                        </label>
                    </div>

                    <label class="text-sm font-medium">
                        Correo electrónico
                        <input
                            v-model="createForm.email"
                            type="email"
                            class="mt-1 w-full rounded-lg border px-3 py-2"
                        />
                    </label>

                    <label class="text-sm font-medium">
                        Teléfono (opcional)
                        <input
                            v-model="createForm.phone"
                            type="tel"
                            class="mt-1 w-full rounded-lg border px-3 py-2"
                        />
                    </label>

                    <label class="text-sm font-medium">
                        Estado
                        <select v-model="createForm.status" class="mt-1 w-full rounded-lg border px-3 py-2">
                            <option value="active">Activo</option>
                            <option value="suspended">Suspendido</option>
                        </select>
                    </label>

                    <p v-if="formError" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-800">
                        {{ formError }}
                    </p>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-muted-foreground"
                            @click="
                                showCreateModal = false;
                                formError = null;
                            "
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                            :disabled="isSaving"
                        >
                            <span v-if="isSaving" class="mr-2 inline-block h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div class="w-full max-w-2xl rounded-2xl bg-card p-6 shadow-2xl">
                <header class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Editar miembro</h2>
                        <p class="text-sm text-muted-foreground">Actualiza los datos seleccionados.</p>
                    </div>
                    <button
                        class="text-sm text-muted-foreground hover:text-foreground"
                        @click="
                            showEditModal = false;
                            formError = null;
                        "
                    >
                        Cerrar
                    </button>
                </header>

                <form class="space-y-4" @submit.prevent="submitEdit">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="text-sm font-medium">
                            Nombre
                            <input
                                v-model="editForm.first_name"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2"
                            />
                        </label>
                        <label class="text-sm font-medium">
                            Apellido
                            <input
                                v-model="editForm.last_name"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2"
                            />
                        </label>
                    </div>

                    <label class="text-sm font-medium">
                        Correo electrónico
                        <input
                            v-model="editForm.email"
                            type="email"
                            class="mt-1 w-full rounded-lg border px-3 py-2"
                        />
                    </label>

                    <label class="text-sm font-medium">
                        Teléfono (opcional)
                        <input
                            v-model="editForm.phone"
                            type="tel"
                            class="mt-1 w-full rounded-lg border px-3 py-2"
                        />
                    </label>

                    <label class="text-sm font-medium">
                        Estado
                        <select v-model="editForm.status" class="mt-1 w-full rounded-lg border px-3 py-2">
                            <option value="active">Activo</option>
                            <option value="suspended">Suspendido</option>
                        </select>
                    </label>

                    <p v-if="formError" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-800">
                        {{ formError }}
                    </p>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-muted-foreground"
                            @click="
                                showEditModal = false;
                                formError = null;
                            "
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                            :disabled="isUpdating"
                        >
                            <span v-if="isUpdating" class="mr-2 inline-block h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete confirmation -->
        <div
            v-if="memberToDelete"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4"
        >
            <div class="w-full max-w-md rounded-2xl bg-card p-6 shadow-2xl">
                <h2 class="mb-2 text-lg font-semibold">Eliminar miembro</h2>
                <p class="text-sm text-muted-foreground">
                    ¿Seguro que deseas eliminar a
                    <span class="font-medium">
                        {{ memberToDelete.first_name }} {{ memberToDelete.last_name }}
                    </span>
                    ? Esta acción no se puede deshacer.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        class="rounded-lg border px-4 py-2 text-sm font-medium text-muted-foreground"
                        type="button"
                        @click="memberToDelete = null"
                    >
                        Cancelar
                    </button>
                    <button
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60"
                        type="button"
                        :disabled="isDeleting"
                        @click="confirmDelete"
                    >
                        <span v-if="isDeleting" class="mr-2 inline-block h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent" />
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

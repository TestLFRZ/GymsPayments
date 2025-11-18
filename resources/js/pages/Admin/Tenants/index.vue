<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">
                Gestión de Inquilinos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">
                                Lista de Inquilinos
                            </h3>
                            <Link
                                :href="route('admin.tenants.create')"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            >
                                Nuevo Inquilino
                            </Link>
                        </div>

                        <div
                            class="overflow-hidden overflow-x-auto rounded-lg border border-gray-200"
                        >
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                        >
                                            Nombre
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                        >
                                            Dominio
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                        >
                                            Usuarios
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                        >
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="tenant in $page.props.tenants"
                                        :key="tenant.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ tenant.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ tenant.domain }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ tenant.users_count }}
                                        </td>
                                        <td
                                            class="px-6 py-4 text-sm font-medium whitespace-nowrap"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.tenants.edit',
                                                        tenant.id,
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Editar
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

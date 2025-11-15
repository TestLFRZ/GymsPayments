<script setup>
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canRegister: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <Head title="GymsPayments">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#050505]"
    >
        <div
            class="flex flex-col items-center gap-8 rounded-2xl border border-red-600 bg-white px-12 py-16 text-center shadow-[0_18px_45px_rgba(0,0,0,0.22)] dark:border-red-400 dark:bg-[#161615]"
        >
            <p class="text-xs uppercase tracking-[0.6em] text-red-500">Bienvenido a</p>
            <h1
                class="text-5xl font-black uppercase tracking-[0.35em] text-[#0b0b09] drop-shadow-[0_40px_25px_rgba(0,0,0,0.35)] dark:text-[#EDEDEC]"
            >
                GymsPayments
            </h1>
            <p class="max-w-md text-sm text-[#5a5954] dark:text-[#c5c4bd]">
                Centraliza los pagos y la gestión de tus gimnasios en un solo lugar. Inicia sesión o crea una cuenta para
                comenzar.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <Link
                    :href="$page.props.auth.user ? dashboard() : login()"
                    class="rounded-lg border border-transparent bg-red-600 px-8 py-3 text-sm font-semibold uppercase tracking-widest text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    {{ $page.props.auth.user ? 'Ir al panel' : 'Iniciar sesión' }}
                </Link>
                <Link
                    v-if="!$page.props.auth.user && canRegister"
                    :href="register()"
                    class="rounded-lg border border-red-600 px-8 py-3 text-sm font-semibold uppercase tracking-widest text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-400 dark:text-red-400 dark:hover:bg-red-400/10"
                >
                    Crear cuenta
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { login, register } from '@/routes';
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
  <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
    <div class="flex flex-col items-center gap-6 rounded-2xl border border-red-600 bg-white px-12 py-14 text-center shadow-[0_15px_40px_rgba(0,0,0,0.2)] dark:bg-[#161615]">
      <h1 class="text-5xl font-black uppercase tracking-[0.4em] text-[#1b1b18] drop-shadow-[0_18px_12px_rgba(0,0,0,0.45)] dark:text-[#EDEDEC]">
        GymsPayments
      </h1>
      <div class="flex flex-wrap justify-center gap-4">
        <Link
          :href="login()"
          class="rounded-md border border-transparent bg-red-600 px-8 py-3 text-sm font-semibold uppercase tracking-widest text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
          Log in
        </Link>
        <Link
          v-if="canRegister"
          :href="register()"
          class="rounded-md border border-red-600 px-8 py-3 text-sm font-semibold uppercase tracking-widest text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-400 dark:text-red-400 dark:hover:bg-red-400/10"
        >
          Register
        </Link>
      </div>
    </div>
  </div>
</template>

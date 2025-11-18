<script setup>
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import { Bell, BookOpen, CreditCard, Folder, LayoutGrid, Users, Layers, Building2, Dumbbell } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems = [
    {
        title: 'Panel',
        href: '/dashboard',
        icon: Dumbbell,
    },
    {
        title: 'Miembros',
        href: '/members',
        icon: Users,
    },
    {
        title: 'Planes',
        href: '/plans',
        icon: Layers,
    },
    {
        title: 'Pagos',
        href: '/payments',
        icon: CreditCard,
    },
    {
        title: 'Alertas',
        href: '/alerts',
        icon: Bell,
    },
    {
        title: 'Inquilinos',
        href: '/admin/tenants',
        icon: Building2,
        adminOnly: true,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/dashboard">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <template v-for="item in mainNavItems" :key="item.href">
                    <SidebarMenuItem
                        v-if="!item.adminOnly || $page.props.auth.user?.is_admin"
                        :title="item.title"
                        :to="item.href"
                        :icon="item.icon"
                    />
                </template>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

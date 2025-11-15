import { defineStore } from 'pinia';

export const useTenantStore = defineStore('tenant', {
    state: () => ({
        currentTenant: null,
        slug: window.localStorage.getItem('tenant_slug'),
    }),
    getters: {
        isResolved: (state) => Boolean(state.currentTenant),
    },
    actions: {
        setTenant(tenant) {
            this.currentTenant = tenant;
            this.slug = tenant.slug;
            window.localStorage.setItem('tenant_slug', tenant.slug);
        },
        clearTenant() {
            this.currentTenant = null;
            this.slug = null;
            window.localStorage.removeItem('tenant_slug');
        },
    },
});

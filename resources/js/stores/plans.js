import { defineStore } from 'pinia';
import { http } from '@/lib/http.js';

export const usePlanStore = defineStore('plans', {
    state: () => ({
        plans: [],
        isLoading: false,
    }),
    actions: {
        async fetchPlans(showInactive = false) {
            this.isLoading = true;
            try {
                const response = await http.get('/plans', {
                    params: {
                        active: showInactive ? undefined : true,
                    },
                });
                this.plans = response.data.data;
            } finally {
                this.isLoading = false;
            }
        },
    },
});

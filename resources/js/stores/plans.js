import { defineStore } from 'pinia';
import { http } from '@/lib/http.js';

export const usePlanStore = defineStore('plans', {
    state: () => ({
        plans: [],
        isLoading: false,
        isSaving: false,
        isUpdating: false,
        isDeleting: false,
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
        async createPlan(payload) {
            this.isSaving = true;
            try {
                const response = await http.post('/plans', payload);
                this.plans.unshift(response.data);
                return response.data;
            } finally {
                this.isSaving = false;
            }
        },
        async updatePlan(id, payload) {
            this.isUpdating = true;
            try {
                const response = await http.put(`/plans/${id}`, payload);
                const index = this.plans.findIndex((plan) => plan.id === id);
                if (index !== -1) {
                    this.plans[index] = response.data;
                }
                return response.data;
            } finally {
                this.isUpdating = false;
            }
        },
        async deletePlan(id) {
            this.isDeleting = true;
            try {
                await http.delete(`/plans/${id}`);
                this.plans = this.plans.filter((plan) => plan.id !== id);
            } finally {
                this.isDeleting = false;
            }
        },
    },
});

import { defineStore } from 'pinia';
import { http } from '@/lib/http.js';

export const usePaymentStore = defineStore('payments', {
    state: () => ({
        payments: [],
        isLoading: false,
    }),
    actions: {
        async fetchPayments(status) {
            this.isLoading = true;
            try {
                const response = await http.get('/payments', {
                    params: { status },
                });
                this.payments = response.data.data;
            } finally {
                this.isLoading = false;
            }
        },
        async recordManualPayment(payload) {
            const response = await http.post('/payments/manual', payload);
            this.payments.unshift(response.data);
            return response.data;
        },
    },
});

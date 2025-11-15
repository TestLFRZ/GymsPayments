import { defineStore } from 'pinia';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { http } from '@/lib/http.js';

export const useAlertStore = defineStore('alerts', {
    state: () => ({
        alerts: [],
        echo: undefined,
    }),
    actions: {
        async fetchAlerts() {
            const response = await http.get('/alerts');
            this.alerts = response.data.data;
        },
        async createAlert(payload) {
            const response = await http.post('/alerts', payload);
            this.alerts.unshift(response.data);
            return response.data;
        },
        connect(tenantId) {
            if (this.echo) {
                return;
            }

            if (typeof window === 'undefined') {
                return;
            }

            window.Pusher = Pusher;

            this.echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY ?? 'local',
                wsHost: window.location.hostname,
                wsPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
                forceTLS: false,
                disableStats: true,
            });

            this.echo.private(`tenant.${tenantId}`).listen('MembershipAlertBroadcast', (event) => {
                this.alerts.unshift(event);
            });
        },
    },
});

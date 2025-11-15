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
            if (this.echo || typeof window === 'undefined') {
                return;
            }

            const key = import.meta.env.VITE_PUSHER_APP_KEY;
            if (!key) {
                return;
            }

            window.Pusher = Pusher;

            try {
                this.echo = new Echo({
                    broadcaster: 'pusher',
                    key,
                    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
                    wsHost: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
                    wsPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
                    wssPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
                    forceTLS: import.meta.env.VITE_PUSHER_FORCE_TLS === 'true',
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                });

                this.echo.private(`tenant.${tenantId}`).listen('MembershipAlertBroadcast', (event) => {
                    this.alerts.unshift(event);
                });
            } catch (error) {
                console.warn('No se pudo inicializar el canal en vivo de alertas:', error);
            }
        },
    },
});

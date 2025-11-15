import axios from 'axios';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
});

http.interceptors.request.use((config) => {
    const tenantKey = window.localStorage.getItem('tenant_slug');
    if (tenantKey) {
        config.headers['X-Tenant-Key'] = tenantKey;
    }

    return config;
});

export { http };

import { defineStore } from 'pinia';
import { http } from '@/lib/http.js';

export const useMemberStore = defineStore('members', {
    state: () => ({
        members: [],
        isLoading: false,
        isSaving: false,
        isUpdating: false,
        isDeleting: false,
    }),
    actions: {
        async fetchMembers(status) {
            this.isLoading = true;
            try {
                const response = await http.get('/members', {
                    params: { status },
                });
                this.members = response.data.data;
            } finally {
                this.isLoading = false;
            }
        },
        async createMember(payload) {
            this.isSaving = true;
            try {
                const response = await http.post('/members', payload);
                this.members.unshift(response.data);
                return response.data;
            } finally {
                this.isSaving = false;
            }
        },
        async updateMember(id, payload) {
            this.isUpdating = true;
            try {
                const response = await http.put(`/members/${id}`, payload);
                const index = this.members.findIndex((member) => member.id === id);
                if (index !== -1) {
                    this.members[index] = response.data;
                }
                return response.data;
            } finally {
                this.isUpdating = false;
            }
        },
        async deleteMember(id) {
            this.isDeleting = true;
            try {
                await http.delete(`/members/${id}`);
                this.members = this.members.filter((member) => member.id !== id);
            } finally {
                this.isDeleting = false;
            }
        },
    },
});

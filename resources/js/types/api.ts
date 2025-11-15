export type Tenant = {
    id: number;
    name: string;
    slug: string;
    contact_email?: string | null;
    contact_phone?: string | null;
};

export type Plan = {
    id: number;
    name: string;
    description?: string | null;
    price: string;
    currency: string;
    billing_interval: string;
    features?: Record<string, unknown>;
    is_active: boolean;
};

export type Member = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string | null;
    status: string;
};

export type Payment = {
    id: number;
    member_id: number;
    plan_id?: number | null;
    amount: string;
    currency: string;
    status: string;
    method: string;
    processed_at?: string | null;
};

export type Alert = {
    id: number;
    member_id: number;
    type: string;
    title: string;
    body?: string | null;
    scheduled_for: string;
    delivered_at?: string | null;
};

import axios from 'axios';

export type ApiEnvelope<T> = {
    success: boolean;
    data: T;
    error: { code: string; message: string; retryable: boolean } | null;
    meta: Record<string, unknown>;
};

const client = axios.create({
    timeout: 30_000,
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

export async function apiGet<T>(url: string): Promise<ApiEnvelope<T>> {
    const { data } = await client.get<ApiEnvelope<T>>(url);
    return data;
}

export async function apiPost<T>(url: string, body?: unknown): Promise<ApiEnvelope<T>> {
    const { data } = await client.post<ApiEnvelope<T>>(url, body);
    return data;
}

export default client;

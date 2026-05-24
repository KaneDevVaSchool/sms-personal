<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { apiGet } from '@/utils/apiClient';

type NotificationRow = {
    id: string;
    data: { type?: string; message?: string };
    read_at: string | null;
    created_at: string;
};

const open = ref(false);
const items = ref<NotificationRow[]>([]);
const loading = ref(false);

async function load() {
    loading.value = true;
    try {
        const res = await apiGet<{ data?: NotificationRow[] } | NotificationRow[]>(
            route('notifications.index'),
        );
        if (res.success) {
            const payload = res.data as { data?: NotificationRow[] } | NotificationRow[];
            items.value = Array.isArray(payload) ? payload : (payload.data ?? []);
        }
    } finally {
        loading.value = false;
    }
}

onMounted(load);

const unreadCount = () => items.value.filter((n) => !n.read_at).length;
</script>

<template>
    <div class="relative">
        <button
            type="button"
            class="relative rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-200"
            aria-label="Notifications"
            @click="open = !open"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <span
                v-if="unreadCount() > 0"
                class="absolute right-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
            >
                {{ unreadCount() }}
            </span>
        </button>
        <div
            v-if="open"
            class="absolute right-0 z-50 mt-2 w-80 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
        >
            <div class="border-b border-gray-100 px-4 py-2 text-sm font-semibold dark:border-gray-800">
                Notifications
            </div>
            <ul class="max-h-64 overflow-y-auto text-sm">
                <li v-if="loading" class="px-4 py-3 text-gray-500">Loading…</li>
                <li v-else-if="!items.length" class="px-4 py-3 text-gray-500">No notifications</li>
                <li
                    v-for="n in items"
                    :key="n.id"
                    class="border-b border-gray-50 px-4 py-2 last:border-0 dark:border-gray-800"
                    :class="{ 'bg-blue-50/50 dark:bg-blue-950/30': !n.read_at }"
                >
                    <p class="font-medium text-gray-800 dark:text-gray-100">
                        {{ n.data.type ?? 'Alert' }}
                    </p>
                    <p class="text-xs text-gray-500">{{ n.created_at }}</p>
                </li>
            </ul>
        </div>
    </div>
</template>

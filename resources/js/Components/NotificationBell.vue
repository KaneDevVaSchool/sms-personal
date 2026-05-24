<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { apiGet } from '@/utils/apiClient';

type NotificationRow = {
    id: string;
    data: { type?: string; message?: string };
    read_at: string | null;
    created_at: string;
};

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
    <li class="dropdown notification-list">
        <a
            class="nav-link dropdown-toggle arrow-none"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            aria-expanded="false"
        >
            <i class="ri-notification-3-fill fs-22"></i>
            <span v-if="unreadCount() > 0" class="noti-icon-badge">{{ unreadCount() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
            <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 fs-16 fw-semibold">Notifications</h6>
                    </div>
                </div>
            </div>
            <div style="max-height: 300px" data-simplebar>
                <p v-if="loading" class="text-muted p-3 mb-0">Loading…</p>
                <p v-else-if="!items.length" class="text-muted p-3 mb-0">No notifications</p>
                <a
                    v-for="n in items"
                    :key="n.id"
                    href="javascript:void(0);"
                    class="dropdown-item p-0 notify-item card m-0 shadow-none"
                    :class="n.read_at ? 'read-noti' : 'unread-noti'"
                >
                    <div class="card-body py-2">
                        <h5 class="noti-item-title fw-medium fs-14 mb-1">
                            {{ n.data.type ?? 'Alert' }}
                        </h5>
                        <small class="noti-item-subtitle text-muted">{{ n.created_at }}</small>
                        <p v-if="n.data.message" class="mb-0 small text-muted">{{ n.data.message }}</p>
                    </div>
                </a>
            </div>
        </div>
    </li>
</template>

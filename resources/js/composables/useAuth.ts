import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export type AuthUser = {
    id: number;
    name: string;
    email: string;
    roles?: { id: number; name: string }[];
    permissions?: string[];
};

export function useAuth() {
    const page = usePage<{ auth: { user: AuthUser | null } }>();

    const user = computed(() => page.props.auth.user);
    const isAdmin = computed(() => user.value?.roles?.some((r) => r.name === 'admin') ?? false);

    const can = (permission: string) =>
        isAdmin.value || (user.value?.permissions?.includes(permission) ?? false);

    return { user, isAdmin, can };
}

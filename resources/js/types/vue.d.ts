import type {} from '@vue/runtime-core';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof route;
    }
}

export {};

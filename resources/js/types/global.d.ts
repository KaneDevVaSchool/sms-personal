/// <reference types="vite/client" />

declare module '*.vue' {
    import type { DefineComponent } from 'vue';

    const component: DefineComponent<object, object, unknown>;
    export default component;
}

declare function route(
    name?: string,
    params?: unknown,
    absolute?: boolean,
): string;

declare namespace route {
    function current(name?: string): boolean;
}

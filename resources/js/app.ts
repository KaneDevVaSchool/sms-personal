import '../css/app.css';
import 'remixicon/fonts/remixicon.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import { createApp, h, type DefineComponent } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const modulePages = import.meta.glob('./modules/**/pages/**/*.vue');
const legacyPages = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const modulePath = `./modules/${name}.vue`;
        if (modulePages[modulePath]) {
            return resolvePageComponent(
                modulePath,
                modulePages,
            ) as Promise<DefineComponent>;
        }

        return resolvePageComponent(
            `./Pages/${name}.vue`,
            legacyPages,
        ) as Promise<DefineComponent>;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4254ba',
    },
});

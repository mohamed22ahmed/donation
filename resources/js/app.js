import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import ChatPopup from './Components/ChatPopup.vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        const inertiaApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);
        
        inertiaApp.component('ChatPopup', ChatPopup);
        
        inertiaApp.mount(el);
        
        const chatApp = createApp(ChatPopup);
        chatApp.mount('#chat-popup');
        
        return inertiaApp;
    },
    progress: {
        color: '#4B5563',
    },
});


import '../sass/app.scss';
import './bootstrap';

import { createApp, h } from 'vue';
import {createInertiaApp, Link} from '@inertiajs/vue3';
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ClickOutside from "@/Directive/ClickOutside/ClickOutside";
import { TippyPlugin } from 'tippy.vue';
import 'tippy.js/dist/tippy.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(TippyPlugin, {
                tippyDefaults: {
                },
            })
            .directive('click-outside', ClickOutside)
            .component("Link", Link)
            .mount(el)
    },
});

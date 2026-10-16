
import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap-icons/font/bootstrap-icons.css';

import '../css/app.css';
import '../css/inicio.css';

import { createApp } from 'vue';
import { initInstitutionalHome } from './inicio.js';

const vueComponents = {};

document.addEventListener('DOMContentLoaded', () => {

    // Inicializar funcionalidades del sitio institucional
    if (document.querySelector('.hero-carousel, #institutionalCarousel')) {
        initInstitutionalHome();
    }

    // Inicializar componentes Vue cuando existan
    const appElement = document.getElementById('atlas-app');

    if (appElement && Object.keys(vueComponents).length > 0) {
        const app = createApp({});

        Object.entries(vueComponents).forEach(([name, component]) => {
            app.component(name, component);
        });

        app.mount(appElement);
    }

});

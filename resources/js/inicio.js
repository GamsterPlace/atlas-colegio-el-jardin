import { Collapse, Carousel } from 'bootstrap';

export function initInstitutionalHome() {
    const home = document.querySelector('.school-hero');
    if (!home) return;

    document.querySelectorAll('[data-image-fallback]').forEach((container) => {
        const image = container.querySelector('img');
        if (!image) return;
        const markMissing = () => container.classList.add('is-missing');
        image.addEventListener('error', markMissing, { once: true });
        if (image.complete && image.naturalWidth === 0) markMissing();
    });

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('institutionalMenu');
            if (menu?.classList.contains('show')) Collapse.getOrCreateInstance(menu).hide();
        });
    });

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const carousel = document.getElementById('schoolHeroCarousel');
        if (carousel) Carousel.getOrCreateInstance(carousel).pause();
    }
}

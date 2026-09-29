import { createIcons, Activity, ArrowLeft, ArrowRight, ArrowUpRight, Cable, Check, ChevronRight, Cog, DraftingCompass, Droplets, Download, Factory, FlaskConical, Gauge, Handshake, Mail, MapPin, Menu, Phone, Search, SearchX, Send, ShieldCheck, Wind, X, Zap } from 'lucide';

createIcons({ icons: { Activity, ArrowLeft, ArrowRight, ArrowUpRight, Cable, Check, ChevronRight, Cog, DraftingCompass, Droplets, Download, Factory, FlaskConical, Gauge, Handshake, Mail, MapPin, Menu, Phone, Search, SearchX, Send, ShieldCheck, Wind, X, Zap } });

const toggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#main-navigation');
if (toggle && navigation) {
    document.documentElement.classList.add('js');
    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Tutup navigasi' : 'Buka navigasi');
        toggle.title = open ? 'Tutup navigasi' : 'Buka navigasi';
        navigation.classList.toggle('is-open', open);
    };
    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
            toggle.focus();
        }
    });
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.site-header')) setOpen(false);
    });
    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a')) setOpen(false);
    });
    matchMedia('(min-width: 1080px)').addEventListener('change', () => setOpen(false));
}

document.querySelectorAll('[data-values-carousel]').forEach((carousel) => {
    const slides = [...carousel.querySelectorAll('[data-value-slide]')];
    const controls = carousel.querySelector('[data-carousel-controls]');
    const status = carousel.querySelector('[data-carousel-status]');
    if (slides.length < 2 || !controls) return;

    let current = 0;
    const show = (index) => {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, position) => {
            const inactive = position !== current;
            slide.classList.toggle('is-inactive', inactive);
            slide.setAttribute('aria-hidden', String(inactive));
            slide.inert = inactive;
        });
        status.textContent = `${String(current + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
    };
    show(0);
    controls.hidden = false;
    carousel.querySelector('[data-previous]').addEventListener('click', () => show(current - 1));
    carousel.querySelector('[data-next]').addEventListener('click', () => show(current + 1));
});

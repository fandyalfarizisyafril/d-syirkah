import { createIcons, ArrowLeft, ArrowRight, Building2, Check, ChevronLeft, ChevronRight, Cog, Download, ExternalLink, FileCheck, Gauge, Inbox, KeyRound, Layers, LayoutDashboard, LogIn, LogOut, Package, Pencil, Plus, Save, Search, ShieldCheck, Tags, Trash2 } from 'lucide';

const renderIcons = () => createIcons({ icons: { ArrowLeft, ArrowRight, Building2, Check, ChevronLeft, ChevronRight, Cog, Download, ExternalLink, FileCheck, Gauge, Inbox, KeyRound, Layers, LayoutDashboard, LogIn, LogOut, Package, Pencil, Plus, Save, Search, ShieldCheck, Tags, Trash2 } });
renderIcons();

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

document.querySelectorAll('[data-repeater]').forEach((repeater) => {
    const rows = repeater.querySelector('[data-rows]');
    const template = repeater.querySelector('template');
    const indexes = [...rows.querySelectorAll('[name]')].map((input) => Number(input.name.match(/\[(\d+)\]/)?.[1] ?? -1));
    let index = Math.max(-1, ...indexes) + 1;
    repeater.querySelector('[data-add-row]').addEventListener('click', () => {
        const fragment = document.createElement('template');
        fragment.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(index++));
        rows.append(fragment.content);
        renderIcons();
        rows.lastElementChild?.querySelector('input:not([type=hidden]), textarea')?.focus();
    });
    rows.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-remove-row]');
        if (remove) {
            remove.closest('[data-row]').remove();
            repeater.querySelector('[data-add-row]').focus();
        }
    });
});

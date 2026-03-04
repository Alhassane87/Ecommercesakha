import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const THEME_KEY = 'sakha-theme';
const THEME_VALUES = ['light', 'dark'];

function normalizeTheme(theme) {
    return THEME_VALUES.includes(theme) ? theme : 'light';
}

function getStoredTheme() {
    try {
        return normalizeTheme(localStorage.getItem(THEME_KEY));
    } catch {
        return 'light';
    }
}

function applyTheme(theme) {
    const resolvedTheme = normalizeTheme(theme);
    const root = document.documentElement;

    root.classList.toggle('dark', resolvedTheme === 'dark');

    root.setAttribute('data-theme', resolvedTheme);

    try {
        localStorage.setItem(THEME_KEY, resolvedTheme);
    } catch {
        // ignore storage issues
    }

    document.querySelectorAll('[data-theme-switch]').forEach((input) => {
        if ('value' in input) {
            input.value = resolvedTheme;
        }
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const targetTheme = normalizeTheme(button.getAttribute('data-theme-toggle'));
        const isActive = targetTheme === resolvedTheme;

        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
}

function bindThemeSwitchers() {
    document.querySelectorAll('[data-theme-switch]').forEach((input) => {
        input.addEventListener('change', (event) => {
            applyTheme(event.target.value);
        });
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            applyTheme(button.getAttribute('data-theme-toggle'));
        });
    });
}

window.SakhaTheme = {
    applyTheme,
    getStoredTheme,
};

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getStoredTheme());
    bindThemeSwitchers();
});

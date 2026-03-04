import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // SAKHA design tokens (primary set to night-blue)
                    primary: { 
                        DEFAULT: '#0B2545', // night blue
                        50: '#EAF2FA',
                        100: '#D6E6F6',
                        200: '#B3D4EE',
                        300: '#80BDE3',
                        400: '#4F97D6',
                        500: '#2E6FBF',
                        600: '#1F4F91',
                        700: '#133665',
                        800: '#0B2545',
                        900: '#081C35',
                    },
                accent: {
                    DEFAULT: '#F8B803'
                },
                neutral: {
                    100: '#f7f7f6',
                    300: '#dbdbd7',
                    500: '#706f6c',
                    800: '#1b1b18'
                },
                success: {
                    DEFAULT: '#16a34a'
                },
                warn: {
                    DEFAULT: '#f59e0b'
                },
                site: {
                    bg: '#FDFDFC',
                    darkbg: '#0a0a0a',
                    card: '#ffffff',
                    darkcard: '#161615'
                }
            },
        },
    },

    plugins: [forms],
};

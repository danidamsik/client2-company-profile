import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
            },
        },
        extend: {
            colors: {
                brand: {
                    white: '#ffffff',
                    ink: '#17120b',
                    charcoal: '#2a241d',
                    primary: '#f5b301',
                    primaryDark: '#c87900',
                    accent: '#ea5b0c',
                    accentDark: '#b94005',
                    soft: '#fff8e6',
                    muted: '#f8f5ee',
                    line: '#eadfcf',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'display-sm': ['2rem', { lineHeight: '2.5rem', fontWeight: '700' }],
                'display-md': ['2.5rem', { lineHeight: '3rem', fontWeight: '700' }],
                'display-lg': ['3.25rem', { lineHeight: '3.75rem', fontWeight: '700' }],
                'body-lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'body-md': ['1rem', { lineHeight: '1.625rem' }],
                'body-sm': ['0.875rem', { lineHeight: '1.375rem' }],
            },
            spacing: {
                18: '4.5rem',
                22: '5.5rem',
                30: '7.5rem',
            },
            borderRadius: {
                sm: '0.25rem',
                md: '0.375rem',
                lg: '0.5rem',
            },
            boxShadow: {
                card: '0 14px 38px rgba(23, 18, 11, 0.08)',
                lift: '0 20px 48px rgba(23, 18, 11, 0.14)',
                nav: '0 8px 24px rgba(23, 18, 11, 0.08)',
            },
        },
    },

    plugins: [forms],
};

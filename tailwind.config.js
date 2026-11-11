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
                sans: ['Inter', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
            },
            colors: {
                memodex: {
                    bg: '#0C1118',
                    panel: '#121B26',
                    raised: '#192535',
                    soft: '#202E40',
                    border: '#2A394B',
                    text: '#F1F4F8',
                    muted: '#9BAABD',
                    subtle: '#718196',
                    primary: '#83B9F2',
                    'primary-strong': '#5598DE',
                    'accent-warm': '#E6B979',
                    'accent-brass': '#C49A45',
                    success: '#78C5A2',
                    warning: '#E7B96F',
                    danger: '#E47D7D',
                },
            },
            aspectRatio: {
                card: '63 / 88',
            },
        },
    },

    plugins: [forms],
};

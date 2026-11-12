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
                display: ['"Plus Jakarta Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
                sans: ['Inter', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
            },
            colors: {
                memodex: {
                    bg: '#101827',
                    navy: '#17243A',
                    panel: '#1D293B',
                    raised: '#263449',
                    soft: '#263449',
                    stage: '#0B111C',
                    border: '#2D3C53',
                    'border-hover': '#3E5474',
                    text: '#F3F5F8',
                    muted: '#9DAABD',
                    subtle: '#6D7A8D',
                    primary: '#69AFFF',
                    'primary-strong': '#4E98F2',
                    silver: '#C8D2DF',
                    gold: '#D4B36A',
                    'accent-warm': '#D4B36A',
                    'accent-brass': '#B89448',
                    success: '#5CC99B',
                    warning: '#E5B567',
                    danger: '#EB6F75',
                },
            },
            aspectRatio: {
                card: '63 / 88',
            },
        },
    },

    plugins: [forms],
};

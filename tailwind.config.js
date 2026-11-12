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
                    bg: '#161F3B',
                    navy: '#26355D',
                    panel: '#26355D',
                    raised: '#304273',
                    soft: '#304273',
                    stage: '#11182E',
                    border: '#3B4E82',
                    'border-hover': '#5268A6',
                    text: '#F6F7FB',
                    muted: '#BCC6E0',
                    subtle: '#8B99BD',
                    primary: '#AF47D2',
                    'primary-strong': '#9333B5',
                    amber: '#FF8F00',
                    'amber-hover': '#FFA22B',
                    gold: '#FFDB00',
                    silver: '#D4DCF0',
                    'accent-warm': '#FFDB00',
                    'accent-brass': '#FF8F00',
                    success: '#5CC99B',
                    warning: '#FF8F00',
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

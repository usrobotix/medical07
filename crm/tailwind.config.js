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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['"PT Mono"', '"Ubuntu Mono"', 'Consolas', 'monospace'],
            },

            fontSize: {
                'ys-xs':  ['12px', { lineHeight: '16px' }],
                'ys-s':   ['14px', { lineHeight: '20px' }],
                'ys-m-s': ['16px', { lineHeight: '20px' }],
                'ys-m':   ['16px', { lineHeight: '24px' }],
                'ys-l':   ['20px', { lineHeight: '28px' }],
                'ys-xl':  ['24px', { lineHeight: '32px' }],
                'ys-xxl': ['32px', { lineHeight: '40px' }],
            },

            colors: {
                'dc-blue': {
                    10:  '#3782ff1a',
                    20:  '#3782ff33',
                    30:  '#3782ff4d',
                    40:  '#3782ff66',
                    50:  '#3782ff80',
                    60:  '#3782ff99',
                    100: '#3782ff',
                    120: '#dbe8ff',
                    200: '#2556ff',
                    300: '#0436e4',
                },
                'dc-gray': {
                    10:  '#cad6ee1a',
                    15:  '#b0bdd626',
                    20:  '#b0bdd633',
                    30:  '#a5b1ca4d',
                    35:  '#919ebb59',
                    40:  '#8694b066',
                    50:  '#475a8080',
                    60:  '#1e335d99',
                    70:  '#1a2b4dba',
                    80:  '#1e242e',
                    90:  '#121725',
                    200: '#f6f8fb',
                    300: '#eff2f7',
                    500: '#a3acbf',
                },
                'dc-green': {
                    10:  '#2eb27a26',
                    20:  '#2eb27a40',
                    60:  '#26aa72b3',
                    100: '#26aa72',
                    120: '#ccecd0',
                },
                'dc-red': {
                    10:  '#dd00001a',
                    20:  '#dd000033',
                    60:  '#dd000099',
                    100: '#dd0000',
                    120: '#f5b2b2',
                },
                'dc-orange': {
                    30:  '#ff82004d',
                    60:  '#ff820099',
                    100: '#ff8200',
                    120: '#ffd9b2',
                },
                'dc-yellow': {
                    10:  '#ffdd5766',
                    20:  '#ffdd578c',
                    100: '#ffdd57',
                    200: '#ffd21f',
                    300: '#f7c408',
                },
                'dc-violet': {
                    10:  '#685cfc1a',
                    100: '#685cfc',
                    120: '#e1defe',
                    200: '#544ad3',
                },
            },

            borderRadius: {
                '4xs':  '4px',
                '3xs':  '6px',
                '2xs':  '8px',
                'xs':   '10px',
                'sm':   '12px',
                'md':   '14px',
                'lg':   '20px',
            },

            spacing: {
                '18':  '18px',
                '22':  '22px',
                '26':  '26px',
                '30':  '30px',
                '72':  '72px',
                '92':  '92px',
                '312': '312px',
                '968': '968px',
            },

            boxShadow: {
                'card':    '0 2px 4px 0 rgba(77,86,103,.12), 0 3px 6px 0 rgba(77,86,103,.15)',
                'card-lg': '0 10px 20px rgba(165,177,202,.3), 0 3px 6px rgba(165,177,202,.3)',
                'popover': '0 0 3px rgba(165,177,202,.3), 0 10px 40px rgba(165,177,202,.3)',
                'tooltip': '0 4px 8px rgba(165,177,202,.3), 0 0 3px rgba(165,177,202,.3)',
                'dialog':  '0 10px 40px rgba(18,23,37,.5), 0 0 3px rgba(18,23,37,.5)',
            },

            transitionDuration: {
                '80':  '80ms',
                '100': '100ms',
                '150': '150ms',
            },
        },
    },

    plugins: [forms],
};

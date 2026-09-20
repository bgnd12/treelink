/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#f2f0ff',
                    100: '#e7e3ff',
                    200: '#d1c9ff',
                    300: '#b3a3ff',
                    400: '#9370ff',
                    500: '#7c4dff',
                    600: '#6c2fff',
                    700: '#5c22e8',
                    800: '#4c1cbf',
                    900: '#3f1c99',
                    950: '#250f5c',
                },
                ink: {
                    50: '#f7f7fb',
                    100: '#eeeef5',
                    200: '#d9d9e6',
                    300: '#b6b6cc',
                    400: '#8d8daf',
                    500: '#6d6d92',
                    600: '#565678',
                    700: '#454562',
                    800: '#2d2d40',
                    900: '#1a1a29',
                    950: '#0e0e17',
                },
            },
            boxShadow: {
                soft: '0 10px 40px -12px rgba(31, 20, 90, 0.18)',
                card: '0 2px 10px rgba(20, 15, 50, 0.06)',
            },
            borderRadius: {
                xl2: '1.25rem',
            },
            keyframes: {
                'fade-up': {
                    '0%': { opacity: 0, transform: 'translateY(12px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
                pop: {
                    '0%': { transform: 'scale(0.96)', opacity: 0 },
                    '100%': { transform: 'scale(1)', opacity: 1 },
                },
            },
            animation: {
                'fade-up': 'fade-up 0.6s ease both',
                pop: 'pop 0.25s ease both',
            },
        },
    },
    plugins: [],
};

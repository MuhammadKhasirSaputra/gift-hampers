import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
            // 🔽 Tambahan Warna & Shadow untuk Desain Gift & Hampers
            colors: {
                // Warna Utama (Pink)
                primary: {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899', // Pink Utama
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                },
                // Warna Sekunder (Ungu)
                secondary: {
                    50: '#faf5ff',
                    100: '#f3e8ff',
                    200: '#e9d5ff',
                    300: '#d8b4fe',
                    400: '#c084fc',
                    500: '#a855f7', // Ungu Utama
                    600: '#9333ea',
                    700: '#7e22ce',
                    800: '#6b21a8',
                    900: '#581c87',
                },
            },
            boxShadow: {
                // Shadow lembut untuk kartu dashboard dan produk
                'card': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'card-hover': '0 10px 25px -5px rgba(236, 72, 153, 0.15)', // Sedikit hint pink saat hover
            }
        },
    },

    plugins: [forms],
};
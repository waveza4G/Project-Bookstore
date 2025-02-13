import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'], // ใช้ app.jsx เป็นไฟล์หลัก
            refresh: true,
        }),
        react(),
    ],
});

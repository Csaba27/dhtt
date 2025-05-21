import {defineConfig} from "vite";
import tailwindcss from "@tailwindcss/vite";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    plugins: [
        tailwindcss()
    ],
};

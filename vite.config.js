import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import { resolve } from 'path';
import { homedir } from 'os';

export default defineConfig(({command, mode}) => {
    // Load current .env-file
    const env = loadEnv(mode, process.cwd(), '')

    // Set the host based on APP_URL
    let host = new URL(env.APP_URL).host
    let homeDir = homedir()
    let serverConfig = {}

    if (homeDir) {
        serverConfig = {
            https: {
                key: fs.readFileSync(
                    resolve(homeDir, `.config/valet/Certificates/${host}.key`),
                ),
                cert: fs.readFileSync(
                    resolve(homeDir, `.config/valet/Certificates/${host}.crt`),
                ),
            },
            hmr: {
                host
            },
            host
        }
    }

    return {
        publicDir: 'vendor/mixpost',
        plugins: [
            laravel({
                input: 'resources/js/app.js',
                publicDirectory: 'resources/dist',
                buildDirectory: 'vendor/mixpost',
                refresh: true
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        resolve: {
            alias: {
                '@css': '/resources/css',
                '@img': 'resources/img'
            },
        },
        server: serverConfig
    }
});

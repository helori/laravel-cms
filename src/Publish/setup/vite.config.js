import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ command, mode, ssrBuild }) => {
    return {
        manifest: true,
        brotliSize: false,
        outDir: 'public/build',
        build: {

        },
        //assetsInclude: ['./resources/images/**/*'],
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
                'server-renderer': '@vue/server-renderer/dist/server-renderer.esm-bundler.js',
            },
            // Importing these types doesn't require any extension in the file's names
            extensions: [
                '.js', '.ts', '.tsx', '.jsx', '.vue'
            ]
        },
        plugins: [
            laravel({
                input: [
                    'resources/css/admin.css',
                    'resources/js/admin.js',
                    'resources/css/website.css',
                    'resources/js/website.js',
                ],
                refresh: [
                    ...refreshPaths,
                    'resources/views/**',
                    'resources/js/**',
                    'resources/css/**',
                ],
            }),
            vue({
                template: {
                    /*compilerOptions: {
                        isCustomElement: (tag) => tag.startsWith('x-')
                    },*/
                    transformAssetUrls: {
                        // The Vue plugin will re-write asset URLs, when referenced
                        // in Single File Components, to point to the Laravel web
                        // server. Setting this to `null` allows the Laravel plugin
                        // to instead re-write asset URLs to point to the Vite
                        // server instead.
                        base: null,

                        // The Vue plugin will parse absolute URLs and treat them
                        // as absolute paths to files on disk. Setting this to
                        // `false` will leave absolute URLs un-touched so they can
                        // reference assets in the public directory as expected.
                        includeAbsolute: false,
                    },
                },
            }),
        ]
    }
})

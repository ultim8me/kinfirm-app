import { defineConfig } from "vite";
import { fileURLToPath } from 'node:url'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { VueRouterAutoImports, getPascalCaseRouteName } from 'unplugin-vue-router'
import VueRouter from 'unplugin-vue-router/vite'
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
  plugins: [
    VueRouter({
      getRouteName: routeNode => {
        return getPascalCaseRouteName(routeNode)
          .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
          .toLowerCase()
      },
      routesFolder: 'resources/js/pages',
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    vuetify(),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
    }),
    Components({
      dirs: ['resources/js/components'],
      dts: true,
    }),
    AutoImport({
      imports: ['vue', VueRouterAutoImports, 'pinia'],
      dirs: [
        './resources/js/utils/',
      ],
      vueTemplate: true,
      eslintrc: {
        enabled: true,
        filepath: './.eslintrc-auto-import.json',
      },
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
    },
  },
});

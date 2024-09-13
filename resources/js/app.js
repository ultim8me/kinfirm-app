import { createApp } from "vue";
import App from "./App.vue";
import { registerPlugins } from './utils/plugins'

import '@mdi/font/css/materialdesignicons.css'

const app = createApp(App)

registerPlugins(app)

app.mount('#app')

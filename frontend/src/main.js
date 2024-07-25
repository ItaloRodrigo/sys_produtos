/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'

// Components
import App from './App.vue'
import BaseLayout from "@/layouts/BaseLayout.vue";

// Composables
import { createApp } from 'vue'

const app = createApp(App)

registerPlugins(app)

app.component("base-layout",BaseLayout)



app.mount('#app')

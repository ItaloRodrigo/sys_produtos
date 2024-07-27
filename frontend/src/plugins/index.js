/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import router from '@/router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import api from './api'
import {auth_store} from "../stores/auth"
import { createPinia} from 'pinia';

import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

export function registerPlugins (app) {
  const pinia = createPinia();
  // pinia.use(piniaPluginPersistedstate);
  app
    .use(vuetify)
    .use(VueAxios, axios)
    .use(api)
    .use(pinia)
    .use(router)
    .use({
      install(app, options) {
        app.config.globalProperties.$auth = auth_store();
      },
    })
    .use(components)
    .use(directives)


    axios.defaults.baseURL=import.meta.env.VITE_API_URL
}

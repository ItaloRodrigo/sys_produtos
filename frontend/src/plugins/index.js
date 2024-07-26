/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import pinia from '@/stores'
import router from '@/router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import api from './api'
import auth_store from "../stores/auth"

export function registerPlugins (app) {
  app
    .use(vuetify)
    .use(router)
    .use(pinia)
    .use(VueAxios, axios)
    .use(api)
    .use({
      install(app, options) {
        app.config.globalProperties.$auth = auth_store();
      },
    });


    axios.defaults.baseURL=import.meta.env.VITE_API_URL
}

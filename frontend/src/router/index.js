/**
 * router/index.ts
 *
 * Automatic routes for `./src/pages/*.vue`
 */

// Composables
import { computed } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
// import { setupLayouts } from 'virtual:generated-layouts';
import {auth_store} from "../stores/auth";
import store from '../stores/loadingstore'


const routes = [
  {
    path: "/",
    name: "home",
    component: () => import("@/pages/Dashboard.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/pages/Login/Login.vue"),
  },
  {
    path: "/logout",
    name: "logout",
    component: () => import("@/pages/Login/Logout.vue"),
  },
  {
    path: "/users",
    name: "users",
    component: () => import("@/pages/Usuarios/Usuarios.vue"),
  },
  {
    path: "/produtos",
    name: "produtos",
    component: () => import("@/pages/Produtos/Produtos.vue"),
  },
  {
    path: "/:pathMatch(.*)*",
    name: "notfound",
    component: () => import("@/pages/NotFound.vue"),
  },
]


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  // extendRoutes: setupLayouts,
  routes
});

router.beforeEach((to, from, next) => {
  document.title = import.meta.env.VITE_TITLE+" - "+to.name;
  //---
  const store_auth = auth_store();
  // const user = computed(() => store.getUser);
  //---
  store.setLoading(true);
  //---
  if ((to.name != "login") && (!store_auth.isAuth)) {
    next('/login');
  } else {
    next();
  }
});

router.afterEach(() => {
  store.setLoading(false)
})

export default router

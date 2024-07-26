/**
 * router/index.ts
 *
 * Automatic routes for `./src/pages/*.vue`
 */

// Composables
import { createRouter, createWebHistory } from 'vue-router';
import { setupLayouts } from 'virtual:generated-layouts';
import auth_store from "../stores/auth";


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
    path: "/users",
    name: "users",
    component: () => import("@/pages/Usuarios/Usuarios.vue"),
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
  const store = auth_store();
  //---
  console.log("chegou aqui!")
  //---
  if ((to.name != "login") && (store.isAuth)) {
    next();
  } else {
    next({
      name: "login",
    });
  }
});

export default router

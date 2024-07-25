/**
 * router/index.ts
 *
 * Automatic routes for `./src/pages/*.vue`
 */

// Composables
import { createRouter, createWebHistory } from 'vue-router'
import { setupLayouts } from 'virtual:generated-layouts'


const routes = [
  {
    path: "/",
    name: "dashboard",
    component: () => import("@/pages/Dashboard.vue"),
  },
  {
    path: "/users",
    name: "users",
    component: () => import("@/pages/Usuarios.vue"),
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
})

export default router

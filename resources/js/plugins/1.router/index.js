import { createRouter, createWebHistory } from 'vue-router/auto'
import { routes } from 'vue-router/auto-routes'
import { redirects, additionalRoutes } from './additional-routes'
import { setupGuards } from './guards'


const router = createRouter({
  history: createWebHistory('/'),
  scrollBehavior(to) {
    if (to.hash)
      return { el: to.hash, behavior: 'smooth', top: 60 }

    return { top: 0 }
  },
  routes: [
    ...routes,
    ...redirects,
    ...additionalRoutes,
  ],
})

setupGuards(router)

export { router }

export default function (app) {
  app.use(router)
}

import { createPinia } from 'pinia'

export const pinia = createPinia()

export default function (app) {
  app.use(pinia)
}

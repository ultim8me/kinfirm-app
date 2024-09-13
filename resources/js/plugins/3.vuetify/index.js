import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import { themes } from './theme'

export default function (app) {

  const vuetify = createVuetify({
    theme: {
      defaultTheme: import.meta.env.VITE_APP_THEME || 'dark',
      themes: themes,
    },
  })

  app.use(vuetify)
}

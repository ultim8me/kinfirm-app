import { isNullOrUndefined } from "@/utils/helpers.js";

export const useUserStore = defineStore('user', {

  state: () => {
    return {
      authenticatedUser: {
        id: null,
        name: '',
        email: '',
        access_token: null
      },
      fetching: false,
    }
  },

  getters: {
    isLoggedIn: (state) => !isNullOrUndefined(state.authenticatedUser.id)
  },
  actions: {

      async fetchAuthUser() {
          this.fetching = true
          await $api('auth/user')
              .then(result => {
                  this.authenticatedUser = result
              })
              .finally(() => {
                  this.fetching = false
              });
      },

      async isAuthenticated() {

          let authenticated = false

          if (!isNullOrUndefined(this.authenticatedUser?.id)) {
              authenticated = true;
          } else {
            await this.fetchAuthUser()
                  .then(result => {
                      authenticated = true
                  })
                  .catch(err => {
                      authenticated = false
                  })
          }

          return authenticated
      },

    async login(credentials) {

      const onResponseError = async ({ response }) => {
        await Promise.reject(response._data)
      };

      const response = await $api('auth/login', 'POST', credentials, onResponseError)

      this.authenticatedUser = response

      useCookie('accessToken').value = response?.access_token

    },

    async logout() {

      const onResponseError = async ({ response }) => {
        await Promise.reject(response._data)
      };

      await $api('auth/logout', 'POST', {}, onResponseError)

      this.authenticatedUser = {
        id: null,
        name: '',
        email: '',
        access_token: null
      }

      useCookie('accessToken').value = null

    },

    async register(data) {

      const onResponseError = async ({ response }) => {
        await Promise.reject(response._data)
      };

      const response = await $api('auth/register', 'POST', data, onResponseError)

      this.authenticatedUser = response

      useCookie('accessToken').value = response?.access_token

    },
  },
})

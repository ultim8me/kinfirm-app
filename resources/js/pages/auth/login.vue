<script setup>
import { useUserStore } from "@/stores/user.js";

definePage({
  meta: {
    unauthenticatedOnly: true,
  },
})

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

const appName = import.meta.env.VITE_APP_NAME

const isPasswordVisible = ref(false)

const credentials = ref({
  email: '',
  password: '',
})

const errors = ref({
  email: undefined,
  password: undefined,
})

const refVForm = ref()

const login = async () => {

  await userStore.login(credentials.value)
    .then(response => {
      nextTick(() => {
        router.replace(route.query.to ? String(route.query.to) : '/')
      })
      router.push({ name: 'root' })
    })
    .catch(response => {
      if (response?.errors) {
        errors.value = response.errors
      }
    })

}

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) {
      login()
    }
  })
}
</script>

<template>


  <VContainer
    class="d-flex align-center"
    style="min-block-size: 100dvh;"
  >

    <VRow
      class="d-flex justify-center"
      no-gutters
    >
      <VCol
        cols="12"
        md="4"
        class="d-flex justify-center"
      >
        <VCard
          flat
          :max-width="450"
          class="mt-12 mt-sm-0 pa-4"
        >
          <VCardText>
            <h4 class="text-h4 mb-1">
              Welcome to <span class="text-capitalize">{{ appName }}</span>! 👋🏻
            </h4>
          </VCardText>
          <VCardText>
            <VForm
              ref="refVForm"
              @submit.prevent="onSubmit"
            >
              <VRow>
                <VCol cols="12">
                  <AppTextField
                    v-model="credentials.email"
                    autofocus
                    autocomplete="on"
                    label="Email"
                    type="email"
                    :rules="[requiredValidator, emailValidator]"
                    :error-messages="errors.email"
                  />
                </VCol>

                <VCol cols="12">
                  <AppTextField
                    v-model="credentials.password"
                    label="Password"
                    autocomplete="on"
                    :rules="[requiredValidator]"
                    :error-messages="errors.password"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />

                  <div class="d-flex justify-end mt-2 mb-4">
                    <RouterLink
                      class="text-primary ms-2 mb-1"
                      :to="{ name: 'auth-register' }"
                    >
                      Register
                    </RouterLink>
                  </div>

                  <VBtn
                    block
                    color="primary"
                    type="submit"
                  >
                    Login
                  </VBtn>
                </VCol>
              </VRow>

            </VForm>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

  </VContainer>


</template>

<style scoped>

</style>

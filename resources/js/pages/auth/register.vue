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

const isPasswordVisible = ref(false)

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref({
  name: undefined,
  email: undefined,
  password: undefined,
  password_confirmation: undefined,
})

const refVForm = ref()

const register = async () => {

  await userStore.register(form.value)
    .then(response => {
      nextTick(() => {
        router.replace(route.query.to ? String(route.query.to) : '/')
      })
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
      register()
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
      no-gutters
      class="d-flex justify-center"
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
              Registration
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
                    v-model="form.name"
                    autofocus
                    autocomplete="on"
                    label="Name"
                    type="text"
                    :rules="[requiredValidator]"
                    :error-messages="errors.name"
                  />
                </VCol>

                <VCol cols="12">
                  <AppTextField
                    v-model="form.email"
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
                    v-model="form.password"
                    label="Password"
                    placeholder="············"
                    :rules="[requiredValidator, passwordValidator]"
                    :error-messages="errors.password"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />
                </VCol>

                <VCol cols="12">
                  <AppTextField
                    v-model="form.password_confirmation"
                    label="Password confirmation"
                    autocomplete="on"
                    placeholder="············"
                    :rules="[requiredValidator, confirmedValidator(form.password_confirmation, form.password)]"
                    :error-messages="errors.password_confirmation"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />

                  <div class="d-flex justify-end mt-2 mb-4">
                    <RouterLink
                      class="text-primary ms-2 mb-1"
                      :to="{ name: 'auth-login' }"
                    >
                      Login
                    </RouterLink>
                  </div>

                  <VBtn
                    block
                    color="primary"
                    type="submit"
                  >
                    Register
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

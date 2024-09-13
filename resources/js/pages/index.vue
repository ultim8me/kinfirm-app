<script setup>
import { useUserStore } from "@/stores/user.js";

const router = useRouter()
const userStore = useUserStore()

const logout = async () => {
  await userStore.logout()
  await router.push({ name: 'auth-login' })
}

</script>

<template>

  <VContainer class="">

    <VRow
      class="d-flex align-center mb-3"
      no-gutters
    >
      <h1>Home page</h1>
    </VRow>

    <VRow
      no-gutters
    >
      <VCol
        cols="12"
        md="4"
      >
        <VCard
          class="py-2 px-5 ma-2 pa-2"
        >
          <v-list density="comfortable" lines="two">
            <v-list-item
              v-if="userStore.isLoggedIn"
              color="primary"
            >
              <template v-slot:prepend>
                <v-icon icon="mdi-account"></v-icon>
              </template>
              <v-list-item-title>
                <RouterLink :to="{ name: 'account' }">Account page</RouterLink>
              </v-list-item-title>
            </v-list-item>

            <v-list-item
              v-else
              color="primary"
            >
              <template v-slot:prepend>
                <v-icon icon="mdi-login"></v-icon>
              </template>
              <v-list-item-title>
                <RouterLink :to="{ name: 'auth-login' }">Login page</RouterLink>
              </v-list-item-title>
            </v-list-item>

            <v-list-item
              color="primary"
            >
              <template v-slot:prepend>
                <v-icon icon="mdi-format-list-bulleted"></v-icon>
              </template>
              <v-list-item-title>
                <RouterLink :to="{ name: 'products' }">Products page</RouterLink>
              </v-list-item-title>
            </v-list-item>

            <v-list-item
              v-if="userStore.isLoggedIn"
              color="primary"
            >
              <template v-slot:prepend>
                <v-icon icon="mdi-logout"></v-icon>
              </template>
              <v-list-item-title>
                <a href="#" @click="logout">Logout</a>
              </v-list-item-title>
            </v-list-item>

          </v-list>


        </VCard>

      </VCol>

    </VRow>
  </VContainer>

</template>

<style scoped>

</style>

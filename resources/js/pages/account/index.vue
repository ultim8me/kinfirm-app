<script setup>
import { useUserStore } from "@/stores/user.js";
import { goBackOrRoute } from "@/utils/helpers.js";

const router = useRouter()
const userStore = useUserStore()

</script>

<template>
  <VContainer class="">

    <VRow
      class="d-flex align-center mb-3"
      no-gutters
    >
      <v-btn
        class="me-4"
        size="large"
        variant="text"
        icon="mdi-chevron-left"
        @click="goBackOrRoute(router, { name: 'root' })"
      ></v-btn>

      <h1>Account page</h1>

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
          :loading="userStore.fetching"
        >

          <template #loader="{ isActive }">
            <v-skeleton-loader v-if="isActive" type="heading,	text@10"></v-skeleton-loader>
          </template>

          <VCardItem v-if="!userStore.fetching">
            <VCardTitle>
              {{ userStore?.authenticatedUser.name }}
            </VCardTitle>
          </VCardItem>

          <VCardText v-if="!userStore.fetching">
            <pre>{{ userStore?.authenticatedUser.email }}</pre>
          </VCardText>

        </VCard>

      </VCol>

    </VRow>
  </VContainer>
</template>

<style scoped>

</style>


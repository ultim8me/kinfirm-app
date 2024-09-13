<script setup>
definePage({
  meta: {
    public: true,
  },
})

const route = useRoute()

console.log(route);

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
        cols="4"
      >
        <v-alert
          title="Page not found"
          variant="tonal"
          type="error"
        >
          <template v-slot:text>
            Page <strong>{{ route.path }}</strong> does not exist! Return to home <RouterLink :to="{ name: 'root' }">page</RouterLink>
          </template>
        </v-alert>
      </VCol>
    </VRow>

  </VContainer>

</template>


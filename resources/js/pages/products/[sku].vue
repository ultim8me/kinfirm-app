<script setup>
import { useProductStore } from "@/stores/product.js";
import { goBackOrRoute } from "@/utils/helpers.js";

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()

onMounted( () => {
  const sku = route.params?.sku

  if (sku) {
    productStore.fetchProductBySku(sku)
  }
})
</script>

<template>
  <VContainer>

    <VRow
      class="d-flex align-center mb-3"
      no-gutters
    >
      <v-btn
        class="me-4"
        size="large"
        variant="text"
        icon="mdi-chevron-left"
        @click="goBackOrRoute(router, { name: 'products' })"
      ></v-btn>

      <h1>Product page</h1>

    </VRow>

    <VRow
      no-gutters
    >
      <VCol
        cols="12"
        md="4"
      >
        <VCard
          class="py-2 px-5 ma-2 "
          :loading="productStore.fetching"
          variant="elevated"
        >
          <template #loader="{ isActive }">
            <v-skeleton-loader v-if="isActive" type="image, heading, text@1"></v-skeleton-loader>
          </template>

          <v-img
            class="cursor-pointer"
            height="200px"
            :src="productStore.product?.photo_url"
            cover
          ></v-img>

          <v-card-title>
            {{ productStore.product?.sku }}
          </v-card-title>

          <v-card-subtitle>
            Size: {{ productStore.product?.size?.name }}
          </v-card-subtitle>

          <v-card-actions>

            <v-btn
              v-for="tag in productStore.product?.tags"
              :key="tag.id"
              color="orange-lighten-2"
              :text="tag.title"
            ></v-btn>

            <v-spacer></v-spacer>

          </v-card-actions>

          <div>
            <v-divider></v-divider>
            <v-card-text>
              {{ productStore.product?.description }}
            </v-card-text>
            <v-divider v-if="productStore.product?.cities?.length"></v-divider>
            <v-card-text>
              <h3>Stock: {{ !productStore.product?.cities?.length ? 'N/A' : '' }}</h3>
              <p
                v-for="city in productStore.product?.cities"
                :key="city.id"
              >{{ city.name }}: {{ city?.stock?.quantity }}</p>
            </v-card-text>
          </div>

        </VCard>

      </VCol>

    </VRow>
  </VContainer>
</template>

<style scoped>

</style>

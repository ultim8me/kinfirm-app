<script setup>
import { useProductStore } from "@/stores/product.js";
import { goBackOrRoute } from "@/utils/helpers.js";

const router = useRouter()
const productStore = useProductStore()

const scrollComponent = ref(null)

const open = ref(null)

onMounted( () => {
  if (productStore.pagination.current_page > productStore.pagination.last_page) {
    productStore.fetchProducts()
  }
  window.addEventListener("scroll", handleScroll)
})

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll)
})

const handleScroll = (e) => {

  let element = scrollComponent.value.$el

  if (element.getBoundingClientRect().bottom < window.innerHeight) {

    if (productStore.pagination.current_page < productStore.pagination.last_page)
      if (!productStore.fetching) {
        productStore.fetchProducts(++productStore.pagination.current_page)
      }
  }
}

const openProduct = product => {
  router.push({
    name: 'products-sku',
    params: { sku: product.sku },
  })
  productStore.product = product
}

const toggleOpen = product => {
  open.value = open.value === product?.id ? null : product?.id
}

</script>

<template>

  <VContainer
  >

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

      <h1>Products page</h1>

    </VRow>

    <VRow
      no-gutters
      ref="scrollComponent"
    >
      <VCol
        v-for="product in productStore.products"
        :key="product.id"
        cols="12"
        md="3"
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
            @click="openProduct(product)"
            height="200px"
            :src="product?.photo_url"
            cover
          ></v-img>

          <v-card-title>
            {{ product?.sku }}
          </v-card-title>

          <v-card-subtitle>
            Size: {{ product?.size.name }}
          </v-card-subtitle>

          <v-card-actions>

            <v-btn
              v-for="tag in product?.tags"
              :key="tag.id"
              color="orange-lighten-2"
              :text="tag.title"
            ></v-btn>

            <v-spacer></v-spacer>

            <v-btn
              icon="mdi-open-in-new"
              @click="openProduct(product)"
            ></v-btn>

            <v-btn
              :icon="open === product?.id ? 'mdi-chevron-up' : 'mdi-chevron-down'"
              @click="toggleOpen(product)"
            ></v-btn>
          </v-card-actions>

          <v-expand-transition>
            <div v-show="open === product?.id">
              <v-divider></v-divider>
              <v-card-text>
                {{ product?.description }}
              </v-card-text>
            </div>
          </v-expand-transition>

        </VCard>

      </VCol>

    </VRow>
  </VContainer>

</template>

<style scoped>

</style>

export const useProductStore = defineStore('product', {

  state: () => {
    return {
      product: {
        id: null,
        sku: null,
        size_id: null,
        photo_url: '',
        description: '',
      },
      products: [],
      pagination: {
        current_page: 1,
        last_page: null,
        per_page: null,
        total: null,
      },
      fetching: false,
    }
  },

  getters: {
    hasProducts: (state) => state.products.length > 0,
    getProductById: (state) => {
      return (id) => state.products.find((product) => product.id === id)
    },
  },
  actions: {

    async fetchProductBySku(sku) {
      this.fetching = true
      await $api(`products/${sku}`)
        .then(result => {
          this.product = result
        })
        .finally(() => {
          this.fetching = false
        });
    },

    async fetchProducts(page = 1) {
      this.fetching = true
      await $api(`products?page=${page}`)
        .then(result => {
          const response = result?.data || []
          this.products.push(...response)
          this.pagination = result?.meta || []
        })
        .finally(() => {
          this.fetching = false
        });
    },

  },
})

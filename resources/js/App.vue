<script setup>

</script>

<template>

  <VApp>
    <router-view v-slot="{ Component, route }">
      <div :key="route.name">
        <Component :is="Component"/>
      </div>
    </router-view>
  </VApp>

</template>

<style scoped>

</style>

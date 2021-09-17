<template>
  <nuxt-child :keep-alive="!!$route.meta.keepAlive"></nuxt-child>
</template>

<script>
export default {
  name: "index"
}
</script>

<style>
</style>
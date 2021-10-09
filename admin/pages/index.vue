<template>
  <nuxt-child :keep-alive="!!$route.meta.keepAlive"></nuxt-child>
</template>

<script>
export default {
  name: "index",
  computed: {
    keepAliveConf() {
      return this.$store.getters["local/keepAliveList"];
    }
  },
  data() {
    return {
      pageActive: false
    }
  },
  activated() {
    this.pageActive = true
  },
  deactivated() {
    this.pageActive = false
  },
  watch: {
    keepAliveConf(n) {
      console.warn(n);
      let name = this.$route.name;
      // console.log("check destroy", name);
      if (!this.$store.getters["local/keepAliveList"].includes(name) && !this.pageActive) {
        // console.log("destroy", name);
        this.$destroy()
      }
    }
  },
}
</script>

<style>
</style>
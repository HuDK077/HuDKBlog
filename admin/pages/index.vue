<template>
  <nuxt-child :keep-alive="!!$route.meta.keepAlive"></nuxt-child>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "index",
  computed: {
    ...mapGetters({
      keepAliveList: "local/keepAliveList"
    }),
  },
  data() {
    return {
      pageActive: false
    }
  },
  activated() {
    console.log("activated");
    this.pageActive = true
  },
  deactivated() {
    console.log("deactivated");
    this.pageActive = false
  },
  watch: {
    keepAliveList(n, o) {
      console.warn(n, o);
      let name = this.$route.name;
      console.log("check destroy", name);
      if (!n.includes(name) && !this.pageActive) {
        this.$destroy()
      }
    }
  },
}
</script>

<style>
</style>
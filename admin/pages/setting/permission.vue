<template>
  <div>
    <el-tabs :value="$route.name" @tab-click="handleClick">
      <el-tab-pane label="角色权限" name="setting.permission.role"></el-tab-pane>
      <el-tab-pane label="用户权限" name="setting.permission.user"></el-tab-pane>
    </el-tabs>
    <nuxt-child ref="child" :keep-alive="!!$route.meta.keepAlive"></nuxt-child>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeName: 'setting.permission.role'
    }
  },
  methods: {
    handleClick(tab, event) {
      this.$router.push({ name: tab.name })
      // console.log(tab, event);
    }
  },
}
</script>

<style lang="scss" scoped>
</style>
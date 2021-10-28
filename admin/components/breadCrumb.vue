<template>
  <el-breadcrumb class="app-breadcrumb" separator="/">
    <transition-group name="breadcrumb">
      <template v-for="(item, index) in levelList">
        <el-breadcrumb-item :key="item.name" v-if="item.title || item.meta.title">
          <span v-if="item.redirect === 'noredirect' || index == levelList.length - 1" class="no-redirect">{{
            item.title || item.meta.title
          }}</span>
          <router-link v-else :to="item.redirect || item.path">
            {{ item.title || item.meta.title }}
          </router-link>
        </el-breadcrumb-item>
      </template>
    </transition-group>
  </el-breadcrumb>
</template>

<script>
export default {
  name: 'breadCrumb',
  created() {
    this.getBreadcrumb()
  },
  data() {
    return {
      levelList: null
    }
  },
  watch: {
    $route() {
      this.getBreadcrumb()
    }
  },
  methods: {
    getBreadcrumb() {
      let matched = this.$route.matched.filter(item => item.name)
      this.levelList = matched
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.app-breadcrumb.el-breadcrumb {
  display: inline-block;
  font-size: 14px;
  line-height: 50px;
  margin-left: 10px;
  .no-redirect {
    @include font_color("navbar-color2");
    cursor: text;
  }
}
</style>

<style lang="scss">
// .el-breadcrumb__inner {
//   a {
//     @include font_color("navbar-color");
//   }
// }
// .el-breadcrumb__item:last-child {
//   color: red;
//   .el-breadcrumb__inner {
//     @include font_color("navbar-color2");
//   }
// }
</style>
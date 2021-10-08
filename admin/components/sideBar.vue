<template>
  <div>
    <div class="logo" @click="toHome">
      <transition name="el-fade-in-linear">
        <e-img v-if="sideShow" :id="siteConfig.logo" src="/image/logo.png" class="logo-img">
          <el-image slot="error" src="/image/logo.png"></el-image>
        </e-img>
      </transition>
      <transition name="el-fade-in-linear">
        <e-img v-if="!sideShow" :id="siteConfig.logo_sm" src="/image/logo_sm.png" class="logo-img-sm">
          <el-image slot="error" src="/image/logo_sm.png"></el-image>
        </e-img>
      </transition>
    </div>

    <el-scrollbar wrap-class="scrollbar-wrapper">
      <el-menu
        class="side-bar-menu"
        :collapse="!sideShow"
        :default-active="active"
        :background-color="bgc"
        :text-color="tc"
        :active-text-color="atc"
        :router="true"
      >
        <template v-for="item in menuList">
          <!-- 单item -->
          <el-menu-item
            v-if="!item.children && menus.includes(item.name)"
            :key="item.name"
            :index="item.name"
            :route="item.path"
            :disabled="item.disabled"
          >
            <i class="menu-icon" :class="item.meta.icon" />
            <span slot="title">{{ item.meta.title }}</span>
          </el-menu-item>
          <!-- 有二级 -->
          <el-submenu v-if="item.children && menus.includes(item.name)" :key="item.name" :index="item.name">
            <template slot="title">
              <i class="menu-icon" :class="item.meta.icon" />
              <span>{{ item.meta.title }}</span>
            </template>

            <template v-for="child in item.children">
              <el-menu-item
                v-if="menus.includes(child.name)"
                :key="child.name"
                :index="child.name"
                :route="child.path"
                :disabled="child.disabled"
              >
                <i class="menu-icon" :class="child.meta.icon" />
                <span slot="title">{{ child.meta.title }}</span>
              </el-menu-item>
            </template>
          </el-submenu>
        </template>
      </el-menu>
    </el-scrollbar>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
const themes = {
  default: {
    bgc: "#304156",
    tc: "#bfcbd9",
    atc: "#409EFF"
  },
  light: {
    bgc: "#ffffff",
    tc: "#54667a",
    atc: "#2cabe3"
  }
};
export default {
  computed: {
    ...mapGetters({
      sideShow: "local/sidebar",
      routes: "permission/routes",
      menus: "permission/menus",
      theme: "local/theme",
      siteConfig: "local/siteConfig"
    }),
    active() {
      if (this.$route.meta && this.$route.meta.equal) {
        return this.$route.meta.equal
      }
      let name = this.$route.name.split(":")[0];
      return name
    },
    bgc() {
      return themes[this.theme].bgc || "";
    },
    tc() {
      return themes[this.theme].tc || "";
    },
    atc() {
      return themes[this.theme].atc || "";
    },
  },
  data() {
    return {
      sortList: [],
      menuList: [],
      childSort: [],
    }
  },
  methods: {
    sortMenu() {
      if (!this.routes.length) {
        return
      }
      if (!this.menus.length) {
        this.menuList = this.routes;
      } else {
        let menuList = this.$clone(this.routes);
        // 排除空列表
        let tempList = [];
        menuList.forEach(v => {
          if (v.children) {
            let child = 0;
            v.children.forEach(c => {
              if (this.menus.includes(c.name)) {
                child++;
              }
            })
            if (child) {
              tempList.push(v);
            }
          } else {
            tempList.push(v);
          }
        })
        this.sort4Tree(tempList);
        this.menuList = tempList;
        this.childSort = [];
      }
    },
    //  树形排序
    sort4Tree(list) {
      let base = this.menus;
      list.sort((a, b) => {
        if (a.children && !this.childSort.includes(a.name)) {
          this.sort4Tree(a.children);
          this.childSort.push(a.name);
        }
        if (b.children && !this.childSort.includes(b.name)) {
          this.sort4Tree(b.children)
          this.childSort.push(b.name);
        }
        return base.indexOf(a.name) < base.indexOf(b.name) ? -1 : 0;
      })
    },
    // 去首页
    toHome() {
      this.$router.push({ path: "/" })
    }
  },
  created() {
    this.sortMenu();
  },
  watch: {
    routes(n) {
      if (n) {
        this.sortMenu()
      }
    },
    menus(n) {
      if (n) {
        this.sortMenu()
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.logo {
  height: $navbar-height;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  border-right: 1px solid #f6f6f6;
  border-bottom: 1px solid #f6f6f6;

  @include border_color("sidebar-border-color");

  > .logo-img {
    height: $navbar-height;
    width: $sidebar-width;
  }
}

.hideSidebar .logo-img-sm {
  height: $navbar-height;
  width: $sidebar-hide-width;
}

.el-submenu.is-opened {
  ::v-deep .el-submenu__title {
    // background-color: #f6f6f6 !important;
    @include background_color("sidebar-active-bg");
  }
  ::v-deep .el-menu-item {
    @include background_color("sidebar-active-item-bg");
  }
}
</style>

<style >
.el-menu {
  border: none !important;
}
</style>

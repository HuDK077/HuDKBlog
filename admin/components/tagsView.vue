<template>
  <div class="tags-view-container">
    <scroll-pane class="tags-view-wrapper" ref="scrollPane">
      <transition-group name="list">
        <router-link
          ref="tag"
          class="tags-view-item"
          :class="isActive(tag)?'active':''"
          v-for="tag in Array.from(visitedViews)"
          :to="tag.path"
          :key="tag.path"
          @contextmenu.prevent.native="openMenu(tag,$event)"
        >
          {{tag.title}}
          <span
            class="el-icon-close"
            v-if="visitedViews.length > 1 "
            @click.prevent.stop="closeSelectedTag(tag)"
          ></span>
        </router-link>
      </transition-group>
    </scroll-pane>
    <ul class="contextmenu" v-show="visible" :style="{left:left+'px',top:top+'px'}">
      <li :class="visitedViews.length <= 1 ? 'disabled' : ''" @click="closeSelectedTag(selectedTag)">关闭</li>
      <li :class="visitedViews.length <= 1 ? 'disabled' : ''" @click="closeOthersTags">关闭其他</li>
      <li :class="visitedViews.length <= 1 ? 'disabled' : ''" @click="closeAllTags">关闭所有</li>
    </ul>
  </div>
</template>

<script>
import scrollPane from './scrollPane'

export default {
  name: 'tagsView',
  components: { scrollPane },
  data() {
    return {
      visible: false,
      top: 0,
      left: 0,
      selectedTag: {}
    }
  },
  computed: {
    visitedViews() {
      return this.$store.state.tagsView.visitedViews
    }
  },
  mounted() {
    this.addViewTags()
  },
  methods: {
    // 校验tag
    generateRoute() {
      if (this.$route.name) {
        return this.$route
      }
      return false
    },
    // 是否是当前tag
    isActive(route) {
      return route.path === this.$route.path
    },
    // 新增tag
    addViewTags() {
      const route = this.generateRoute()
      if (!route) {
        return false
      }
      if (route.name.includes(":") || (route.meta && route.meta.tagHidden)) {
        return false;
      }
      this.$store.dispatch('tagsView/addVisitedViews', route)
    },
    // 移动到当前tag
    moveToCurrentTag() {
      const tags = this.$refs.tag
      this.$nextTick(() => {
        for (const tag of tags) {
          if (tag.to === this.$route.path) {
            this.$refs.scrollPane.moveToTarget(tag.$el)
            break
          }
        }
      })
    },
    // 关闭选中tag
    closeSelectedTag(view) {
      if (this.visitedViews.length <= 1) {
        return
      }

      this.$store.dispatch('tagsView/delVisitedViews', view).then((views) => {
        if (this.isActive(view)) {
          const latestView = views.slice(-1)[0]
          if (latestView) {
            this.$router.push(latestView.path)
          } else {
            this.$router.push('/')
          }
        }
      })
    },
    // 关闭其他tags
    closeOthersTags() {
      if (this.visitedViews.length <= 1) {
        return
      }
      this.$router.push(this.selectedTag.path)
      this.$store.dispatch('tagsView/delOthersViews', this.selectedTag).then(() => {
        this.moveToCurrentTag()
      })
    },
    // 关闭所有tag
    closeAllTags() {
      if (this.visitedViews.length <= 1) {
        return
      }
      this.$store.dispatch('tagsView/delAllViews')
      if (this.$route.meta && this.$route.meta.mainPage) {
        this.addViewTags()
      } else {
        this.$router.push('/')
      }
    },
    // 右击打开菜单
    openMenu(tag, e) {
      // console.log(e);
      this.visible = true
      this.selectedTag = tag
      this.left = e.clientX
      this.top = e.clientY
    },
    // 关闭菜单
    closeMenu() {
      this.visible = false
    }
  },
  watch: {
    $route() {
      this.addViewTags()
      this.moveToCurrentTag()
    },
    visible(value) {
      if (value) {
        document.body.addEventListener('click', this.closeMenu)
      } else {
        document.body.removeEventListener('click', this.closeMenu)
      }
    }
  },
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.tags-view-container {
  .tags-view-wrapper {
    background: #fff;
    height: 35px;
    border-bottom: 1px solid #d8dce5;
    // box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 0 3px 0 rgba(0, 0, 0, 0.04);
    .tags-view-item {
      display: inline-block;
      position: relative;
      height: 26px;
      line-height: 26px;
      border: 1px solid #d8dce5;
      // color: #495060;
      // background: #fff;
      @include font_color("tags-view-color");
      @include background_color("tags-view-bg");
      padding: 0 8px;
      font-size: 12px;
      margin-left: 5px;
      margin-top: 4px;
      text-decoration: none;
      &:first-of-type {
        margin-left: 15px;
      }
      &.active {
        // background-color: #42b983;
        // color: #fff;
        // border-color: #42b983;
        @include font_color("tags-view-active-color");
        @include background_color("tags-view-active-bg");
        @include border_color("tags-view-active-border");
        &::before {
          content: "";
          // background: #fff;
          @include background_color("tags-view-active-color");
          display: inline-block;
          width: 8px;
          height: 8px;
          border-radius: 50%;
          position: relative;
          margin-right: 2px;
        }
      }
    }
  }
  .contextmenu {
    margin: 0;
    background: #fff;
    // z-index: 100;
    position: fixed;
    // position: absolute;
    list-style-type: none;
    padding: 5px 0;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 400;
    color: #333;
    box-shadow: 2px 2px 3px 0 rgba(0, 0, 0, 0.3);
    li {
      margin: 0;
      padding: 7px 16px;
      cursor: pointer;
      &.disabled {
        color: #bbbbbb;
        cursor: no-drop;
      }

      &:hover {
        background: #eee;
      }
    }
  }
}
</style>

<style rel="stylesheet/scss" lang="scss">
//reset element css of el-icon-close
.tags-view-wrapper {
  .tags-view-item {
    .el-icon-close {
      width: 16px;
      height: 16px;
      vertical-align: 2px;
      border-radius: 50%;
      text-align: center;
      transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
      transform-origin: 100% 50%;
      &:before {
        transform: scale(0.6);
        display: inline-block;
        vertical-align: -3px;
      }
      &:hover {
        background-color: #b4bccc;
        color: #fff;
      }
    }
  }
}
</style>

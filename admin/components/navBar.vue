<template>
  <div class="navbar">
    <div class="bar">
      <div class="left">
        <i @click="switchSidebar" :class="foldClass"></i>
        <bread-crumb />
      </div>
      <div class="right">
        <el-button
          @click="showError(true)"
          v-if="errorLogs.length && openErrorLog"
          type="text"
          class="error_message"
          icon="zly_debug"
        >错误</el-button>
        <!-- mqtt连接 -->
        <span class="mqtt" v-if="hasMqtt">
          <el-popconfirm
            confirmButtonText="好的"
            cancelButtonText="不用了"
            :icon="connected ? 'zly_weilianjie' : 'zly_yilianjie'"
            :iconColor="connected ? '#f56c6c' : '#67c23a'"
            :title="connected ? '确定断开mqtt连接吗' : '确定连接mqtt服务吗'"
            v-if="!connecting"
            @confirm="switchStatus"
          >
            <el-button slot="reference" type="text" :icon="connected ? 'zly_yilianjie' : 'zly_weilianjie'"></el-button>
          </el-popconfirm>
          <i v-else class="el-icon-loading"></i>
        </span>
        <!-- 多主题 -->
        <el-dropdown @command="onThemeCommand" class="local">
          <span class="el-dropdown-link">
            <i class="zly_zhuti"></i>
            {{ themeName }}
          </span>
          <el-dropdown-menu slot="dropdown">
            <template v-for="(value, key) in themes">
              <el-dropdown-item :key="key" :command="value.key" :disabled="theme == value.key">{{ value.title }}</el-dropdown-item>
            </template>
          </el-dropdown-menu>
        </el-dropdown>
        <!-- 多语言 -->
        <span v-if="hasI18n">
          <el-dropdown @command="onCommand" class="local">
            <span class="el-dropdown-link">
              <i class="zly_duoyuyan"></i>
              {{ localName }}
            </span>
            <el-dropdown-menu slot="dropdown">
              <template v-for="(value, key) in locales">
                <el-dropdown-item :key="key" :command="key" :disabled="locale == key">{{ value }}</el-dropdown-item>
              </template>
            </el-dropdown-menu>
          </el-dropdown>
        </span>
        <!-- 头像 -->
        <el-dropdown @command="handleCommand" trigger="click">
          <div class="el-dropdown-link avatar">
            <el-avatar size="medium" shape="square">
              <e-img v-if="member && member.avatar" :id="member.avatar"></e-img>
              <span v-else>{{member.name}}</span>
            </el-avatar>
            <i class="el-icon-caret-bottom"></i>
          </div>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="setting">设置</el-dropdown-item>
            <el-dropdown-item command="resetPwd">修改密码</el-dropdown-item>
            <el-dropdown-item divided command="logout">退出登录</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <tags-view></tags-view>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import breadCrumb from "./breadCrumb";
import tagsView from "./tagsView";
import { mqtt } from "@/mixin";
export default {
  components: {
    breadCrumb,
    tagsView,
  },
  mixins: [
    mqtt
  ],
  computed: {
    ...mapGetters({
      sideShow: 'local/sidebar',
      themes: 'local/themes',
      theme: 'local/theme',
      member: "auth/member",
      errorLogs: "error/logs",
    }),
    foldClass() {
      return this.sideShow ? "el-icon-s-fold" : "el-icon-s-unfold";
    },
    themeName() {
      let name = "";
      let res = this.themes.find(v => {
        return this.theme == v.key
      });
      name = res.title;
      return name;
    },
    hasMqtt() {
      return !!this.$mqtt;
    },
    hasI18n() {
      return !!this.$i18n;
    },
    openErrorLog() {
      return this.$env.NODE_ENV == "dev"
    }
  },
  created() {
    window.tth = this;
  },
  methods: {
    ...mapActions({
      // 侧栏收放
      switchSidebar: "local/changeBarStatus",
      // 主题切换
      onThemeCommand: "local/changeTheme",
      // 打开错误栏
      showError: "error/switchStatus",
      // 设置密码框状态
      resetPwdStatus: "local/resetPwdStatus"
    }),
    // 用户下拉事件
    handleCommand(command) {
      switch (command) {
        case "logout":
          this.$store.dispatch("auth/initStatus", "");
          this.$apis.loginOut()
            .finally(() => {
              this.$store.dispatch("auth/logout");
              this.$router.push({ name: "login" })
            })
          break;
        case "setting":
          let { id } = this.member;
          this.$router.push({ name: "setting.user:id", params: { id } })
          break;
        case "resetPwd":
          this.resetPwdStatus(true);
          // this.$store.dispatch("local/resetPwdStatus", { status: true })
          break;
        default:
          break;
      }
    },
  },
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.navbar {
  height: calc(#{$navbar-height} + #{$tags-view-height});
  position: fixed;
  @include background-color("navbar-bg");
  @include font_color("navbar-color");
  box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
  width: calc(100% - #{$sidebar-width});
  z-index: 1000;
  top: 0;
  transition: width 280ms;

  .bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    width: 100%;
    border-bottom: 1px solid rgba(0, 21, 41, 0.08);
    height: $navbar-height;

    .el-icon-s-fold,
    .el-icon-s-unfold {
      font-size: 23px;
      padding: 0 7px;
    }

    .left,
    .right {
      display: flex;
      align-items: center;
    }

    .right {
      justify-content: right;
    }

    .right > div,
    .right > span,
    .left > div {
      padding: 0 15px;
    }

    .error_message {
      color: #f56c6c;
      padding-left: 15px;
      padding-right: 15px;
    }
  }
}

.hideSidebar .navbar {
  width: calc(100% - #{$sidebar-hide-width});
}

.el-dropdown-link {
  cursor: pointer;
  @include font_color("navbar-color1");
}
.el-icon-caret-bottom {
  font-size: 16px;
}

.el-dropdown-link.avatar {
  font-size: 0;
}

.el-button {
  font-size: 10px;
  ::v-deep .zly_weilianjie {
    color: #f56c6c;
  }

  ::v-deep .zly_yilianjie {
    color: #67c23a;
  }
}

.el-icon-loading {
  color: #409eff;
}
</style>

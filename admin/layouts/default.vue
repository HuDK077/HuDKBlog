<template>
    <div class="main" :class="baseClass" :data-theme="baseTheme">
        <side-bar class="sidebar-container"></side-bar>
        <div class="body">
            <nav-bar></nav-bar>
            <div class="page-body">
                <nuxt :keep-alive="!!$route.meta.keepAlive"/>
            </div>
            <div class="footer">
                <div>Powered by HuDK @v{{ siteConfig.system_version }}</div>
                <a href="http://www.beian.gov.cn/" target="_blank" class="icp_code">{{ siteConfig.website_icp }}</a>
                <!-- <div>{{siteConfig.website_icp}}</div> -->
            </div>
        </div>
        <reset-passwd></reset-passwd>
        <error-logs></error-logs>
        <div v-if="false">
            <message-drawer></message-drawer>
        </div>
        <div
            v-if="$env.NODE_ENV == 'production' && !loadingHide && notSafari"
            class="loading-box"
            :class="loaded ? 'close' : ''">
            <div class="loading" :class="loadingTheme">
                <template v-for="(item,index) in 5">
                    <span :key="index"></span>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {sideBar, navBar, resetPasswd, errorLogs, messageDrawer} from "@/components";
import {mapGetters} from 'vuex'

const loadingThemes = ["line", "ball", "square"];
export default {
    name: "default",
    components: {
        sideBar,
        navBar,
        resetPasswd,
        errorLogs,
        // messageDrawer
    },
    computed: {
        ...mapGetters({
            sideShow: "local/sidebar",
            theme: "local/theme",
            init: "auth/init",
            siteConfig: "local/siteConfig"
        }),
        baseClass() {
            return {hideSidebar: !this.sideShow};
        },
        baseTheme() {
            return this.theme
        },
        loaded() {
            return this.init == "success";
        },
        loadingTheme() {
            return loadingThemes[parseInt(Math.random() * 100) % 3];
        },
        notSafari() {
            return !(/Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent));
        }
    },
    data() {
        return {
            loadingHide: false,
            drawer: false,
            direction: 'rtl',
        }
    },
    mounted() {
        if (this.$env.NODE_ENV == "dev") {
            window.sth = this;
        }
    },
    methods: {
        logout() {
            this.$apis.loginOut()
        },
    },
    watch: {
        loaded(n) {
            setTimeout(() => {
                this.loadingHide = !n;
            }, 1300);
        }
    },
}
</script>

<style lang="scss" scoped>
.main {
    min-height: 100vh;
    display: flex;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    overflow: hidden;
    backface-visibility: hidden;
    visibility: visible;

    > .el-container {
        min-height: 100vh;
    }

    .body {
        width: 100%;
        height: calc(100vh - #{$navbar-height} - #{$tags-view-height});
        transition: margin-left 0.28s;
        margin-left: 210px;
        position: relative;
        overflow: hidden;
        overflow-y: auto;
        padding-top: calc(#{$navbar-height} + #{$tags-view-height});
        @include background_color("body-bg");

        .page-body {
            padding: 15px 10px 10px 15px;
            min-height: calc(100% - 70px);
        }

        .footer {
            text-align: center;
            height: $footer-height;
            width: calc(100% - 20px);
            padding: 0 10px;

            & > div {
                height: 20px;
            }
        }
    }
}

.sidebar-container {
    transition: width 0.28s;
    width: $sidebar-width !important;
    scrollbar-width: none;
    @include background_color("sidebar-bg");
    @include font_color("sidebar-color");
    height: 100%;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    overflow: hidden;
    overflow-y: auto;
    color: #fff;
}

.hideSidebar .sidebar-container {
    width: $sidebar-hide-width !important;
}

.hideSidebar .body {
    margin-left: 64px;
}

.footer {
    @include background_color("sidebar-bg");
    @include font_color("sidebar-color");
}
</style>

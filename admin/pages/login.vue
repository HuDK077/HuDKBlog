<template>
  <div id="login-main" class="page-main">
    <div class="login">
      <div class="logo">
        <e-img class="img" :id="siteConfig.logo_sm" src="/image/logo.png">
          <el-image slot="error" src="/image/logo_sm.png"></el-image>
        </e-img>
      </div>
      <div class="login_title">
        <span>{{ P_name }}</span>
      </div>
      <div class="login_fields">
        <el-form :model="form" :rules="rules" ref="form">
          <el-form-item prop="username">
            <el-input
              class="login-input"
              v-model="form.username"
              placeholder="用户名"
              prefix-icon="zly_icon-test35"
              @keyup.enter.native="submit"
            ></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input
              class="login-input"
              type="password"
              v-model="form.password"
              placeholder="密码"
              :show-password="showPwd"
              prefix-icon="zly_icon-test26"
              @keyup.enter.native="submit"
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-checkbox-group v-model="checkList">
              <el-checkbox :label="1">记住我</el-checkbox>
            </el-checkbox-group>
          </el-form-item>
        </el-form>
        <div class="login_fields__submit">
          <el-button class="submit" type="primary" @click="submit" :loading="loading" plain round>登录</el-button>
        </div>
      </div>
      <div class="disclaimer">
        <p>欢迎登陆后台管理系统</p>
      </div>
    </div>
    <a href="http://www.beian.gov.cn/" target="_blank" class="icp_code">{{ siteConfig.website_icp }}</a>
  </div>
</template>

<script>
import CLOUD from "../utils/cloud";
import { mapGetters } from 'vuex'

export default {
  layout: "login",
  computed: {
    ...mapGetters({
      siteConfig: "local/siteConfig"
    })
  },
  data() {
    return {
      P_name: "管理员登录",
      loading: false,
      showPwd: true,
      checkList: [],
      form: {
        username: "",
        password: ""
      },
      rules: {
        username: [
          { required: true, message: '请输入用户名', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '请输入密码', trigger: 'blur' }
        ],
      }
    };
  },
  created() {
    let ui = localStorage.getItem("ui");
    if (ui) {
      try {
        let userInfo = JSON.parse(ui);
        if (userInfo.c) {
          this.showPwd = false;
        }
        this.form.username = window.atob(userInfo.u);
        this.form.password = window.atob(userInfo.p);
        this.checkList = userInfo.c || [];
      } catch (err) {
        console.log(err);
      }
    }
    this.$store.dispatch("tagsView/delAllViews");
  },
  mounted() {
    if (!(/Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent))) {
      this.cloud = new CLOUD("login-main");
      this.cloud.init();
      this.cloud.startAnimal();
    }
  },
  beforeRouteLeave(to, from, next) {
    if (this.cloud) {
      this.cloud.unLoad();
    }
    this.$destroy();
    this.$store.dispatch("auth/initStatus", "success");
    next();
  },
  methods: {
    // 登录
    submit() {
      let { form, checkList } = this;
      this.$refs["form"].validate(vaild => {
        if (vaild) {
          this.loading = true;
          this.$apis.login({ ...form })
            .then(res => {
              console.log(res);
              let { error_code, message, token } = res.data;
              if (error_code == 2001) {
                if (checkList.length) {
                  let obj = {};
                  obj.u = window.btoa(form.username);
                  obj.p = window.btoa(form.password);
                  obj.c = [1];
                  localStorage.setItem("ui", JSON.stringify(obj))
                } else {
                  localStorage.removeItem("ui");
                }
                this.$qiniu.getQiniuToken()
                this.$store.dispatch("auth/setToken", { token });
                // return this.$apis.getAuthUser()
                this.$updateUser((s, r) => {
                  console.log(s, r);
                  if (s) {
                    this.$notify.success({
                      title: "登录成功",
                      message: "欢迎回来"
                    });
                    setTimeout(() => {
                      this.$router.push({ name: "home" });
                      if (this.cloud) {
                        this.cloud.unLoad();
                      }
                    }, 500);
                  } else {
                    this.$notify.error(r.data.message);
                    return Promise.reject(r);
                  }
                })
              } else {
                console.log("登录失败");
                this.$notify.error(message);
                return Promise.reject();
              }
            })
            .catch(error => {
              console.log(error);
              this.loading = false;
              if (error && error.response && error.response.status == 401) {
                this.$notify.error({
                  title: "登录失败",
                  message: error.response.data.message
                });
              }
            });
        } else {
          this.$notify.warning("请输入用户名和密码");
        }
      })
    },
  }
};
</script>

<style lang="scss" scoped>
.page-main {
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}

.login {
  box-shadow: -15px 15px 15px rgba(6, 17, 47, 0.7);
  opacity: 1;
  top: 20px;
  -webkit-transition-timing-function: cubic-bezier(0.68, -0.25, 0.265, 0.85);
  -webkit-transition-property: -webkit-transform, opacity, box-shadow, top, left;
  transition-property: transform, opacity, box-shadow, top, left;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
  -webkit-transform-origin: 161px 100%;
  -ms-transform-origin: 161px 100%;
  transform-origin: 161px 100%;
  -webkit-transform: rotateX(0deg);
  transform: rotateX(0deg);
  position: relative;
  width: 290px;
  height: 370px;
  position: absolute;
  left: 0;
  right: 0;
  margin: auto;
  top: 0;
  bottom: 0;
  padding: 30px 40px 40px;
  background: #35394a;
  background: -webkit-gradient(linear, left bottom, right top, color-stop(0%, #35394a), color-stop(100%, rgb(0, 0, 0)));
  background: -webkit-linear-gradient(230deg, rgba(53, 57, 74, 0) 0%, rgb(0, 0, 0) 100%);
  background: linear-gradient(230deg, rgba(53, 57, 74, 0) 0%, rgb(0, 0, 0) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(53, 57, 74, 0)', endColorstr='rgb(0, 0, 0)', GradientType=1);

  .logo {
    text-align: center;
    .img {
      width: 50px;
    }
  }

  .login_title {
    color: #d3d7f7;
    height: 60px;
    font-size: 23px;
    text-align: center;
  }

  .login_fields__submit {
    display: flex;
    justify-content: center;
    // padding-top: 30px;
    .submit {
      background: unset;
      width: 10em;
      &:hover {
        background: #409eff;
      }
    }
  }

  .disclaimer {
    position: absolute;
    bottom: 20px;
    left: 35px;
    width: 300px;
    p {
      text-align: center;
      font-size: 13px;
      color: #d3d7f7;
    }
  }
}

.icp_code {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  color: #000 !important;
}
</style>

<style>
#login-main {
  background-image: url("/image/could_bg.png");
  background-size: 100% 100%;
  background-repeat: no-repeat;
}

.login-input .el-input__inner {
  background: unset !important;
  border: none !important;
  color: #61bfff !important;
  padding-left: 40px;
}

.login-input .el-input__prefix {
  font-size: 20px;
}
</style>
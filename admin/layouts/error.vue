<template>
  <div class="container">
    <div class="wscn-http-err">
      <div class="pic-err">
        <img v-if="error.statusCode === 404" class="pic-err__parent" src="@/assets/image/error/404.png" alt="404" />
        <img v-else-if="error.statusCode === 403" class="pic-err__parent" src="@/assets/image/error/403.png" alt="404" />
        <img v-else class="pic-err__parent" src="@/assets/image/error/500.png" alt="404" />
        <img class="pic-err__child left" src="@/assets/image/error/404_cloud.png" alt="404" />
        <img class="pic-err__child mid" src="@/assets/image/error/404_cloud.png" alt="404" />
        <img class="pic-err__child right" src="@/assets/image/error/404_cloud.png" alt="404" />
      </div>
      <div class="bullshit">
        <div class="bullshit__oops">OOPS!</div>
        <div class="bullshit__headline" v-if="error.statusCode === 404">特朗普说没有这个页面......</div>
        <div class="bullshit__headline" v-else>{{error.message}}</div>
        <div class="bullshit__info">请检查您输入的网址是否正确，请点击以下按钮返回主页</div>
        <nuxt-link to="/" class="bullshit__return-home">返回首页</nuxt-link>
        <div class="bullshit__return-home" @click="back">返回上一页</div>
      </div>
    </div>
    <!-- <div v-else-if="error.statusCode === 403"></div>
    <div v-else>{{error.statusCode}}</div>-->
  </div>
</template>

<script>
export default {
  layout: 'login', // 你可以为错误页面指定自定义的布局
  props: ['error'],
  created() {
    console.log(this.error);
  },
  methods: {
    back() {
      this.$router.back();
    }
  },
  head() {
    return {
      title: this.error.statusCode
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  background-color: rgb(240, 242, 245);
  height: 100vh;
  width: 100vw;
  overflow: hidden;
}

.wscn-http-err {
  position: relative;
  width: 1200px;
  margin: 100px auto 60px;
  padding: 0 100px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  .pic-err {
    position: relative;
    float: left;
    width: 600px;
    padding: 150px 0;
    overflow: hidden;
    &__parent {
      width: 100%;
    }
    &__child {
      position: absolute;
      &.left {
        width: 80px;
        top: 17px;
        left: 220px;
        opacity: 0;
        animation-name: cloudLeft;
        animation-duration: 2s;
        animation-timing-function: linear;
        animation-fill-mode: forwards;
        animation-delay: 1s;
      }
      &.mid {
        width: 46px;
        top: 10px;
        left: 420px;
        opacity: 0;
        animation-name: cloudMid;
        animation-duration: 2s;
        animation-timing-function: linear;
        animation-fill-mode: forwards;
        animation-delay: 1.2s;
      }
      &.right {
        width: 62px;
        top: 100px;
        left: 500px;
        opacity: 0;
        animation-name: cloudRight;
        animation-duration: 2s;
        animation-timing-function: linear;
        animation-fill-mode: forwards;
        animation-delay: 1s;
      }
      @keyframes cloudLeft {
        0% {
          top: 17px;
          left: 220px;
          opacity: 0;
        }
        20% {
          top: 33px;
          left: 188px;
          opacity: 1;
        }
        80% {
          top: 81px;
          left: 92px;
          opacity: 1;
        }
        100% {
          top: 97px;
          left: 60px;
          opacity: 0;
        }
      }
      @keyframes cloudMid {
        0% {
          top: 10px;
          left: 420px;
          opacity: 0;
        }
        20% {
          top: 40px;
          left: 360px;
          opacity: 1;
        }
        70% {
          top: 130px;
          left: 180px;
          opacity: 1;
        }
        100% {
          top: 160px;
          left: 120px;
          opacity: 0;
        }
      }
      @keyframes cloudRight {
        0% {
          top: 100px;
          left: 500px;
          opacity: 0;
        }
        20% {
          top: 120px;
          left: 460px;
          opacity: 1;
        }
        80% {
          top: 180px;
          left: 340px;
          opacity: 1;
        }
        100% {
          top: 200px;
          left: 300px;
          opacity: 0;
        }
      }
    }
  }
  .bullshit {
    position: relative;
    float: left;
    width: 300px;
    padding: 150px 0;
    overflow: hidden;
    &__oops {
      font-size: 32px;
      font-weight: bold;
      line-height: 40px;
      color: #1482f0;
      opacity: 0;
      margin-bottom: 20px;
      animation-name: slideUp;
      animation-duration: 0.5s;
      animation-fill-mode: forwards;
    }
    &__headline {
      font-size: 20px;
      line-height: 24px;
      color: #1482f0;
      opacity: 0;
      margin-bottom: 10px;
      animation-name: slideUp;
      animation-duration: 0.5s;
      animation-delay: 0.1s;
      animation-fill-mode: forwards;
    }
    &__info {
      font-size: 13px;
      line-height: 21px;
      color: grey;
      opacity: 0;
      margin-bottom: 30px;
      animation-name: slideUp;
      animation-duration: 0.5s;
      animation-delay: 0.2s;
      animation-fill-mode: forwards;
    }
    &__return-home {
      display: block;
      float: left;
      width: 110px;
      height: 36px;
      background: #1482f0;
      border-radius: 100px;
      text-align: center;
      color: #ffffff;
      opacity: 0;
      font-size: 14px;
      line-height: 36px;
      cursor: pointer;
      animation-name: slideUp;
      animation-duration: 0.5s;
      animation-delay: 0.3s;
      animation-fill-mode: forwards;
      text-decoration: unset;
    }

    @keyframes slideUp {
      0% {
        transform: translateY(60px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }
  }
  .bullshit__return-home + .bullshit__return-home {
    margin-left: 10px;
  }
}
</style>
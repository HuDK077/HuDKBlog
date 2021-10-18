<template>
    <div class="hello">
        <h1>点击{{ state.count }}</h1>
        <el-button @click="increment()">点一下</el-button>
    </div>
</template>

<script setup lang="ts">
import { getCurrentInstance } from "vue"
const axios = getCurrentInstance()?.appContext.config.globalProperties.$axios
axios.request({
    url: '/user/login',
    method: 'post'
})
</script>

<style scoped lang="scss">
h3 {
    margin: 40px 0 0;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: inline-block;
    margin: 0 10px;
}

a {
    color: #42b983;
}
</style>

<template>
    <div>
    </div>
</template>
<script setup lang="ts">
import {apis} from '@/plugins/apis'
import user from "@/store/modules/User";
import SocketService from "@/plugins/websocket";
import {reactive} from "vue";

const data = reactive({
    socketServer: SocketService.Instance
});
SocketService.connect()
SocketService.send('给我发消息干什么');

// console.log(SocketService.onMessage())

// import { io } from "socket.io-client";
// const socket = io("ws://blog.cc:5500");
// console.log(socket)
// socket.on("connection", (socket) => {
//     console.log(socket.id);
// });
//
// socket.on("disconnect", () => {
//     console.log(socket.id); // undefined
// });
// console.log(socket)


// let data: object = {} = {
//     openid: 'HuDK'
// }
// apis('login', data).then(res => {
//     console.log(res)
// });
// user.dispatch('change', "asdasd")import { io } from "socket.io-client";

</script>


<style scoped lang="scss">
</style>

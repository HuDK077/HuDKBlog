<template>
    <el-container>
        <!-- 顶栏 -->
        <el-header>
            <h2>ohh</h2>

        </el-header>
        <!-- 嵌套容器 -->
        <el-container>
            <!-- 内容 -->
            <el-main>
                <!-- 第一列布局 -->
                <el-row>
                    <el-col :span="24" class="col1"></el-col>
                </el-row>
                <!-- 第二列栅格布局 -->
                <el-row>
                    <el-col :span="8" class="col2"></el-col>
                    <el-col :span="8" class="col3"></el-col>
                    <el-col :span="8" class="col4"></el-col>
                </el-row>
                <!-- 第三列布局 -->
                <el-row>
                    <el-col :span="24" class="col5"></el-col>
                </el-row>
            </el-main>
        </el-container>
        <!-- 底栏 -->
        <el-footer>2021</el-footer>
    </el-container>
</template>

<script>





// import {Options, Vue} from 'vue-class-component';
// import HelloWorld from '@/views/HelloWorld.vue'; // @ is an alias to /src
//
// @Options({
//     components: {
//         HelloWorld,
//     },
// })
</script>
<style lang="scss" scoped>
.el-header {
    position: absolute;
    height: 60px;
    background-color: #000000;
    color: white;
    top: 0;
    right: 0;
    left: 0;
}

.el-main {
    position: absolute;
    top: 60px;
    bottom: 30px;
    right: 0;
    left: 0;
    background: #000000;
    overflow-y: auto;
}

.el-footer {
    position: absolute;
    height: 30px;
    bottom: 0px;
    right: 0;
    left: 0;
    background: #545454;
}

.el-main::-webkit-scrollbar { /*滚动条整体样式*/
    width: 4px; /*高宽分别对应横竖滚动条的尺寸*/
    height: 4px;
    scrollbar-arrow-color: red;
}

.el-main::-webkit-scrollbar-thumb { /*滚动条里面小方块*/
    border-radius: 5px;
    -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    background: rgba(0, 0, 0, 0.2);
    scrollbar-arrow-color: red;
}

.el-main::-webkit-scrollbar-track { /*滚动条里面轨道*/
    -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    border-radius: 0;
    background: rgb(255, 255, 255);
}

.el-aside {
    background-color: chartreuse;
}


.el-col {
    height: 300px;
}

.col1 {
    background-color: teal;
}

.col2 {
    background-color: tomato;
}

.col3 {
    background-color: #2567b6;
}

.col4 {
    background-color: #19cccc;
}

.col5 {
    background-color: #790a79;
}
</style>

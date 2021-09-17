<template>
  <div>
    <div class="top-bar">
      <el-input placeholder="搜索用户登录名/真实姓名" v-model="search" class="input-with-select" @keyup.enter.native="loadData">
        <el-button slot="append" type="primary" icon="el-icon-search" @click="loadData">搜索</el-button>
      </el-input>
      <el-button type="primary" icon="el-icon-plus" @click="addNewUser">新增管理员(用户)</el-button>
    </div>
    <el-table v-loading="loading" :data="users" stripe style="width: 100%">
      <el-table-column prop="id" label="编号" width="50"></el-table-column>
      <el-table-column prop="username" label="登录名"></el-table-column>
      <el-table-column prop="name" label="真实姓名"></el-table-column>
      <el-table-column prop="role" label="角色">
        <template slot-scope="scope">{{scope.row.role_name + " ( "+scope.row.slug+" )"}}</template>
      </el-table-column>
      <el-table-column fixed="right" label="操作" width="200">
        <template slot-scope="scope">
          <el-button @click="editUser(scope.row)" type="text" size="small">编辑</el-button>
          <!--<el-button @click="updateMenuRole(scope.row)" type="text" size="small">分配页面权限</el-button>-->
          <el-button
            v-if="scope.row.username != 'admin'"
            class="table-delete"
            type="text"
            size="small"
            @click="deleteUser(scope.row)"
          >删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      users: [],
      loading: false
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    // 数据加载
    loadData() {
      let { search } = this;
      this.loading = true;
      this.$apis.getAllUser({ search })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.users = data.data;
          }
        }).finally(() => {
          this.loading = false;
        });
    },
    // 新增用户
    addNewUser() {
      this.$router.push({ name: "setting.user:id", params: { id: "add" } })
    },
    // 编辑用户
    editUser(e) {
      console.log(e);
      this.$router.push({ name: "setting.user:id", params: { id: e.id } })
    },
    // 删除用户
    deleteUser(e) {
      console.log(e);
      let str = `确认删除用户 ${e.name} 吗?`
      this.$confirm(str, "确认删除", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        this.$apis.delUser({ id: e.id })
          .then(res => {
            console.log(res.data);
            let { error_code, message } = res.data;
            if (error_code == 2001) {
              this.loadData();
            } else {
              this.$message.error(message);
            }
          });
      })
    }
  },
}
</script>

<style scoped>
.top-bar {
  padding: 10px 0;
}

.input-with-select {
  width: 30%;
}

.el-input-group__append button.el-button {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  border: 1px solid #409eff;
  color: #fff;
  background-color: #409eff;
}
</style>
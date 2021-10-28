<template>
  <div>
    <div class="top-bar">
      <el-input
        placeholder="搜索用户登录名/真实姓名"
        v-model="search"
        class="input-with-select"
        @keyup.enter.native="loadData"
        :disabled="!canGetUser"
      >
        <e-btn slot="append" tag="setting.user@get" type="primary" icon="el-icon-search" @click="loadData">搜索</e-btn>
      </el-input>
      <e-btn tag="setting.user@add" type="primary" icon="el-icon-plus" @click="addNewUser">新增用户</e-btn>
    </div>
    <el-table v-loading="loading" :data="list" stripe style="width: 100%">
      <el-table-column prop="id" label="编号" width="50"></el-table-column>
      <el-table-column prop="username" label="登录名"></el-table-column>
      <el-table-column prop="name" label="用户名"></el-table-column>
      <el-table-column fixed="right" label="操作" width="200">
        <template slot-scope="scope">
          <e-btn tag="setting.user@update" @click="editUser(scope.row)" type="text" size="small">编辑</e-btn>
          <!--<el-button @click="updateMenuRole(scope.row)" type="text" size="small">分配页面权限</el-button>-->
          <e-btn
            v-if="scope.row.username != 'admin'"
            tag="setting.user@delete"
            class="table-delete"
            type="text"
            size="small"
            @click="deleteUser(scope.row)"
          >
            删除
          </e-btn>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination">
      <el-pagination
        :disabled="!canGetUser"
        @size-change="loadData"
        @current-change="loadData"
        :current-page.sync="page"
        :page-sizes="[10, 20, 50, 100]"
        :page-size.sync="limit"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
      ></el-pagination>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      limit: 10,
      page: 1,
      total: 0,
      search: "",
      list: [],
      loading: false
    }
  },
  created() {
    this.loadData()
  },
  computed: {
    ...mapGetters({
      widgets: "permission/widgets",
    }),
    canGetUser() {
      return this.widgets.includes("setting.user@get")
    }
  },
  methods: {
    // 数据加载
    loadData() {
      let { limit, page, search } = this;
      this.loading = true;
      this.$apis.getAllUser({ limit, page, search })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.list = data.data;
            this.total = data.total;
          }
        })
        .finally(() => {
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
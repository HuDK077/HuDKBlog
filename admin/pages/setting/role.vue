<template>
  <div v-loading="loading">
    <div class="top-bar">
      <e-btn tag="setting.rule@add" type="primary" icon="el-icon-plus" @click="addNewRole">新增角色</e-btn>
    </div>

    <el-table :data="roles" stripe style="width: 100%">
      <el-table-column prop="id" label="编号" width="80"></el-table-column>
      <el-table-column prop="slug" label="标识"></el-table-column>
      <el-table-column prop="name" label="角色名称"></el-table-column>
      <el-table-column prop="created_at" label="创建日期"></el-table-column>
      <el-table-column prop="updated_at" label="更新日期"></el-table-column>
      <el-table-column fixed="right" label="操作" width="100">
        <template slot-scope="scope">
          <e-btn tag="setting.rule@update" @click="editRole(scope.row)" type="text" size="small">编辑</e-btn>
          <e-btn tag="setting.role@delete" @click="deleteRole(scope.row)" class="table-delete" type="text" size="small">删除</e-btn>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      width="50%"
      :title="updateMode ? '更新角色' : '新增角色'"
      :visible.sync="showEditDialog"
      :close-on-click-modal="false"
      @closed="editDialogClosed"
    >
      <div>
        <el-form :model="form" :rules="rules" ref="form" label-width="10em" label-position="top">
          <el-form-item label="标识" prop="slug">
            <el-input @keyup.enter.native="submitRole" v-model="form.slug"></el-input>
          </el-form-item>
          <el-form-item label="角色名称" prop="name">
            <el-input @keyup.enter.native="submitRole" v-model="form.name"></el-input>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <e-btn :loading="onRoleEdit" @click="cancel">取 消</e-btn>
        <e-btn :tag="['setting.rule@add', 'setting.rule@update']" :loading="onRoleEdit" type="primary" @click="submitRole">确 定</e-btn>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  // TODO 角色名搜索
  data() {
    let vaildeSlug = (r, v, cb) => {
      if (v === "") {
        cb(new Error("请输入角色名"));
      } else if (!/^[a-zA-Z]+$/.test(v)) {
        cb(new Error("只能为英文单词，建议首字母大写"));
      } else {
        cb();
      }
    };

    return {
      roles: [],
      loading: true,
      showEditDialog: false,
      updateMode: false,
      onRoleEdit: false,
      form: {
        slug: "",
        name: ""
      },
      rules: {
        name: [
          { required: true, message: "请输入角色展示名", trigger: "blur" },
          { max: 10, message: "长度在 10 个字符内", trigger: "blur" }
        ],
        slug: [
          { required: true, message: "请输入角色名", trigger: "blur" },
          { validator: vaildeSlug, trigger: ["blur", "change"] }
        ]
      }
    };
  },
  created() {
    this.loadData();
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      this.$apis.getRole()
        .then(res => {
          console.log(res.data);
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.roles = data;
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 编辑用户
    editRole(e) {
      console.log(e);
      this.updateMode = true;
      this.form = { slug: e.slug, name: e.name, id: e.id };
      this.showEditDialog = true;
    },
    // 删除用户
    deleteRole(e) {
      console.log(e);
      let str = `确认删除${e.name} ( ${e.slug} ) ?`;
      this.$confirm(str, "删除", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          return this.$apis.delRole({ id: e.id });
        })
        .then(res => {
          console.log(res.data);
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("删除成功");
            this.loadData();
          } else {
            this.$message.error(message);
          }
        })
        .catch(e => {
          console.log(e);
        });
    },
    // 新增用户
    addNewRole() {
      this.updateMode = false;
      this.showEditDialog = true;
    },
    // 提交
    submitRole() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          this.onRoleEdit = true;
          if (this.updateMode) {
            this.updateRole();
          } else {
            this.addRole();
          }
        }
      });
    },
    // 取消提交
    cancel() {
      this.showEditDialog = false;
    },
    // 重置form
    editDialogClosed() {
      this.$refs["form"].resetFields();
      this.form = { slug: "", name: "" };
    },
    // 新增角色
    addRole() {
      let { name, slug } = this.form;
      this.$apis.addRole({ name, slug })
        .then(res => {
          console.log(res.data);
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.showEditDialog = false;
            this.loadData();
            this.$message.success("新增成功");
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.onRoleEdit = false;
        });
    },
    // 更新角色
    updateRole() {
      let { name, slug, id } = this.form;
      this.$apis.updateRole({ name, slug, id })
        .then(res => {
          console.log(res.data);
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.showEditDialog = false;
            this.loadData();
            this.$message.success("更新成功");
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.onRoleEdit = false;
        });
    },

    // 整页liading
    showLoading(text, time) {
      this.loadinger = this.$loading({
        lock: true,
        text,
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)"
      });

      if (time) {
        setTimeout(() => {
          this.loadinger.close();
        }, time);
      }
    },
  }
};
</script>

<style lang="scss" scoped>
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

.menu-tree-box {
  max-height: 50vh;
  overflow: hidden;
  overflow-y: auto;
}

.permission-box {
  display: flex;
  justify-content: center;
  max-height: 50vh;
  overflow: hidden;
  overflow-y: auto;
  padding-bottom: 20px;
}
</style>

<style>
.permission-box .el-transfer-panel {
  width: 280px;
}
</style>
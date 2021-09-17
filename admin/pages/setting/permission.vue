<template>
  <div>
    <div class="top-bar">
      <el-button type="primary" icon="el-icon-plus" @click="addNewPermissions">新增权限</el-button>
    </div>
    <el-table v-loading="loading" :data="permissions" stripe style="width: 100%">
      <el-table-column prop="id" label="编号"></el-table-column>
      <el-table-column prop="name" label="权限名称"></el-table-column>
      <el-table-column prop="slug" label="权限标识"></el-table-column>
      <el-table-column fixed="right" label="操作" width="100">
        <template slot-scope="scope">
          <el-button v-if="scope.row.id != 1" @click="editPermission(scope.row)" type="text" size="small">编辑</el-button>
          <el-button
            v-if="scope.row.id != 1"
            class="table-delete"
            type="text"
            size="small"
            @click="deletePermission(scope.row)"
          >删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      center
      width="50%"
      title="编辑权限"
      @closed="handleClose"
      :visible.sync="showEditDialog"
      :close-on-click-modal="false"
    >
      <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="7em" label-position="top">
          <el-form-item label="权限名称" prop="name">
            <el-input v-model="form.name" @keyup.enter.native="editSubmit"></el-input>
          </el-form-item>
          <el-form-item label="权限标识" prop="slug">
            <el-input v-model="form.slug" @keyup.enter.native="editSubmit"></el-input>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :loading="onEdit" @click="editCancel">取 消</el-button>
        <el-button :loading="onEdit" type="primary" @click="editSubmit">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      permissions: [],
      loading: false,
      showEditDialog: false,
      onEdit: false,
      editMode: false,
      form: {
        name: "",
        slug: "",
      },
      rules: {
        name: [
          { required: true, message: '请输入权限名称', trigger: 'blur' },
          { max: 10, message: '长度在 10 个字符内', trigger: 'blur' }
        ],
        slug: [
          { required: true, message: '请输入权限标识', trigger: 'blur' }
        ],
      }
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true
      this.$apis.getPermission()
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.permissions = data;
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 新增权限
    addNewPermissions() {
      this.editMode = false;
      this.showEditDialog = true;
    },
    // 编辑权限
    editPermission(e) {
      this.editMode = true;
      let { id, name, slug } = e;
      this.form.id = id;
      this.form.name = name;
      this.form.slug = slug;
      this.showEditDialog = true;
    },
    // 删除权限
    deletePermission(e) {
      console.log(e);
      let str = `确认删除权限 ${e.name} ( ${e.slug} ) 吗?`
      this.$confirm(str, "删除权限", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        this.$apis.delPermission({ id: e.id })
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
    },
    // dialog关闭恢复页面
    handleClose() {
      this.$refs["form"].resetFields();
      this.form = { name: "", slug: "", }
    },
    // 关闭修改弹窗
    editCancel() {
      this.showEditDialog = false;
    },
    // 保存编辑
    editSubmit() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          if (this.editMode) {
            this.editPermissions();
          } else {
            this.addPermissions();
          }
        }
      })
    },
    // 编辑权限
    editPermissions() {
      let { name, slug, id } = this.form
      this.$apis.updatePermission({ id, name, slug })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.loadData()
            this.showEditDialog = false;
          } else {
            this.$message.error(message);
          }
        });
    },
    // 新增权限
    addPermissions() {
      let { name, slug } = this.form
      this.$apis.addPermission({ name, slug })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.loadData()
            this.showEditDialog = false;
          } else {
            this.$message.error(message);
          }
        });
    },
  },
}
</script>

<style lang="scss" scoped>
.top-bar {
  padding: 10px 0;
}
</style>
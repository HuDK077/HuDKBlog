<template>
  <div v-loading="loading">
    <div class="top-bar">
      <el-button type="primary" icon="el-icon-plus" @click="addNewRole">新增角色</el-button>
    </div>

    <el-table :data="roles" stripe style="width: 100%">
      <el-table-column prop="id" label="ID" width="80"></el-table-column>
      <el-table-column prop="slug" label="标识"></el-table-column>
      <el-table-column prop="name" label="角色名称"></el-table-column>
      <el-table-column prop="created_at" label="创建日期"></el-table-column>
      <el-table-column prop="updated_at" label="更新日期"></el-table-column>
      <el-table-column fixed="right" label="操作" width="300">
        <template slot-scope="scope">
          <el-button @click="editRole(scope.row)" type="text" size="small">编辑</el-button>
          <el-button @click="updateMenuRole(scope.row)" type="text" size="small">分配页面权限</el-button>
          <el-button @click="updatePermissionsRole(scope.row)" type="text" size="small">分配接口权限</el-button>
          <el-button @click="deleteRole(scope.row)" class="table-delete" type="text" size="small">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog width="50%" :title="updateMode? '更新角色' : '新增角色' " :visible.sync="showEditDialog" :close-on-click-modal="false" @closed="editDialogClosed">
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
        <el-button :loading="onRoleEdit" @click="cancel">取 消</el-button>
        <el-button :loading="onRoleEdit" type="primary" @click="submitRole">确 定</el-button>
      </span>
    </el-dialog>

    <el-dialog width="50%" title="更新角色页面权限" :visible.sync="showRoleMenu" :close-on-click-modal="false" @closed="roleMenuDialogClosed">
      <div class="menu-tree-box">
        <el-tree ref="menuTree" :data="menus" show-checkbox node-key="id" :props="defaultProps" :default-expand-all="true" :default-checked-keys="roleMenu"></el-tree>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="roleMenuCancel">取 消</el-button>
        <el-button type="primary" @click="submitRoleMenu">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog width="900px" title="更新角色接口权限" :visible.sync="showRolePermission" :close-on-click-modal="false" @closed="rolePermissionDialogClosed" destroy-on-close>
      <div class="permission-box">
        <el-transfer :titles="['未获得的权限', '已获得的权限']" :button-texts="['收回', '分配']" :props="perProps" v-model="rolePermission" :data="permissions" filterable :filter-method="filterMethod">
          <template slot-scope="{option}">
            <span>{{option.name +"( "+option.slug+" )"}}</span>
          </template>
        </el-transfer>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="rolePermissionCancel">取 消</el-button>
        <el-button type="primary" @click="submitRolePermission">确 定</el-button>
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
        cb(new Error("请输入角色名"))
      } else if (!/^[a-zA-Z]+$/.test(v)) {
        cb(new Error("只能为英文单词，建议首字母大写"))
      } else {
        cb()
      }
    };

    return {
      roles: [],
      loading: true,
      showEditDialog: false,
      updateMode: false,
      onRoleEdit: false,
      menus: [],
      roleMenu: [],
      showRoleMenu: false,
      permissions: [],
      rolePermission: [],
      showRolePermission: false,
      perProps: { key: "id", label: "name" },
      defaultProps: { label: 'title' },
      form: {
        slug: "",
        name: "",
      },
      rules: {
        name: [
          { required: true, message: '请输入角色展示名', trigger: 'blur' },
          { max: 10, message: '长度在 10 个字符内', trigger: 'blur' }
        ],
        slug: [
          { required: true, message: '请输入角色名', trigger: 'blur' },
          { validator: vaildeSlug, trigger: ["blur", "change"] }
        ],
      }
    }
  },
  created() {
    this.loadMenus();
    this.loadPermissions();
    this.loadData();
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      this.$apis.getRole()
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.roles = data;
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 获取菜单列表
    loadMenus() {
      this.$apis.getAllMenu()
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            let menus = this.list2Tree(data, 0);
            this.menus = menus;
          }
        });
    },
    // 获取权限列表
    loadPermissions() {
      this.$apis.getPermission()
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.permissions = data;
          }
        });
    },
    // 编辑用户
    editRole(e) {
      console.log(e);
      this.updateMode = true;
      this.form = { slug: e.slug, name: e.name, id: e.id }
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
        type: "warning",
      })
        .then(() => {
          return this.$apis.delRole({ id: e.id })
        }).then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("删除成功");
            this.loadData();
          } else {
            this.$message.error(message);
          }
        }).catch(e => {
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
      })
    },
    // 取消提交
    cancel() {
      this.showEditDialog = false;
    },
    // 重置form
    editDialogClosed() {
      this.$refs["form"].resetFields();
      this.form = { slug: "", name: "", };
    },
    // 新增角色
    addRole() {
      let { name, slug } = this.form;
      this.$apis.addRole({ name, slug })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.showEditDialog = false
            this.loadData();
            this.$message.success("新增成功");
          } else {
            this.$message.error(message);
          }
        }).finally(() => {
          this.onRoleEdit = false;
        });
    },
    // 更新角色
    updateRole() {
      let { name, slug, id } = this.form;
      this.$apis.updateRole({ name, slug, id })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.showEditDialog = false
            this.loadData();
            this.$message.success("更新成功");
          } else {
            this.$message.error(message);
          }
        }).finally(() => {
          this.onRoleEdit = false;
        });
    },
    // 列表转树递归
    list2Tree(list, id) {
      let arr = []
      list.forEach(v => {
        if (v.parent_id == id) {
          if (v.slug == "setting.menu") {
            v.disabled = true;
          }
          let obj = v;
          obj.children = this.list2Tree(list, v.id || v.menu_id)
          arr.push(JSON.parse(JSON.stringify(obj)))
        }
      });
      return arr;
    },
    // 更新角色页面权限
    updateMenuRole(e) {
      this.showLoading("加载中...");
      let { menus } = this;
      let roleMenu = [];
      this.$apis.getRM({ id: e.id })
        .then(res => {
          // console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            let tempMenu = this.list2Tree(data, 0);
            // console.log(tempMenu);
            tempMenu.forEach(v => {
              let res = menus.find(f => { return f.id == v.menu_id })
              if (v.children && v.children.length) {
                v.children.forEach(child => {
                  roleMenu.push(child.menu_id);
                })
                if (res && res.children && res.children.length == v.children.length) {
                  roleMenu.push(v.menu_id);
                }
              } else {
                roleMenu.push(v.menu_id)
              }
            })
            this.roleMenu = roleMenu;
            if (this.$refs["menuTree"]) {
              this.$refs["menuTree"].setCheckedKeys(roleMenu);
            }
            this.showRoleMenu = true;
            this.roleTempId = e.id;
            this.loadinger.close();

          } else {
            this.$message.error(message);
          }
        }).finally(() => {
          this.loadinger.close();
        });
    },
    // 取消菜单权限编辑
    roleMenuCancel() {
      this.showRoleMenu = false;
    },
    // 提交菜单权限编辑
    submitRoleMenu() {
      let role_id = this.roleTempId;
      let nodes = this.$refs["menuTree"].getCheckedNodes();
      // console.log(JSON.parse(JSON.stringify(nodes)));
      let menu_ids = [];
      nodes.forEach(v => { menu_ids.push(v.id); })
      nodes.forEach(v => {
        if (!menu_ids.includes(v.parent_id) && v.parent_id != 0) {
          menu_ids.push(v.parent_id);
        }
      })

      this.$apis.updateRM({ role_id, menu_ids })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            if (role_id == this.$store.getters["auth/role"].role_id) {
              this.$updateUser();
            }
            this.$message.success("更新成功");
            this.showRoleMenu = false;
          } else {
            this.$message.error(message);
          }
        });

    },
    // 分配菜单权限关闭
    roleMenuDialogClosed() {
      this.roleTempId = "";
      this.roleMenu = [];
    },
    // 整页liading
    showLoading(text, time) {
      this.loadinger = this.$loading({
        lock: true,
        text,
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });

      if (time) {
        setTimeout(() => {
          this.loadinger.close();
        }, time);
      }
    },
    // 分配接口权限
    updatePermissionsRole(e) {
      // console.log(e);
      this.showLoading("加载中...");
      this.$apis.getRP({ id: e.id })
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            let rolePermission = [];
            data.forEach(v => {
              rolePermission.push(v.permission_id);
              this.rolePermission = rolePermission;
            })
            this.showRolePermission = true;
            this.roleTempId = e.id;
            this.loadinger.close();
          } else {
            this.$message.error(message);
          }
        }).finally(() => {
          this.loadinger.close();
        });
    },
    // 接口分配窗口关闭
    rolePermissionDialogClosed() {
      this.rolePermission = [];
    },
    // 关闭权限分配接口
    rolePermissionCancel() {
      this.showRolePermission = false;
    },
    // 更新权限分配
    submitRolePermission() {
      let role_id = this.roleTempId;
      let permission_ids = this.rolePermission;
      this.$apis.updateRP({ role_id, permission_ids })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("权限修改成功");
            this.showRolePermission = false;
          } else {
            this.$message.error(message);
          }
        });
    },
    // 筛选
    filterMethod(query, item) {
      return item.name.indexOf(query) > -1 || item.slug.indexOf(query) > -1;
    }
  },
}
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
  max-height: 60vh;
  overflow: hidden;
  overflow-y: auto;
  padding-bottom: 20px;
  ::v-deep .el-transfer-panel .el-transfer-panel__body {
    height: 340px;
    .el-input {
      width: calc(100% - 30px);
    }
    .el-transfer-panel__list.is-filterable {
      height: 280px;
    }
  }
}
</style>

<style>
.permission-box .el-transfer-panel {
  width: 280px;
}
</style>

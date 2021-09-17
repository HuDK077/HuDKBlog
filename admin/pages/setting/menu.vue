<template>
  <div class="page-main serring-menu" v-loading="loading">
    <div class="top-bar">
      <el-button type="primary" icon="el-icon-plus" @click="addNewMenu">新增主菜单</el-button>
    </div>

    <el-tree draggable ref="tree" :data="menus" node-key="id" :props="treeProps" :allow-drop="allowDrop" :allow-drag="allowDrog" @node-drop="dropDone" :expand-on-click-node="true">
      <span class="custom-tree-node" slot-scope="{ node, data }">
        <span :class="[data.parent_id == 0 ? 'parent' : '',data.id == -1 ? 'need-edit' : '']">{{ data.title }}</span>
        <span style="padding-left: 10px">
          <el-button v-if="data.parent_id == 0 && data.id != -1" type="text" @click.stop="append(data)">
            <i class="el-icon-plus"></i>
          </el-button>
          <el-button type="text" @click.stop="edit(node, data)">
            <i class="el-icon-edit"></i>
          </el-button>
          <el-button type="text" @click.stop="remove(node, data)">
            <i class="el-icon-delete"></i>
          </el-button>
        </span>
      </span>
    </el-tree>

    <el-dialog title="编辑页面" @closed="handleClose" :visible.sync="showEditDialog" center width="50%" :close-on-click-modal="false">
      <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="7em" label-position="top">
          <el-form-item label="页面名称" prop="title">
            <el-input v-model="form.title" @keyup.enter.native="editSubmit"></el-input>
          </el-form-item>
          <el-form-item label="页面标识" prop="slug">
            <el-input v-model="form.slug" @keyup.enter.native="editSubmit"></el-input>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :loading="onMenuEdit" @click="editCancel">取 消</el-button>
        <el-button :loading="onMenuEdit" type="primary" @click="editSubmit">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      menus: [],
      onMenuEdit: false,
      showEditDialog: false,
      loading: false,
      treeProps: { label: "title" },
      form: {
        title: "",
        slug: "",
      },
      rules: {
        title: [
          { required: true, message: '请输入菜单名称', trigger: 'blur' },
          { max: 10, message: '长度在 10 个字符内', trigger: 'blur' }
        ],
        slug: [
          { required: true, message: '请输入菜单标识', trigger: 'blur' }
        ],
      }
    };
  },
  created() {
    this.loadData()
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      this.$apis.getAllMenu()
        .then((res) => {
          console.log(res.data);
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            if (data.length) {
              let menus = this.list2Tree(data, 0);
              this.menus = menus;
            }
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 新增
    append(data) {
      if (!data.children) {
        this.$set(data, "children", []);
      }
      if (!data.children.length || data.children[data.children.length - 1].id != -1) {
        data.children.push({ title: "新增菜单", parent_id: data.id, id: -1 });
      } else {
        this.$message.info("请编辑现有的菜单");
      }
    },
    // 删除
    remove(node, data) {
      if (data.title == "新增菜单") {
        const parent = node.parent;
        const children = parent.data.children || parent.data;
        const index = children.findIndex(d => d.id === data.id);
        children.splice(index, 1);
        return
      }

      let childName = "";
      if (data.children && data.children.length) {
        data.children.forEach((v, i) => {
          childName += v.title;
          if (i != data.children.length - 1) {
            childName += " , "
          }
        })
      }
      let str = `是否删除菜单: ${data.title}${childName ? " 以及子菜单: " + childName : ""}`;
      this.$confirm(str, '确认删除', {
        confirmButtonText: '删除',
        confirmButtonClass: "el-button--danger",
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          this.$apis.delMenu({ id: data.id })
            .then(res => {
              console.log(res.data)
              let { error_code, message } = res.data;
              if (error_code == 2001) {
                const parent = node.parent;
                const children = parent.data.children || parent.data;
                const index = children.findIndex(d => d.id === data.id);
                children.splice(index, 1);
                this.$message.success("删除成功");
              } else {
                this.$message.error(message);
              }
            });
        })

    },
    // 编辑
    edit(node, data) {
      console.log(node, data);
      if (data.title && data.title != "新增菜单") {
        this.form.title = data.title;
        this.form.slug = data.slug;
      }
      this.form.id = data.id;
      this.form.parent_id = data.parent_id || 0;
      this.showEditDialog = true;
      this.data = data;
    },
    // 新增一个主菜单
    addNewMenu() {
      if (this.menus.length) {
        if (this.menus[this.menus.length - 1].id != -1) {
          this.menus.push({
            title: "新增菜单",
            parent_id: 0,
            children: [],
            id: -1
          });
        } else {
          this.$message.info("请编辑现有的菜单");
        }
      } else {
        this.menus.push({
          title: "新增菜单",
          parent_id: 0,
          children: [],
          id: -1
        });
      }
    },
    // 提交数据
    editSubmit() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          this.onMenuEdit = true;
          if (this.form.id != -1) {
            this.editMenu();
          } else {
            this.addMenu();
          }
        }
      })
    },
    // 取消编辑
    editCancel() {
      this.showEditDialog = false;
    },
    // 停止编辑,重置form
    handleClose() {
      if (!this.onMenuEdit) {
        this.data = "";
        this.$refs["form"].resetFields();
        this.form = { title: "", slug: "" }
      }
    },
    // 新增菜单
    addMenu() {
      let { slug, title, parent_id } = this.form;
      this.$apis.addMenu({ slug, title, parent_id })
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.data.title = data.title;
            this.data.id = data.id;
            this.data.parent_id = data.parent_id;
            this.data.slug = data.slug;
            this.showEditDialog = false;
          } else {
            this.$message.warning(message);
          }
        }).finally(res => {
          this.onMenuEdit = false;
        });
    },
    // 编辑菜单
    editMenu() {
      let { slug, title, parent_id, id } = this.form;
      this.$apis.updateMenu({ slug, title, parent_id, id })
        .then(res => {
          console.log(res.data);
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.data.title = data.title;
            this.data.slug = data.slug;
            this.showEditDialog = false;
          } else {
            this.$message.warning(message);
          }
        }).finally(res => {
          this.onMenuEdit = false;
        });
    },
    // 列表转树递归
    list2Tree(list, id) {
      let arr = []
      list.forEach(v => {
        if (v.parent_id == id) {
          let obj = v;
          obj.children = this.list2Tree(list, v.id)
          arr.push(JSON.parse(JSON.stringify(obj)))
        }
      });
      return arr;
    },
    // 是否能放下
    allowDrop(draggingNode, dropNode, type) {
      if (type == "inner") {
        return false;
      }
      if (draggingNode.data.parent_id != dropNode.data.parent_id) {
        return false;
      }
      return true
    },
    // 是否能拖拽
    allowDrog() {
      return true
    },
    // 拖拽完成
    dropDone() {
      let menu = JSON.parse(JSON.stringify(this.menus));
      let list = this.tree2List(menu, 0);
      let sort = [];
      list.forEach(v => {
        sort.push(v.id)
      })
      sort.reverse();
      console.log(list, sort);
      this.$apis.menuSort({ sort })
        .then(res => {
          console.log(res.data)
          let { error_code } = res.data;
          if (error_code == 2001) {
            this.$updateUser();
          }
        });
    },
    tree2List(tree, id) {
      let arr = [];
      tree.forEach(v => {
        if (v.parent_id == id) {
          arr.push(v);
          if (v.children && v.children.length) {
            let c_arr = this.tree2List(v.children, v.id);
            arr = arr.concat(c_arr);
          }
        }

      })
      return arr;
    }
  },
};
</script>

<style lang="scss" scoped>
.top-bar {
  padding: 10px 0;
}

.parent {
  font-weight: 800;
  font-size: 20px;
}
.need-edit {
  color: #e6a23c;
}
</style>

<style>
.serring-menu .el-tree-node__content {
  height: 30px;
}
</style>
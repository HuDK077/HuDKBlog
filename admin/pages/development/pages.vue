<template>
  <div class="container">
    <el-container>
      <el-aside>
        <el-card class="menu-tree" v-loading="loading">
          <div class="head">
            <el-input placeholder="搜索列表" v-model="treeSearch" class="input-with-select">
              <template #append>
                <el-button icon="el-icon-plus" @click="appendMenu()"></el-button>
              </template>
            </el-input>
          </div>
          <el-tree
            ref="tree"
            :data="menus"
            node-key="id"
            :props="treeProps"
            highlight-current
            draggable
            :allow-drop="allowDrop"
            :allow-drag="allowDrog"
            @node-drop="dropDone"
            @node-click="handleNodeClick"
            :filter-node-method="filterNode"
            :expand-on-click-node="false"
          >
            <span class="custom-tree-node" slot-scope="{ node, data }">
              <span :class="[data.parent_id == 0 ? 'parent' : '', data.id == -1 ? 'need-edit' : '']">{{ data.title }}</span>
              <span style="padding-left: 10px">
                <el-button type="text" @click="appendMenu(data)">
                  <i class="el-icon-plus"></i>
                </el-button>
                <el-button type="text" @click="updateMenu(node, data)">
                  <i class="el-icon-edit"></i>
                </el-button>
                <el-button type="text" @click.stop="removeMenu(node, data)">
                  <i class="el-icon-delete"></i>
                </el-button>
              </span>
            </span>
          </el-tree>
        </el-card>
      </el-aside>
      <el-main>
        <el-card class="page-info">
          <el-form label-position="left" label-width="5em" inline class="form-expand" v-if="tempMenu">
            <el-row>
              <el-col :span="6">
                <el-form-item label="页面标题">
                  <span>{{ tempMenu.title }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="页面标识">
                  <span>{{ tempMenu.slug }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="页面路径">
                  <span>{{ tempMenu.path }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="页面文件">
                  <span>{{ tempMenu.file }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="重定向">
                  <span>{{ tempMenu.redirect || "无" }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="图标">
                  <span v-if="tempMenu.icon" :class="tempMenu.icon"></span>
                  <span v-else>无图标</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="高亮标识">
                  <span>{{ (tempMenu.meta && tempMenu.meta.equal) || "无" }}</span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="隐藏页面">
                  <span> <el-checkbox :value="tempMenu.meta && tempMenu.meta.hidden"></el-checkbox> </span>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="keepAlive">
                  <span> <el-checkbox :value="tempMenu.meta && tempMenu.meta.keepAlive"></el-checkbox></span>
                </el-form-item>
              </el-col>
            </el-row>
          </el-form>
          <el-empty description="暂无信息" :image-size="100" v-else></el-empty>
        </el-card>

        <el-card class="widget" v-loading="widgetLoading">
          <div class="head">
            <el-button type="primary" size="small" icon="el-icon-plus" :disabled="!widgetLoading && !tempMenu" @click="handleCreateWidget">
              新增控件
            </el-button>
          </div>
          <el-table :data="widgetList" style="width: 100%">
            <el-table-column prop="id" label="ID" width="80"> </el-table-column>
            <el-table-column prop="name" label="控件名称"> </el-table-column>
            <el-table-column prop="slug" label="控件标识"> </el-table-column>
            <el-table-column prop="uri" label="uri"> </el-table-column>
            <el-table-column fixed="right" label="操作" width="100">
              <template slot-scope="scope">
                <el-button type="text" size="small" @click="editRow(scope.row)">编辑</el-button>
                <el-button type="text" size="small" class="table-delete" @click="deleteRow(scope.row)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-main>
    </el-container>

    <el-dialog
      center
      width="60%"
      title="编辑页面"
      @closed="handleMenuClose"
      :visible.sync="showMenuEditDialog"
      :close-on-click-modal="false"
    >
      <div>
        <el-form ref="menuForm" :model="menuForm" :rules="menuRules" label-width="6em" class="menu-form" label-position="left">
          <el-row :gutter="40">
            <el-col :span="24">
              <el-divider content-position="center">主要设置</el-divider>
            </el-col>
            <el-col :span="12">
              <el-form-item label="页面名称" prop="title">
                <el-input v-model="menuForm.title" placeholder="请输入页面名称"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="页面图标" prop="icon">
                <el-input v-model="menuForm.icon" placeholder="请输入页面图标">
                  <template #prepend>
                    <div class="form-icon" :class="menuForm.icon"></div>
                  </template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="页面标识" prop="slug">
                <el-input v-model="menuForm.slug" placeholder="请输入页面标识"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="页面路径" prop="path">
                <el-input v-model="menuForm.path" placeholder="请输入页面路径"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="文件路径" prop="file">
                <el-input v-model="menuForm.file" placeholder="请输入文件路径"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="重定向" prop="redirect">
                <el-input v-model="menuForm.redirect" placeholder="请输入重定向路径"></el-input>
              </el-form-item>
            </el-col>

            <el-col :span="24">
              <el-divider content-position="center">其他设置</el-divider>
            </el-col>
            <el-col :span="12">
              <el-form-item label="高亮标识" prop="equal">
                <el-input v-model="menuForm.equal" placeholder="请输入高亮标识(equal)"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="4">
              <el-form-item label="隐藏页面" prop="hidden">
                <el-checkbox v-model="menuForm.hidden"></el-checkbox>
              </el-form-item>
            </el-col>
            <el-col :span="4">
              <el-form-item label="keepAlive" prop="keepAlive">
                <el-checkbox v-model="menuForm.keepAlive"></el-checkbox>
              </el-form-item>
            </el-col>

            <!-- <el-col :span="24">
              <el-divider content-position="center">自定义设置</el-divider>
            </el-col> -->
          </el-row>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :loading="onMenuEdit" @click="showMenuEditDialog = false">取 消</el-button>
        <el-button :loading="onMenuEdit" type="primary" @click="menuEditSubmit">确 定</el-button>
      </span>
    </el-dialog>

    <el-dialog
      center
      width="60%"
      title="编辑控件"
      @closed="handleWidgetClose"
      :visible.sync="showWidgetEditDialog"
      :close-on-click-modal="false"
    >
      <div>
        <el-form ref="widgetForm" :model="widgetForm" :rules="widgetRules" label-width="6em" class="widget-form" label-position="left">
          <el-row :gutter="40">
            <el-col :span="12">
              <el-form-item label="控件名称" prop="name">
                <el-input v-model="widgetForm.name" placeholder="请输入控件名称"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="控件标识" prop="slug">
                <el-input v-model="widgetForm.slug" placeholder="请输入控件标识"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="控件uri" prop="uri">
                <el-autocomplete v-model="widgetForm.uri" placeholder="请输入控件uri" :fetch-suggestions="queryUriSearch"></el-autocomplete>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :loading="onWidgetEdit" @click="showWidgetEditDialog = false">取 消</el-button>
        <el-button :loading="onWidgetEdit" type="primary" @click="widgetEditSubmit">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { interopDefault } from "@/common/utils";
import { mapActions } from "vuex";
export default {
  name: "devPages",
  data() {
    let vaildeFile = async (r, v, cb) => {

      if (v) {
        if (v != "/") {
          try {
            await (() => interopDefault(import(`@/pages${v}`)))();
            cb();
          } catch (error) {
            cb(new Error('请输入正确的路径'));
          }
        } else {
          cb(new Error('请输入正确的路径'));
        }
      } else {
        cb();
      }
    };
    return {
      treeSearch: "",
      menus: [],
      loading: false,
      treeProps: { label: "title" },
      tempMenu: "",
      widgetLoading: false,
      widgetList: [],

      showMenuEditDialog: false,
      onMenuEdit: false,
      menuForm: {
        id: "",
        parent_id: "",
        title: "",
        icon: "",
        slug: "",
        path: "",
        file: "",
        redirect: "",
        hidden: false,
        keepAlive: false,
        equal: "",
      },
      menuRules: {
        title: [{ required: true, message: "请输入菜单名称", trigger: "blur" },],
        slug: [{ required: true, message: "请输入菜单标识", trigger: "blur" }],
        path: [
          { required: true, message: "请输入页面路径", trigger: "blur" },
        ],
        file: [
          { validator: vaildeFile, trigger: ["blur", "change"] }
        ],
      },

      showWidgetEditDialog: false,
      onWidgetEdit: false,
      uris: [],
      widgetForm: {
        id: "",
        name: "",
        slug: "",
        uri: "",
      },
      widgetRules: {
        name: [{ required: true, message: "请输入控件名称", trigger: "blur" },],
        slug: [{ required: true, message: "请输入控件标识", trigger: "blur" },],
      }
    };
  },
  created() {
    this.loadData();
    this.loadUris();
  },
  methods: {
    ...mapActions({
      initPermissionData: "permission/initPermissionData"
    }),
    // 数据加载
    loadData(cb) {
      this.loading = true;
      this.$apis.getAllMenu()
        .then((res) => {
          console.log(res.data);
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            if (data.length) {
              let menus = this.list2Tree(data, 0);
              this.menus = menus;
              cb && cb()
            }
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // uri列表
    loadUris() {
      this.uris = Object.values(this.$url).map(m => ({ value: m, str: m.toLowerCase() }))
    },
    // 列表转树递归
    list2Tree(list, id) {
      let arr = [];
      list.forEach(v => {
        if (v.parent_id == id) {
          let obj = v;
          obj.children = this.list2Tree(list, v.id);
          arr.push(JSON.parse(JSON.stringify(obj)));
        }
      });
      return arr;
    },
    // 节点点击
    handleNodeClick(e) {
      console.log(e);
      if (this.tempMenu.id != e.id) {
        this.getWidgetList(e.id)
      }
      this.tempMenu = e
    },
    // 树筛选
    filterNode(value, data) {
      if (!value) return true;
      return data.title.indexOf(value) !== -1;
    },
    // 新增页面
    appendMenu(data) {
      console.log(data);
      this.menuForm.id = -1;
      this.menuForm.parent_id = 0
      if (data) {
        this.menuForm.parent_id = data.id
        this.menuForm.slug = `${data.slug}.`
      }
      this.showMenuEditDialog = true
    },
    // 更新节点
    updateMenu(node, data) {
      console.log(node, data);
      let { id, parent_id, title, icon, path, file, slug, redirect, meta } = data;
      let { equal, hidden, keepAlive } = meta;
      this.menuForm.id = id
      this.menuForm.parent_id = parent_id
      this.menuForm.title = title
      this.menuForm.icon = icon
      this.menuForm.path = path
      this.menuForm.file = file
      this.menuForm.slug = slug
      this.menuForm.redirect = redirect
      this.menuForm.equal = equal || ""
      this.menuForm.hidden = !!hidden
      this.menuForm.keepAlive = !!keepAlive

      this.showMenuEditDialog = true
    },
    // 删除节点
    removeMenu(node, data) {
      let childName = "";
      let ids = [data.id]
      if (data.children && data.children.length) {
        data.children.forEach((v, i) => {
          childName += v.title;
          ids.push(v.id)
          if (i != data.children.length - 1) {
            childName += " , ";
          }
        });
      }
      let str = `是否删除菜单: ${data.title}${childName ? " 以及子菜单: " + childName : ""}`;
      this.$confirm(str, "确认删除", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        this.$apis.delMenu({ id: data.id }).then(res => {
          console.log(res.data);
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.initPermissionData()
            const parent = node.parent;
            const children = parent.data.children || parent.data;
            const index = children.findIndex(d => d.id === data.id);
            children.splice(index, 1);
            if (ids.includes(this.tempMenu.id)) {
              this.tempMenu = {}
              this.widgetList = []
            }
            this.$message.success("删除成功");
          } else {
            this.$message.error(message);
          }
        });
      });
    },
    // 页面编辑关闭
    handleMenuClose() {
      this.menuForm.id = ''
      this.menuForm.parent_id = ""
      this.menuForm.title = ""
      this.menuForm.icon = ""
      this.menuForm.path = ""
      this.menuForm.file = ""
      this.menuForm.slug = ""
      this.menuForm.redirect = ""
      this.menuForm.equal = ""
      this.menuForm.hidden = false
      this.menuForm.keepAlive = false
      this.$nextTick(() => {
        this.$refs["menuForm"].clearValidate()
      });
    },
    // 页面编辑提交
    menuEditSubmit() {
      this.$refs["menuForm"].validate(valid => {
        if (valid) {
          this.onMenuEdit = true;
          if (this.menuForm.id != -1) {
            this.editMenu();
          } else {
            this.addMenu();
          }
        }
      });
    },
    // 新增菜单
    addMenu() {
      let { parent_id, title, icon, slug, path, file, redirect, hidden, keepAlive, equal } = this.menuForm;
      let meta = {
        hidden, keepAlive, equal
      }
      this.$apis.addMenu({ parent_id, title, icon, slug, path, file, redirect, meta })
        .then(res => {
          console.log(res.data);
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {

            this.$nextTick(() => {
              this.$refs.tree.append(data, parent_id)
              this.initPermissionData()
              this.showMenuEditDialog = false
              this.onMenuEdit = false;
            });
          } else {
            this.$message.warning(message);
          }
        })
        .finally(() => {
          this.onMenuEdit = false;
        });
    },
    // 编辑菜单
    editMenu() {
      let { id, parent_id, title, icon, slug, path, file, redirect, hidden, keepAlive, equal } = this.menuForm;
      let meta = {
        hidden, keepAlive, equal
      }
      this.$apis.updateMenu({ id, parent_id, title, icon, slug, path, file, redirect, meta })
        .then(res => {
          console.log(res.data);
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.$nextTick(() => {
              let node = this.$refs.tree.getNode(id)
              if (node) {
                node.data.title = data.title
                node.data.icon = data.icon
                node.data.slug = data.slug
                node.data.path = data.path
                node.data.redirect = data.redirect
                node.data.meta = data.meta
                this.handleNodeClick(node.data);
              }
              this.initPermissionData()
              this.showMenuEditDialog = false
              this.onMenuEdit = false;
            });
          } else {
            this.onMenuEdit = false;
            this.$message.warning(message);
          }
        })
        .catch(res => {
          console.warn(res);
          this.onMenuEdit = false;
        })
    },
    // 新增控件
    handleCreateWidget() {
      this.showWidgetEditDialog = true
      if (this.tempMenu) {
        this.widgetForm.slug = `${this.tempMenu.slug}@`
      }
    },
    // 获取控件列表
    getWidgetList(id) {

      this.widgetLoading = true
      this.$apis.listWidget({ menu_id: id })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.widgetList = data
          }
        })
        .finally(() => {
          this.widgetLoading = false
        })
    },
    // 控件编辑
    editRow(e) {
      let { id, name, slug, uri } = e
      this.widgetForm.id = id;
      this.widgetForm.name = name;
      this.widgetForm.slug = slug;
      this.widgetForm.uri = uri;

      this.showWidgetEditDialog = true
    },
    // 控件删除
    deleteRow(e) {
      let str = `是否删除控件${e.name}`;
      this.$confirm(str, "确认删除", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        this.$apis.deleteWidget({ ids: [e.id] })
          .then(res => {
            console.log(res.data);
            let { error_code, message } = res.data;
            if (error_code == 2001) {
              this.getWidgetList(this.tempMenu.id);
              this.$message.success("删除成功");
            } else {
              this.$message.error(message);
            }
          });
      });
    },
    // 控件编辑关闭
    handleWidgetClose() {
      this.widgetForm.id = "";
      this.widgetForm.name = "";
      this.widgetForm.slug = "";
      this.widgetForm.uri = "";
      this.$nextTick(() => {
        this.$refs["widgetForm"].clearValidate()
      });
    },
    // 编辑提交
    widgetEditSubmit() {
      this.$refs["widgetForm"].validate(valid => {
        if (valid) {
          this.onWidgetEdit = true;
          if (this.widgetForm.id) {
            this.editWidget();
          } else {
            this.addWidget();
          }
        }
      });
    },
    // 组件编辑
    editWidget() {
      let { id, name, slug, uri } = this.widgetForm;
      let menu_id = this.tempMenu.id;
      this.$apis.updateWidget({ id, menu_id, name, slug, uri })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.getWidgetList(menu_id)
            this.showWidgetEditDialog = false
            this.$message.success("修改成功");
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.onWidgetEdit = false
        });
    },
    // 组件URI查找
    queryUriSearch(str, cb) {
      let result = this.uris.filter(f => f.str.includes(str.toLowerCase()))
      cb(result)
    },
    // 组件新增
    addWidget() {
      let { name, slug, uri } = this.widgetForm;
      let menu_id = this.tempMenu.id;

      this.$apis.addWidget({ menu_id, name, slug, uri })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.getWidgetList(menu_id)
            this.showWidgetEditDialog = false
            this.$message.success("新增成功");
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.onWidgetEdit = false
        });
    },
    // 是否能放下
    allowDrop(draggingNode, dropNode, type) {
      if (type == "inner") {
        return false;
      }
      if (draggingNode.data.parent_id != dropNode.data.parent_id) {
        return false;
      }
      return true;
    },
    // 是否能拖拽
    allowDrog() {
      return true;
    },
    // 拖拽完成
    dropDone() {
      let menu = JSON.parse(JSON.stringify(this.menus));
      let list = this.tree2List(menu, 0);
      let sort = list.map(m => m.id);
      sort.reverse();
      // console.log(list, sort);
      this.$apis.menuSort({ sort })
        .then(res => {
          console.log(res.data);
          let { error_code } = res.data;
          if (error_code == 2001) {
            this.$store.dispatch("permission/initPermissionData")
          }
        });
    },
    // 树转列表
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
      });
      return arr;
    }
  },
  watch: {
    treeSearch(n) {
      this.$refs.tree.filter(n);
    }
  },
};
</script>

<style lang="scss" scoped>
.container {
  height: calc(100vh - 104px);
  /deep/ .el-container {
    height: 100%;
    .el-aside {
      overflow: unset;
      .el-card {
        height: calc(100% - 2px);
      }
    }
  }

  .menu-tree {
    .input-with-select {
      width: 100%;
      margin-bottom: 10px;
    }
    .el-icon-plus {
      color: #67c23a;
    }
    .el-icon-delete {
      color: #f56c6c;
    }
  }

  .menu-form {
    /deep/ .el-input-group__prepend {
      padding: 0;
      .form-icon {
        font-size: 30px;
        width: 40px;
        text-align: center;
      }
    }
  }
  .form-expand {
    font-size: 0;

    /deep/ .el-form-item {
      margin-right: 0;
      margin-bottom: 0;
      label {
        width: 90px;
        color: #b7c9e2;
      }
    }
  }
  .page-info {
    min-height: 240px;
  }

  .widget {
    margin-top: 10px;
  }
  .widget-form {
    /deep/ .el-autocomplete {
      width: 100%;
    }
  }
}
</style>
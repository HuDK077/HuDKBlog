<template>
  <div class="container">
    <el-container>
      <el-aside>
        <el-card class="menu-tree" v-loading="loading">
          <div class="head">
            <el-input placeholder="搜索列表" v-model="search" class="input-with-select"> </el-input>
          </div>
          <el-tree
            ref="tree"
            :data="list"
            node-key="id"
            highlight-current
            @node-click="handleNodeClick"
            :filter-node-method="filterNode"
            :expand-on-click-node="false"
          >
            <span class="custom-tree-node" slot-scope="{ data }">
              <span>{{ data.name }}({{ data.username }})</span>
            </span>
          </el-tree>
        </el-card>
      </el-aside>
      <el-main>
        <el-row :gutter="10">
          <el-col class="info-block" :span="12">
            <el-card v-loading="pagesLoading">
              <el-divider>页面权限</el-divider>

              <el-tree
                v-if="!!activePerlission"
                ref="pageTree"
                :data="pages"
                node-key="id"
                show-checkbox
                highlight-current
                default-expand-all
                @node-click="handlePageClick"
                @check="handlePageCheck"
                :expand-on-click-node="false"
              >
                <span class="custom-tree-node" slot-scope="{ data }">
                  <span>{{ data.title }}</span>
                </span>
              </el-tree>
            </el-card>
          </el-col>
          <el-col class="info-block" :span="12">
            <el-card>
              <el-divider>组件权限</el-divider>
              <el-tree
                v-if="!!activePerlission"
                ref="widgetTree"
                :data="widgetTreeList"
                node-key="id"
                show-checkbox
                highlight-current
                default-expand-all
                check-on-click-node
                :expand-on-click-node="false"
                @check="handleWidgetCheck"
              >
                <span class="custom-tree-node" slot-scope="{ data }">
                  <span>{{ data.name }}</span>
                </span>
              </el-tree>
            </el-card>
          </el-col>
          <el-col :span="24" class="button">
            <el-button type="primary" :disabled="!canSaveData" @click="saveData">保存信息</el-button>
          </el-col>
        </el-row>
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // role
      search: "",
      list: [],
      loading: false,
      activePerlission: "",
      // page
      pages: [],
      pagesLoading: false,
      basicActiveRolePages: [], /* 角色的 原始选中的页面 */
      basicActivePages: [], /* 原始选中的页面 */
      activePages: [], /* 选中的页面 */

      // widget
      widgets: [],
      widgetLoading: false,
      basicActiveRoleWidgets: [], /* 角色的 原始选择的组件 */
      basicActiveWidgets: [], /* 原始选择的组件 */
      activeWidgets: [], /* 已选择的组件 */
      choosePages: [], /* 页面筛选 */
      widgetTreeList: [] /* 显示的组件表 */
    }
  },
  created() {
    window.tth = this
    this.loadData();
    this.getAllMenu()
    this.getWidgets()
  },
  computed: {
    canSaveData() {
      let status = false
      if (this.activePerlission) {
        let { activePages, basicActivePages, activeWidgets, basicActiveWidgets } = this
        if (activePages.length != basicActivePages.length) {
          status = true
        } else {
          status = !!(activePages.filter(f => !basicActivePages.includes(f))).length
        }
        if (activeWidgets.length != basicActiveWidgets.length) {
          status = true
        } else if (!status) {
          status = !!(activeWidgets.filter(f => !basicActiveWidgets.includes(f))).length
        }
      }
      return status
    }
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      this.$apis.getAllUser({ limit: 1000 })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.list = data.data;
          }
        }).finally(() => {
          this.loading = false;
        });
    },
    // 加载页面树
    getAllMenu() {
      this.$apis.getAllMenu()
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            data.forEach(v => { v.disabled = false })
            this.pages = this.list2Tree(data, 0);
          }
        });
    },
    // 组件列表
    getWidgets() {
      this.$apis.listWidget()
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            this.widgets = data
          }
        });
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
    // 树筛选
    filterNode(value, data) {
      if (!value) return true;
      return data.name.indexOf(value) !== -1 || data.username.indexOf(value) !== -1;
    },
    // 列表被点击
    handleNodeClick(e) {
      console.log(e);
      this.resetAll()
      if (e.id != this.activePerlission.id) {
        this.diffPageAndWidgetsChange()
          .then(() => {
            this.activePerlission = e
            this.getPermissions(e.id)
          })
          .catch(() => {
            this.saveData()
          })
      }
    },
    // 重置数据
    resetAll() {
      this.choosePages = [];
      this.$refs.pageTree && this.$refs.pageTree.setCurrentKey()
    },
    // 页面权限树点击
    handlePageClick(e) {
      console.log("page", this.deepGetAttr([e], "id"));
      this.choosePages = this.deepGetAttr([e], "id");
      this.makeWidgetList()
    },
    // 递归获取数据
    deepGetAttr(list, key) {
      let values = []
      try {
        list.forEach(v => {
          values.push(v[key])
          let temp_val = []
          if (v.children && v.children.length) {
            temp_val = this.deepGetAttr(v.children, key)
          }
          values = values.concat(temp_val)
        })
      } catch (error) {
        console.error(error);
      }
      return values
    },
    // 获取权限数据
    getPermissions(id) {
      this.pagesLoading = true
      this.$apis.getPermission({ id })
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            let { menus, role_menus, role_widgets, widgets } = data;

            // // 页面权限
            this.basicActiveRolePages = this.$clone(role_menus);

            let menuIds = menus.concat(role_menus)
            menuIds = Array.from(new Set(menuIds))
            this.basicActivePages = this.$clone(menuIds);
            this.activePages = menuIds;
            const menu_ids = this.getActiveTree(this.$clone(this.pages), menuIds);

            this.$refs.pageTree.setCheckedKeys(menu_ids, false);
            this.setPagesDisable(this.pages, role_menus);
            this.pagesLoading = false;
            // 组件权限
            this.basicActiveRoleWidgets = this.$clone(role_widgets)
            this.makeWidgetList()
            let widgetIds = widgets.concat(role_widgets);
            widgetIds = Array.from(new Set(widgetIds))
            this.basicActiveWidgets = this.$clone(widgetIds);
            // 初始化选中
            this.$nextTick(() => {
              this.activeWidgets = widgetIds
              this.setWidgetCheck()
            })
          } else {
            this.$u.showToast(message)
          }
        })
        .finally(() => {
          this.pagesLoading = false
        });
    },
    // 禁止操作部分页面
    setPagesDisable(list, pages) {
      list.forEach(v => {
        if (pages.includes(v.id)) {
          v.disabled = true
        } else {
          v.disabled = false
        }
        if (v.children && v.children.length) {
          this.setPagesDisable(v.children, pages)
        }
      })
    },
    // 获取页面选中的树数据
    getActiveTree(pages, actives, needAll = true) {
      let tempList = [];
      pages.forEach(v => {
        let childList = [];
        let childIds = [];
        let equalLength = false;
        if (actives.includes(v.id)) {
          if (v.children && v.children.length) {
            childIds = v.children.map(m => m.id)
            childList = this.getActiveTree(v.children, actives, needAll)
          }

          if (childIds.length) {
            let temp_cid = 0
            childIds.forEach(cid => {
              if (childList.includes(cid)) {
                temp_cid++
              }
            })
            if (temp_cid == childIds.length) {
              equalLength = true;
            }
          } else {
            equalLength = true;
          }
          if (!v.children.length || equalLength || !needAll) {
            tempList.push(v.id);
          }
        }
        tempList = tempList.concat(childList);
      })
      return tempList
    },
    // 复选框被选中
    handlePageCheck(e, { checkedKeys, halfCheckedKeys }) {
      console.log("handlePageCheck", e);
      let newKeys = checkedKeys.concat(halfCheckedKeys);
      // diff 选中页面
      let less = [], and = [];
      let more = this.$clone(newKeys)
      this.activePages.forEach(v => {
        if (more.includes(v)) {
          and = and.concat(more.splice(more.indexOf(v), 1))
        } else {
          less.push(v)
        }
      })
      this.activePages = newKeys
      // 设置 组件权限列表和选中状态
      this.makeWidgetList()
      let lessWidgetIds = this.widgets.filter(f => less.includes(f.menu_id)).map(m => m.id)
      let moreWidgetIds = this.widgets.filter(f => more.includes(f.menu_id)).map(m => m.id)
      let tempActiveWidgets = this.activeWidgets.filter(f => !lessWidgetIds.includes(f))
      tempActiveWidgets = tempActiveWidgets.concat(moreWidgetIds);
      this.activeWidgets = tempActiveWidgets
      // console.log(` less:${less}\n more:${more}\n lessWidgetIds:${lessWidgetIds} \n moreWidgetIds:${moreWidgetIds} \n tempActiveWidgets:${tempActiveWidgets}`);
      this.setWidgetCheck()
    },
    // 格式化组件权限列表
    makeWidgetList() {
      if (this.widgets.length) {
        const widgets = this.$clone(this.widgets)
        const tempPages = this.activePages
        const tempRoleWidgets = this.basicActiveRoleWidgets
        const tempChoosePages = this.choosePages;
        let widgetTree = []
        // 先分两组,页面选中的和页面未选中的
        let activePages = []
        let disabledPages = []
        let choosePages = []

        widgets.forEach(v => {
          v.disabled = true
          if (tempPages.includes(v.menu_id)) {
            if (!tempRoleWidgets.includes(v.id)) {
              v.disabled = false
            }
            if (tempChoosePages.includes(v.menu_id)) {
              choosePages.push(v)
            } else {
              activePages.push(v)
            }
          }
          else {
            if (tempChoosePages.includes(v.menu_id)) {
              choosePages.push(v)
            } else {
              disabledPages.push(v)
            }
          }
        })

        const chooseTree = {
          name: "已筛选页面组件",
          id: -1,
          children: choosePages
        }
        const activeTree = {
          name: "已分配页面组件",
          id: -2,
          children: activePages
        }
        const disabledTree = {
          name: "未分配页面组件",
          id: -3,
          disabled: true,
          children: disabledPages
        }

        widgetTree = [
          activeTree,
          disabledTree
        ]

        if (choosePages.length) {
          widgetTree.unshift(chooseTree)
        }
        this.widgetTreeList = widgetTree
      }
    },
    // 设置组件权限的状态
    setWidgetCheck() {
      this.$refs.widgetTree.setCheckedKeys(this.activeWidgets, true)
    },
    // 组件选择
    handleWidgetCheck(e, { checkedKeys, halfCheckedKeys }) {
      console.log(e, checkedKeys, halfCheckedKeys);
      this.activeWidgets = checkedKeys.filter(f => f > 0)
    },
    // 查验页面和组件数据是否改变 确定需要保存时进入catch,无改变或取消保存进入then
    diffPageAndWidgetsChange() {
      return new Promise((resolve, reject) => {
        if (this.canSaveData) {
          this.$confirm("有未保存的更改等待保存", "确认保存", {
            confirmButtonText: "保存",
            cancelButtonText: "取消",
            type: "info"
          })
            .then(() => {
              // TODO 保存信息
              reject()
            })
            .catch(() => {
              this.$message.warning("取消保存");
              // 取消保存
              resolve();
            })
        } else {
          resolve()
        }
      })
    },
    // 保存信息
    saveData() {
      let { activePerlission, activePages, basicActiveRolePages, activeWidgets, basicActiveRoleWidgets } = this
      let menu_ids = activePages.filter(f => !basicActiveRolePages.includes(f))
      let widget_ids = activeWidgets.filter(f => !basicActiveRoleWidgets.includes(f))
      // console.log(menu_ids, widget_ids);
      this.$apis.updatePermission({ id: activePerlission.id, menu_ids, widget_ids })
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("修改成功");
            this.$nextTick(() => {
              this.getPermissions(activePerlission.id)
            })
          } else {
            this.$u.showToast(message)
          }
        });
    }
  },
  watch: {
    // 搜索
    search(n) {
      this.$refs.tree.filter(n);
    },
  },
}
</script>

<style lang="scss" scoped>
.container {
  height: calc(100vh - 160px);
  /deep/ .el-container {
    height: 100%;
    .el-aside {
      overflow: unset;
      .el-card {
        height: calc(100% - 2px);
      }
    }

    .el-main {
      overflow: unset;
      .el-row {
        width: 100%;
        height: 100%;
        margin: 0 !important;
        .el-col {
          &.info-block {
            height: calc(100% - 60px);
          }
          &.button {
            line-height: 60px;
            text-align: center;
          }
          .el-card {
            height: 100%;
          }
        }
      }
    }
  }

  .menu-tree {
    .head {
      margin-bottom: 10px;
    }
    .input-with-select {
      width: 100%;
    }
  }
}
</style>
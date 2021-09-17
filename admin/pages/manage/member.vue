<template>
  <div>
    <div class="top-bar">
      <el-input placeholder="搜索会员" v-model="search" class="input-with-select" @keyup.enter.native="loadData">
        <el-button slot="append" type="primary" icon="el-icon-search" @click="loadData">搜索</el-button>
      </el-input>
    </div>

    <el-table v-loading="loading" :data="memberList" stripe style="width: 100%">
      <el-table-column prop="id" label="ID" width="40"></el-table-column>
      <el-table-column prop="openid" label="OPENID" width="250"></el-table-column>
      <el-table-column prop="nickname" label="昵称"></el-table-column>
      <el-table-column prop="real_name" label="姓名"></el-table-column>
      <el-table-column prop="avatar" label="头像" width="80">
        <template slot-scope="scope">
          <e-img :preview-src-list="[scope.row.avatar]" :src="scope.row.avatar" class="avatar"></e-img>
        </template>
      </el-table-column>
      <el-table-column prop="gender" label="性别" width="50">
        <template slot-scope="{row}">{{row.gender == 0 ? "未知" : row.gender == 1 ? "男" : "女"}}</template>
      </el-table-column>
      <el-table-column prop="phone" label="手机号" width="120"></el-table-column>
      <el-table-column prop="type" label="会员类型">
        <template slot-scope="{row}">{{getType(row.type)}}</template>
      </el-table-column>
      <el-table-column prop="province" label="省份"></el-table-column>
      <el-table-column prop="city" label="城市"></el-table-column>
      <el-table-column fixed="right" label="操作" width="50">
        <template slot-scope="scope">
          <el-button @click="editRow(scope.row)" type="text" size="small">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination">
      <el-pagination @size-change="loadData" @current-change="loadData" :current-page.sync="page" :page-sizes="[10, 20, 50, 100]" :page-size.sync="limit" layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
    </div>

    <el-dialog width="50%" title="修改会员信息" :visible.sync="showEditDialog" :close-on-click-modal="false" @closed="editDialogClosed">
      <div>
        <el-form :model="form" :rules="rules" ref="form" label-width="10em" label-position="top">
          <el-form-item label="真实姓名" prop="real_name">
            <el-input @keyup.enter.native="submitMember" v-model="form.real_name"></el-input>
          </el-form-item>
          <el-form-item label="手机号" prop="phone">
            <el-input @keyup.enter.native="submitMember" v-model="form.phone" :min="1" label="手机号"></el-input>
          </el-form-item>
          <el-form-item label="会员类型 " prop="type">
            <el-select style="width:100%" v-model="form.type" placeholder="请选择会员类型">
              <template v-for="(item,index) in menberType">
                <el-option :key="index" :label="item.name" :value="item.id"></el-option>
              </template>
            </el-select>
          </el-form-item>
          <el-form-item label="核销权限" prop="is_verification" v-if="form.type != 1">
            <el-switch v-model="form.is_verification" active-text="是" :inactive-value="0" :active-value="1">
            </el-switch>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :loading="onEdit" @click="showEditDialog=false">取 消</el-button>
        <el-button :loading="onEdit" type="primary" @click="submitMember">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    const menberType = [
      {
        id: 1,
        name: "普通用户"
      },
      {
        id: 2,
        name: "员工"
      },
      {
        id: 3,
        name: "管理员"
      },
    ];

    const vaildPhone = (r, v, cb) => {
      if (v == "") {
        cb(new Error("请输入手机号"));
      } else if (!/^(?:(?:\+|00)86)?1[3-9]\d{9}$/.test(v)) {
        cb(new Error("请输入正确的手机号"));
      } else {
        cb();
      }
    };

    return {
      search: "",
      limit: 10,
      page: 1,
      total: 0,
      loading: false,
      memberList: [],
      showEditDialog: false,
      onEdit: false,
      menberType,
      form: {
        real_name: "",
        phone: "",
        type: "",
        is_verification: 0,
      },
      rules: {
        real_name: [
          { required: true, message: '请输入真实姓名', trigger: 'blur' }
        ],
        type: [
          { required: true, message: '请选择会员类型', trigger: 'blur' }
        ],
        is_verification: [
          { required: true, message: '', trigger: 'blur', type: "number" }
        ],
        phone: [
          { required: true, message: '请输入手机号', trigger: 'blur' },
          { validator: vaildPhone, trigger: ["blur", "change"] }
        ],
      }
    }
  },
  created() {
    this.loadData();
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      let { limit, page, search } = this;
      this.$apis.getAllMember({ limit, page, search })
        .then(res => {
          console.log(res.data)
          let { error_code, data, message } = res.data;
          if (error_code == 2001) {
            this.memberList = data.data;
            this.total = data.total;
          } else {
            this.$message.error(message);
          }
        })
        .finally(() => {
          this.loading = false;
        })
    },
    // 编辑用户
    editRow(e) {
      console.log(e);
      let { real_name, phone, type, is_verification, id } = e;
      let obj = { real_name, phone, type, is_verification, id };
      Object.keys(obj).forEach(k => {
        this.form[k] = obj[k];
      })
      this.showEditDialog = true;
    },
    // 提交更改
    submitMember() {
      this.$refs["form"].validate(vaild => {
        if (vaild) {
          this.onEdit = true;
          let { real_name, phone, type, is_verification, id } = this.form;
          this.$apis.updateMember({ real_name, phone, type, is_verification, id })
            .then(res => {
              console.log(res.data)
              let { error_code, message } = res.data;
              if (error_code == 2001) {
                this.loadData();
                this.$message.success("修改成功");
                this.showEditDialog = false;
              } else {
                this.$message.error(message);
              }
            })
            .finally(() => {
              this.onEdit = false;
            });
        }
      })
    },
    // dialog 关闭
    editDialogClosed() {
      this.$refs["form"].resetFields();
      this.form = { real_name: "", phone: "", type: "", is_verification: 0 };
    },
    // 获取用户类型
    getType(t) {
      let res = this.menberType.find(v => v.id == t)
      return (res && res.name) || t;
    }
  },
}
</script>

<style lang="scss" scoped>
.pagination {
  padding-top: 10px;
  text-align: center;
}

.avatar {
  width: 35px;
  height: 35px;
}
</style>
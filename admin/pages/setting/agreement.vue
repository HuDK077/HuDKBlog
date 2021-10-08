<template>
  <div>
    <el-form :model="form" :rules="rules" ref="form" label-width="8em" label-position="left">
      <el-form-item label="设置名" prop="name">
        <el-input v-model="form.name" placeholder="设置名"></el-input>
      </el-form-item>
      <el-form-item class="form-block" label="内容" prop="value">
        <e-editor v-model="form.value"></e-editor>
      </el-form-item>
    </el-form>

    <el-button @click="onSave" class="save-btn" type="primary">保存</el-button>
  </div>
</template>

<script>
import { eEditor } from "@/components";
export default {
  data() {
    return {
      form: {
        name: "",
        value: "",
      },
      rules: {
        name: [{ required: true, message: '请填写设置名称', trigger: 'blur' }],
        value: [{ required: true, message: '请填写设置内容', trigger: 'blur' }],
      },
    }
  },
  created() {
    this.loadData();
  },
  methods: {
    // 数据加载
    loadData() {
      this.$apis.systemDataDetail({ key: 'agreement' })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            let form = ["name", "value"];
            form.forEach(v => {
              this.form[v] = data[v]
            })
          }
        });
    },
    // 保存
    onSave() {
      this.$refs.form.validate(v => {
        if (v) {
          this.updateRow();
        }
      })
    },
    // 更新
    updateRow() {
      let { name, value } = this.form;
      this.$apis.updateSystemData({ key:'agreement',name, value })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("更新成功");
          } else {
            this.$message.error(message);
          }
        });
    },
  },
  components: { eEditor },
}
</script>

<style lang="scss" scoped>
.form-block {
  ::v-deep .el-form-item__label {
    float: none;
  }

  ::v-deep .el-form-item__content {
    margin: 0 !important;
  }
}


.tips {
  color: #409eff;
}
</style>

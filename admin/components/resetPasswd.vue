<template>
  <el-dialog
    width="50%"
    title="修改密码"
    :visible.sync="showResetDialog"
    :close-on-click-modal="false"
    @closed="editDialogClosed"
  >
    <div>
      <el-form :model="form" :rules="rules" ref="form" label-width="7em" label-position="top">
        <el-form-item label="登录密码" prop="password">
          <el-input v-model="form.password" placeholder="请输入登录密码" type="password"></el-input>
        </el-form-item>
        <el-form-item label="重复登录密码" prop="checkPassword">
          <el-input v-model="form.checkPassword" placeholder="请再次输入登录密码" type="password"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="cancel">取 消</el-button>
      <el-button type="primary" @click="submit">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: "resetPasswd",
  data() {
    const vaildePass = (r, v, cb) => {

      if (!v) {
        cb(new Error('请输入密码'));
      } else {
        cb();
      }
    };
    const vaildeChedkPass = (r, v, cb) => {
      if (v == '') {
        cb(new Error('请再次输入密码'));
      } else if (v != this.form.password) {
        cb(new Error('两次输入密码不一致!'));
      } else {
        cb();
      }
    }
    return {
      form: {
        password: "",
        checkPassword: "",
      },
      rules: {
        password: [
          { required: true, message: '请输入密码', trigger: 'blur' },
          { validator: vaildePass, trigger: ["blur", "change"] }
        ],
        checkPassword: [
          { required: true, message: '请再次输入密码', trigger: 'blur' },
          { validator: vaildeChedkPass, trigger: ["blur", "change"] }
        ]
      },
      showResetDialog: false
    }
  },
  computed: {
    ...mapGetters({
      resetPwd: "local/resetPwd"
    })
  },
  methods: {
    ...mapActions({
      // 设置密码框状态
      resetPwdStatus: "local/resetPwdStatus"
    }),
    // 重置form
    editDialogClosed() {
      this.form = { password: "", checkPassword: "" }
      this.$nextTick(() => {
        this.$refs["form"].resetFields();
      })
    },
    // 关闭dialog
    cancel() {
      this.resetPwdStatus(false)
      // this.$store.dispatch("local/resetPwdStatus", { status: false })
    },
    // 提交
    submit() {
      this.$refs["form"].validate(vaild => {
        console.log(vaild);
        if (vaild) {
          let { password } = this.form;
          this.$apis.resetPaw({ password })
            .then(res => {
              console.log(res.data)
              let { error_code, message } = res.data;
              if (error_code == 2001) {
                this.$message.success("密码修改成功");
              } else {
                this.$message.error(message);
              }

            }).finally(() => {
              this.cancel()
            });
        }
      })
    },
  },
  watch: {
    resetPwd(n) {
      this.showResetDialog = !!n;
    },
    showResetDialog(n) {
      if (!n) {
        this.resetPwdStatus(false)
        // this.$store.dispatch("local/resetPwdStatus", { status: false })
      }
    }
  },
}
</script>

<style>
</style>
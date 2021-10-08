<template>
  <div class="page-main" v-loading="loading">
    <el-form style="text-align: left;" :model="form" :rules="rules" ref="form" label-width="8em" label-position="left">
      <el-form-item label="活动banner" prop="banner">
        <el-upload drag action="/" class="file-uploader" :multiple="false" :limit="1" :show-file-list="false" :before-upload="uploadFilter()">
          <template v-if="form.banner">
            <e-img :src="form.banner_src" class="banner" />
          </template>
          <div v-else>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">
              将文件拖到此处，或
              <em>点击上传</em>
            </div>
          </div>
        </el-upload>
        <el-alert :closable="false" title="banner建议分辨率为750px*424px,大小小于500kb" type="warning"></el-alert>
      </el-form-item>
      <el-form-item label="跳转类型" prop="type">
        <el-select clearable v-model="form.type" placeholder="选择跳转类型">
          <el-option label="其他小程序" value="1"></el-option>
          <el-option label="活动链接" value="2"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="跳转地址" prop="jump_url">
        <el-input v-model="form.jump_url" placeholder="跳转活动链接或小程序的APPID"> </el-input>
      </el-form-item>
    </el-form>

    <el-button @click="saveBanner" class="save-btn" type="primary">保存banner</el-button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      name:'',
      form: {
        banner: "",
        banner_src: "",
        jump_url: "",
        type: "",
      },
      rules: {
        banner: [{ required: true, message: "请上传文件", trigger: 'blur' }],
        jump_url: [{ required: false, message: "", trigger: 'blur' }]
      },
    }
  },
  created() {
    this.loadData();
  },

  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      this.$apis.systemDataDetail({ key: 'activity_banner' })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            let form = ["banner", "banner_src", "jump_url", "type",];
            form.forEach(v => {
              this.form[v] = data['value'][v];
            })
            this.name = data['name'];
          }
        })
        .finally(() => {
          this.loading = false;
        })
    },
    // 保存活动banner
    saveBanner() {
      this.$refs["form"].validate(vaild => {
        console.log(vaild);
        if (vaild) {
          this.updateRow();
        }
      })
    },
    // 更新banner
    updateRow() {
      let { banner, jump_url, type } = this.form;
      this.$apis.updateSystemData({key:'activity_banner',name:this.name, value:{banner, jump_url, type }})
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

    // 上传文件
    uploadFilter() {
      return (file) => {
        let { type, size } = file;
        if (type.split("/")[0] != "image") {
          this.$message.warning(`请上传图片类型的文件`);
          return false
        }
        if ((size / 1024) > 500) {
          this.$message.warning(`请上传大小小于500kb的图片`);
          return false
        }

        this.$CDN.upload(file)
          .then(res => {
            console.log(res);
            let { file_id, img } = res;
            this.form.banner = file_id;
            this.form.banner_src = img
          })
          .catch(res => {
            console.warn(res);
          })
        return false;
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.page-main {
  text-align: center;
  .save-btn {
    margin-top: 10px;
    font-size: 20px;
    padding: 10px 80px;
  }
  /deep/ .file-uploader {
    &.full {
      .el-upload {
        display: none;
      }
    }
    .el-upload {
      border: 1px dashed #d9d9d9;
      border-radius: 6px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      width: 375px;
      height: 212px;
      line-height: unset;
      vertical-align: unset;
      &:hover {
        border-color: #409eff;
      }
      .el-upload-dragger {
        border: unset;
        width: 375px;
        height: 212px;
      }
    }
    .thumbnail,
    .banner {
      width: 375px;
      height: 212px;
      display: block;
    }
    .el-upload-list__item {
      width: 375px;
      height: 212px;
    }
  }
  .el-alert {
    /deep/ .el-alert__content {
      line-height: 1;
    }
  }
}
</style>

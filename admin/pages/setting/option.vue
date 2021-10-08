<template>
  <div class="page-main" v-loading="loading">
    <el-alert
      class="alert"
      title="请在超级管理员协助下修改系统配置选项，错误或不合理的修改可能会造成系统运行错误。本表单不对数据做任何校验处理，请务必输入正确与合法的数据。"
      :closable="false"
      type="error"
    ></el-alert>
    <el-form v-if="webForms.length" :model="webForm" ref="webForm" label-width="6em">
      <template v-for="(item,index) in webForms">
        <el-form-item :key="index" :label="item.description">
          <el-input v-model="webForm[item.name]"></el-input>
        </el-form-item>
      </template>

      <el-form-item label="logo">
        <el-upload
          drag
          action="/"
          class="logo-uploader default"
          :show-file-list="false"
          :before-upload="uploadFilter('logo')"
        >
          <e-img v-if="logoForm.logo" :id="logoForm.logo" class="logo" />
          <div v-else>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">
              将文件拖到此处，或
              <em>点击上传</em>
            </div>
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item label="logo(小)">
        <el-upload
          drag
          action="/"
          class="logo-uploader logo-uploader-sm"
          :show-file-list="false"
          :before-upload="uploadFilter('logo_sm')"
        >
          <e-img v-if="logoForm.logo_sm" :id="logoForm.logo_sm" class="logo-sm" />
          <div v-else>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">
              将文件拖到此处，
              <div style="line-height:1">
                或
                <em>点击上传</em>
              </div>
            </div>
          </div>
        </el-upload>
      </el-form-item>
    </el-form>
    <el-button type="primary" @click="confirmWeb">更新网站配置</el-button>
  </div>
</template>

<script>
export default {
  data() {
    let webArr = ["website_title", "website_keywords", "company_full_name", "company_short_name", "company_telephone", "website_icp", "system_version", "system_author", "system_author_website"];
    return {
      webForm: {},
      webForms: [],
      logoForm: {
        logo: "",
        logo_sm: "",
      },
      loading: true,
      webArr,
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    // 数据加载
    loadData() {
      this.loading = true;
      let webTemp = [];

      this.$apis.getConfig()
        .then(res => {
          // console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            data.forEach(v => {
              if (this.webArr.includes(v.name)) {
                webTemp.push(v);
                this.$set(this.webForm, v.name, v.value);
              }
              if (v.name == "logo") {
                this.logoForm.logo = v.value;
              }
              if (v.name == "logo_sm") {
                this.logoForm.logo_sm = v.value;
              }
            });
            let webForms = [];
            this.webArr.forEach(v => {
              // console.log(v);
              let res = webTemp.find(f => { return f.name == v });
              webForms.push(res);
            })
            this.webForms = webForms;
          }
        }).finally(() => {
          this.loading = false;
        });
    },
    // 更新网站配置
    confirmWeb() {
      let arr = [];
      let { webForm, logoForm } = this;
      Object.keys(webForm).forEach(v => {
        let res = this.webForms.find(f => { return f.name == v });
        if (res) {
          res.value = webForm[v];
          arr.push(res);
        }
      })
      Object.keys(logoForm).forEach(v => {
        let obj = {}
        obj.name = v;
        obj.value = logoForm[v];
        arr.push(obj);
      })

      this.$apis.updateConfig(arr)
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.$message.success("更新成功");
            this.$updateSiteConfig();
          } else {
            this.$message.error(message);
          }
        });
    },
    // 上传文件
    uploadFilter(name) {
      return (file) => {
        let { type, size } = file;
        if (type.split("/")[0] != "image") {
          this.$message.warning("请上传图片类型的文件");
          return false
        }
        if (size / 1024 > 300) {
          this.$message.warning("请上传大小小于300kb的图片");
          return false
        }

        let fd = new FormData();
        fd.append("file", file)
        this.$apis.uploadImage(fd, { headers: { 'Content-Type': 'multipart/form-data' } })
          .then(res => {
            let { error_code, data } = res.data;
            if (error_code == 2001) {
              this.logoForm[name] = data.file_id;
            }
          });
        return false;

      }
    },
  },
}
</script>

<style lang="scss" scoped>
.page-main {
  padding-bottom: 40px;
  ::v-deep.alert {
    margin-bottom: 1em;
  }
}
</style>

<style lang="scss">
.logo-uploader {
  .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    &:hover {
      border-color: #409eff;
    }
  }
  .el-upload .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    text-align: center;
  }

  &.default {
    .el-upload-dragger {
      width: 315px;
      height: 75px;
      .el-icon-upload {
        margin: 0 10px;
        font-size: 24px;
        line-height: 75px;
      }
      .el-upload__text {
        display: inline-block;
        line-height: 75px;
      }
    }
  }
  &.logo-uploader-sm {
    .el-upload-dragger {
      width: 150px;
      height: 150px;
      .el-icon-upload {
        margin: 10px 0;
      }
    }
  }

  .logo {
    width: 315px;
    height: 75px;
  }
  .logo-sm {
    width: 150px;
    height: 150px;
  }
}
</style>

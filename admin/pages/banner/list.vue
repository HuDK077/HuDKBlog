<template>
  <div>
    <div class="top-bar">
      <e-btn tag="banner.add" type="primary" icon="el-icon-plus" @click="addRow">新增轮播</e-btn>
    </div>
    <el-table v-loading="loading" :data="list" stripe style="width: 100%" class="table">
       <el-table-column prop="id" label="ID" width="80"></el-table-column>
      <el-table-column prop="banner_src" label="banner" align="center">
        <template slot-scope="{row}">
          <e-img class="banner" fit="cover" :src="row.banner_src" :previewSrcList="[row.banner_src]"></e-img>
        </template>
      </el-table-column>
      <el-table-column prop="sort" label="排序"></el-table-column>
      <el-table-column fixed="right" label="操作" width="100">
        <template slot-scope="{row}">
          <e-btn @click="editRow(row)" tag="banner.update" type="text" size="small">编辑</e-btn>
          <e-btn @click="deleteRow(row)" tag="banner.delete" class="table-delete" type="text" size="small">删除</e-btn>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="editMode ? '编辑banner' : '新增banner'" @closed="handleClose" :visible.sync="showEditDialog" center width="50%" :close-on-click-modal="false" :destroy-on-close="true">
      <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="7em" label-position="left">
          <el-form-item label="banner" prop="banner">
            <el-upload drag action="/" class="file-uploader default"  :multiple="false" :limit="1" :show-file-list="false" :before-upload="uploadFile()">
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
            <el-alert :title="alertTitle" type="info" :closable="false"></el-alert>
          </el-form-item>
          <el-form-item label="排序" prop="sort">
            <el-input-number v-model="form.sort" :min="1" placeholder></el-input-number>
            <el-alert title="默认为1,越大越靠前" type="info" :closable="false"></el-alert>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <e-btn :loading="onSubmit" :tag="['banner.update','banner.add']" @click="showEditDialog = false">取 消</e-btn>
        <e-btn :loading="onSubmit" :tag="['banner.update','banner.add']" type="primary" @click="editSubmit">确 定</e-btn>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      list: [],
      loading: false,
      editMode: false,
      onSubmit: false,
      type: "",
      showEditDialog: false,
      form: {
        banner: "",
        banner_src: "",
        sort: 1,
      },
      rules: {
        banner: [{ required: true, message: "请上传banner", trigger: "blur" }],
      },
      circles: [],
      baseCircles: [],
      circleLoading: false,
    }
  },
  created() {
    // this.remoteCircle();
    this.loadData();
  },
  computed: {
    ...mapGetters({
      imageSize: "local/imageSize"
    }),
    alertTitle() {
      return `轮播分辨率建议692*292;文件大小限制为${this.imageSize}kb`;
    }
  },
  methods: {
    // 数据加载
    loadData() {
      let { type } = this;
      this.loading = true;
      this.$apis.bannerList({ type })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {

            this.list = data
          }
        }).finally(() => {
          this.loading = false
        });
    },
    // 新增banner
    addRow() {
      this.editMode = false;
      this.showEditDialog = true;
    },
    // 编辑
    editRow(e) {
      console.log(e);
      let { banner, banner_src, id,  sort } = e;
      this.form.id = id;
      this.form.banner = banner;
      this.form.banner_src = banner_src;
      this.form.sort = sort;
      this.editMode = true;
      this.showEditDialog = true;
    },
    // 删除
    deleteRow(row) {
      console.log(row);
      let { id, } = row;
      let str = `确认删除轮播 ${id} 吗`
      this.$confirm(str, "确认删除", {
        confirmButtonText: "删除",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        return this.$apis.deleteBanner({ id })
      }).then(res => {
        let { error_code, message } = res.data;
        if (error_code == 2001) {
          this.loadData();
        } else {
          this.$message.error(message);
        }
      });
    },
    // 关闭
    handleClose() {
      this.form = {
        banner: "",
        banner_src: "",
        sort: 1,
      }
      this.onSubmit = false
    },
    // 提交
    editSubmit() {
      this.onSubmit = true;
      this.$refs.form.validate((valid, e) => {
        console.log(valid, e);
        if (valid) {
          if (this.editMode) {
            this.updateBanner();
          } else {
            this.createBanner();
          }
        } else {
          this.onSubmit = false;
          this.$message.warning(e[Object.keys(e)[0]][0].message);
        }
      });
    },
    // 上传文件
    uploadFile() {
      return (file) => {
        let { type, size } = file;
        if (type.split("/")[0] != "image") {
          this.$message.warning(`请上传图片类型的文件`);
          return false
        }
        if (size / 1024 > this.imageSize) {
          this.$message.warning(`请上传大小小于${this.imageSize}kb的图片`);
          return false
        }
        this.$qiniu.upload(file)
          .then(res => {
            this.form.banner = res.file_id
            this.form.banner_src = res.img
          })
        return false;
      }
    },
    // 更新
    updateBanner() {
      let { banner, id, sort } = this.form;
      this.$apis.updateBanner({ banner, id, sort })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.loadData();
            this.showEditDialog = false;
            this.$message.success("更新banner成功");
          } else {
            this.$message.error(message);
          }
        });
    },
    // 创建
    createBanner() {
      let { banner, sort, } = this.form;
      this.$apis.addBanner({ banner, sort, })
        .then(res => {
          console.log(res.data)
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.loadData();
            this.showEditDialog = false;
            this.$message.success("新增banner成功");
          } else {
            this.$message.error(message);
          }
        });
    },
  },
  watch: {
  },
}
</script>

<style lang="scss" scoped>
.table {
  .banner {
    width: 100px;
    height: 50px;
  }
}
.file-uploader {
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
    /deep/ .el-upload-dragger {
      width: 300px;
      height: 126px;
      .el-icon-upload {
        margin: 0 10px;
        font-size: 24px;
        line-height: 60px;
      }
      .el-upload__text {
        display: inline-block;
        line-height: 60px;
      }
    }

    .banner {
      width: 300px;
      height: 126px;
    }
  }
}
</style>

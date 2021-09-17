<template>
  <editor @onKeyDown="keyDown" :api-key="key" v-model="content" :init="init" :disabled="disabled"></editor>
</template>


<script>
import tinymce from "tinymce/tinymce";
import Editor from "@tinymce/tinymce-vue";
import "tinymce/icons/default/icons";
import "tinymce/themes/silver";
// import "tinymce/plugins/image";
import "tinymce/plugins/media";
import "tinymce/plugins/table";
import "tinymce/plugins/lists";
import "tinymce/plugins/contextmenu";
import "tinymce/plugins/wordcount";
import "tinymce/plugins/colorpicker";
import "tinymce/plugins/textcolor";
import "tinymce/plugins/preview";
import "tinymce/plugins/code";
import "tinymce/plugins/link";
import "tinymce/plugins/advlist";
import "tinymce/plugins/codesample";
import "tinymce/plugins/hr";
import "tinymce/plugins/fullscreen";
import "tinymce/plugins/textpattern";
import "tinymce/plugins/searchreplace";
import "tinymce/plugins/autolink";
import "tinymce/plugins/directionality";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/visualchars";
import "tinymce/plugins/template";
import "tinymce/plugins/charmap";
import "tinymce/plugins/nonbreaking";
import "tinymce/plugins/insertdatetime";
import "tinymce/plugins/imagetools";
import "tinymce/plugins/autosave";
import "tinymce/plugins/autoresize";
// 扩展插件
// import "../assets/tinymce/plugins/lineheight/plugin";
// import "../assets/tinymce/plugins/bdmap/plugin";
import "@/assets/tinymce/plugins/image/index";
import "@/assets/tinymce/plugins/indent2em/index";

export default {
  name: "eEditor",
  components: {
    Editor
  },
  computed: {
    key() {
      return this.$env.TINY_KEY
    }
  },
  props: {
    value: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    },
    plugins: {
      type: [String, Array],
      default:
        "preview searchreplace autolink directionality visualblocks visualchars fullscreen image template code  table charmap hr nonbreaking insertdatetime advlist lists wordcount imagetools textpattern autoresize indent2em"
    },
    toolbar: {
      type: [String, Array],
      default:
        "code undo redo | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough | alignleft aligncenter alignright alignjustify outdent indent formatpainter lineheight indent2em | \
    styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
    table image charmap hr pagebreak insertdatetime | fullscreen preview"
    }
  },
  /*
      plugins: {
      type: [String, Array],
      default:
        "preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code codesample table charmap hr nonbreaking insertdatetime advlist lists wordcount imagetools textpattern autosave autoresize"
    },
    toolbar: {
      type: [String, Array],
      default:
        "code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link codesample | alignleft aligncenter alignright alignjustify outdent indent formatpainter | \
    styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
    table image media charmap hr pagebreak insertdatetime | fullscreen preview"
    }
  */
  data() {
    return {
      // 初始化配置
      init: {
        language_url: "/tinymce/langs/zh_CN.js",
        language: "zh_CN",
        skin_url: "/tinymce/skins/ui/oxide",
        content_css: "/tinymce/skins/content/default/content.min.css",
        height: 770,
        min_height: 770,
        max_height: 770,
        toolbar_mode: "wrap",
        plugins: this.plugins,
        toolbar: this.toolbar,
        content_style: "p {margin: 5px 0;}",
        fontsize_formats: "12px 14px 16px 18px 24px 36px 48px 56px 72px",
        lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.6 1.7 1.8 2 3 4 5",
        font_formats:
          "微软雅黑=Microsoft YaHei,Helvetica Neue,PingFang SC,sans-serif;苹果苹方=PingFang SC,Microsoft YaHei,sans-serif;宋体=simsun,serif;仿宋体=FangSong,serif;黑体=SimHei,sans-serif;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;",
        branding: false,
        // 此处为图片上传处理函数，这个直接用了base64的图片形式上传图片，
        // 如需ajax上传可参考https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_handler
        images_upload_handler: this.uploadImg,
        relative_urls: false,
        remove_script_host: false,
        // document_base_url: this.$env.API_URL,
      },
      content: this.value
    };
  },
  mounted() {
    tinymce.init({});
  },
  methods: {
    uploadImg(blobInfo, success, failure, progress) {
      let blob = blobInfo.blob();
      try {
        if (blob instanceof Blob && !(blob instanceof File)) {
          let name = this.$randomString(16);
          let type = blob.type;
          blob = new File([blob], `${name}.${type.replace(/^.*\//, "")}`, { type });
        }
        this.$CDN.upload(blob)
          .then(res => {
            success(res.img);
          })
          .catch(res => {
            console.error(res);
            failure("上传失败")
          })
      } catch (error) {
        console.error(error);
        failure("上传失败")
      }
    },
    keyDown(e) {
      this.$emit("onKeyDown", e);
    }
  },
  watch: {
    value(newValue) {
      this.content = newValue;
    },
    content(newValue) {
      this.$emit("input", newValue);
    }
  }
};
</script>
<style scoped lang="scss">
</style>

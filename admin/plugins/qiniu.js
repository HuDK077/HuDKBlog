import Vue from "vue";
import BMF from "@/utils/BMF";
export class QiNiu {
    constructor(vm) {
        this.vm = vm
        this.bmf = new BMF();
        // 请参考demo的index.js中的initQiniu()方法，若在使用处对options进行了赋值，则此处config不需要赋默认值。init(options) 即updateConfigWithOptions(options)，会对config进行赋值
        this.config = {
            // bucket 所在区域。ECN, SCN, NCN, NA, ASG，分别对应七牛云的：华东，华南，华北，北美，新加坡 5 个区域
            qiniuRegion: '',
            // 七牛云bucket 外链前缀，外链在下载资源时用到
            qiniuBucketURLPrefix: '',

            // 获取uptoken方法三选一即可，执行优先级为：uptoken > uptokenURL > uptokenFunc。三选一，剩下两个置空。推荐使用uptokenURL，详情请见 README.md
            // 由其他程序生成七牛云uptoken，然后直接写入uptoken
            qiniuUploadToken: '',
            // 从指定 url 通过 HTTP GET 获取 uptoken，返回的格式必须是 json 且包含 uptoken 字段，例如： {"uptoken": "0MLvWPnyy..."}
            qiniuUploadTokenURL: '',
            // uptokenFunc 这个属性的值可以是一个用来生成uptoken的函数，详情请见 README.md
            qiniuUploadTokenFunction: function () { },

            // qiniuShouldUseQiniuFileName 如果是 true，则文件的 key 由 qiniu 服务器分配（全局去重）。如果是 false，则文件的 key 使用微信自动生成的 filename。出于初代sdk用户升级后兼容问题的考虑，默认是 false。
            // 微信自动生成的 filename较长，导致fileURL较长。推荐使用{qiniuShouldUseQiniuFileName: true} + "通过fileURL下载文件时，自定义下载名" 的组合方式。
            // 自定义上传key 需要两个条件：1. 此处shouldUseQiniuFileName值为false。 2. 通过修改qiniuUploader.upload方法传入的options参数，可以进行自定义key。（请不要直接在sdk中修改options参数，修改方法请见demo的index.js）
            // 通过fileURL下载文件时，自定义下载名，请参考：七牛云“对象存储 > 产品手册 > 下载资源 > 下载设置 > 自定义资源下载名”（https://developer.qiniu.com/kodo/manual/1659/download-setting）。本sdk在README.md的"常见问题"板块中，有"通过fileURL下载文件时，自定义下载名"使用样例。
            qiniuShouldUseQiniuFileName: false
        }
    }

    // init(options) 将七牛云相关配置初始化进本sdk
    // 在整个程序生命周期中，只需要 init(options); 一次即可
    // 如果需要变更七牛云配置，再次调用 init(options); 即可
    init(options) {
        this.updateConfigWithOptions(options);
    }

    // 更新七牛云配置
    updateConfigWithOptions(options) {
        if (options.region) {
            this.config.qiniuRegion = options.region;
        } else {
            console.error('qiniu uploader need your bucket region');
        }
        if (options.uptoken) {
            this.config.qiniuUploadToken = options.uptoken;
        } else if (options.uptokenURL) {
            this.config.qiniuUploadTokenURL = options.uptokenURL;
        } else if (options.uptokenFunc) {
            this.config.qiniuUploadTokenFunction = options.uptokenFunc;
        }
        if (options.domain) {
            this.config.qiniuBucketURLPrefix = options.domain;
        }
        this.config.qiniuShouldUseQiniuFileName = options.shouldUseQiniuFileName
    }

    // 正式上传的前置方法，做预处理，应用七牛云配置
    upload(file, options, progress) {
        console.log(file);
        return new Promise((resolve, reject) => {
            if (null == file) {
                console.error('qiniu uploader need file to upload');
                return reject('qiniu uploader need file to upload');
            }
            if (options) {
                this.updateConfigWithOptions(options);
            } else {
                options = {}
            }

            this.bmf.md5(
                file,
                (err, md5) => {
                    if (!err) {
                        let mime_type = file.type
                        let size = file.size;
                        let client_file_name = `${md5}.${mime_type.split("/")[1]}`
                        let path = `upload/api/${this.vm.$formatDate("MM_dd", Date.now())}/${client_file_name}`;
                        options.key = path
                        console.log(md5);
                        Vue.prototype.$apis.getImageFileCheck({ file_id: md5 })
                            .then(res => {
                                // console.log(res.data)
                                let { error_code, data } = res.data;
                                if (error_code == 2001) {
                                    resolve(data);
                                    return Promise.reject("break");
                                } else if (error_code == 2006) {
                                    return this.doUpload(file, options, progress)
                                }
                            })
                            .then(res => {
                                return Vue.prototype.$apis.addImageInfo({ file_id: md5, path: res.key, size, client_file_name, mime_type })
                            })
                            .then(res => {
                                console.log(res.data)
                                let { error_code, data } = res.data;
                                if (error_code == 2001) {
                                    resolve(data)
                                    return Promise.reject("break")
                                } else {
                                    return Promise.reject(res)
                                }
                            })
                            .catch(res => {
                                console.log(res);
                                if (res != "break") {
                                    reject(res)
                                }
                            })
                    } else {
                        reject(err)
                    }
                }
            );
        })

    }

    // 正式上传
    doUpload(file, options, progress) {

        return new Promise((resolve, reject) => {
            if (null == this.config.qiniuUploadToken && this.config.qiniuUploadToken.length > 0) {
                console.error('qiniu UploadToken is null, please check the init config or networking');
                return
            }
            var url = this.uploadURLFromRegionCode(this.config.qiniuRegion);
            let fileName = ""
            // 自定义上传key（即自定义上传文件名）。通过修改qiniuUploader.upload方法传入的options参数，可以进行自定义文件名称。如果options非空，则使用options中的key作为fileName
            if (options && options.key) {
                fileName = options.key;
            }
            let formData = new FormData();
            formData.append("file", file);
            formData.append("token", this.config.qiniuUploadToken);

            // qiniuShouldUseQiniuFileName 如果是 true，则文件的 key 由 qiniu 服务器分配（全局去重）。如果是 false，则文件的 key 使用微信自动生成的 filename。出于初代sdk用户升级后兼容问题的考虑，默认是 false。
            if (!this.config.qiniuShouldUseQiniuFileName) {
                formData.append("key", fileName);
            }
            this.vm.$axios.post(url, formData, {
                onUploadProgress: (res) => { if (progress) { progress(res) } }
            })
                .then(res => {
                    console.log(res);
                    let dataString = res.data
                    try {
                        if (dataString instanceof String) {
                            dataString = JSON.parse(res.data);
                        }
                        resolve(dataString)
                    } catch (e) {
                        console.log('parse JSON failed, origin String is: ' + dataString)
                        reject(e)
                    }
                })
                .catch(error => {
                    console.error(error);
                    reject(error)
                })
        })
    }

    // 获取七牛云uptoken, url为后端服务器获取七牛云uptoken接口
    getQiniuToken(callback) {
        Vue.prototype.$apis.getUploadConfig()
            .then(res => {
                console.log(res);
                let { error_code, data } = res.data;
                if (error_code == 2001) {
                    this.updateConfigWithOptions(data)
                    if (callback) {
                        callback()
                    }
                } else {
                    console.error('qiniu UploadToken is error, please check the init config or networking: ' + res);
                }
            })
            .catch(res => {
                console.error('qiniu UploadToken is null, please check the init config or networking: ' + res);
            })
    }

    // 选择七牛云文件上传接口，文件向匹配的接口中传输。ECN, SCN, NCN, NA, ASG，分别对应七牛云的：华东，华南，华北，北美，新加坡 5 个区域
    uploadURLFromRegionCode(code) {
        var uploadURL = null;
        switch (code) {
            case 'ECN': uploadURL = 'https://up.qiniup.com'; break;
            case 'NCN': uploadURL = 'https://up-z1.qiniup.com'; break;
            case 'SCN': uploadURL = 'https://up-z2.qiniup.com'; break;
            case 'NA': uploadURL = 'https://up-na0.qiniup.com'; break;
            case 'ASG': uploadURL = 'https://up-as0.qiniup.com'; break;
            default: console.error('please make the region is with one of [ECN, SCN, NCN, NA, ASG]');
        }
        return uploadURL;
    }
}

export default function (vm, inject) {
    console.log(vm);
    const qiniu = new QiNiu(vm);
    if (vm.store.getters["auth/isLogin"]) {
        qiniu.getQiniuToken()
    }
    inject("qiniu", qiniu)
}

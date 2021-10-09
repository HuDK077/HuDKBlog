<template>
    <div class="user-edit">
        <el-form :model="form" :rules="rules" ref="form" label-width="7em" label-position="top">
            <el-form-item label="头像" prop="avatar">
                <el-upload
                    drag
                    action="/"
                    class="avatar-uploader goods thumb-uploader"
                    :multiple="false"
                    :limit="1"
                    :show-file-list="false"
                    :before-upload="uploadFilter('avatar')"
                >
                    <e-img v-if="form.avatar" :src="form.avatar_src" class="thumbnail"/>
                    <div v-else>
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">
                            将文件拖到此处，或
                            <em>点击上传</em>
                        </div>
                    </div>
                </el-upload>
                <el-alert :closable="false" :title="alertThumbTitle" type="info"></el-alert>
            </el-form-item>

            <el-form-item v-if="role.role_id == 1" prop="username">
                <div slot="label" class="custom-label">
                    用户登录名
                    <span class="tips">只能4-10位英文字母或阿拉伯数字</span>
                </div>
                <el-input v-model="form.username" placeholder="请输入用户登录名"></el-input>
            </el-form-item>
            <el-form-item label="用户名" prop="name">
                <el-input v-model="form.name" placeholder="请输入用户名"></el-input>
            </el-form-item>
            <el-form-item v-if="role.role_id == 1" label="角色(用户组) " prop="role_id">
                <el-select v-model="form.role_id" placeholder="请选择角色(用户组)">
                    <template v-for="(item,index) in roles">
                        <el-option :key="index" :label="item.name+' ( '+item.slug+' ) '" :value="item.id"></el-option>
                    </template>
                </el-select>
            </el-form-item>
            <el-form-item label="登录密码" prop="password">
                <el-input v-model="form.password" placeholder="请输入登录密码" type="password"></el-input>
            </el-form-item>
            <el-form-item label="重复登录密码" prop="checkPassword">
                <el-input v-model="form.checkPassword" placeholder="请再次输入登录密码" type="password"></el-input>
            </el-form-item>
        </el-form>
        <el-button type="primary" @click="confirmUser">{{ editMode ? "更新用户" : "新增用户" }}</el-button>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    data() {

        let vaildeUsername = (r, v, cb) => {
            if (!v) {
                cb(new Error('请输入用户登录名'));
            } else if (!/^[a-zA-Z0-9]{4,10}$/.test(v)) {
                cb(new Error('只能4-10位英文字母或阿拉伯数字'));
            } else {
                cb();
            }
        };
        let vaildePass = (r, v, cb) => {

            if (!v) {
                if (!this.editMode) {
                    cb(new Error('请输入登录密码'));
                } else {
                    cb();
                }
            } else {
                cb();
            }
        };
        let vaildeChedkPass = (r, v, cb) => {
            if (v == '' && !this.form.password) {
                if (!this.editMode) {
                    cb(new Error('请再次输入密码'));
                } else {
                    cb();
                }
            } else if (v != this.form.password) {
                cb(new Error('两次输入密码不一致!'));
            } else {
                cb();
            }
        }

        return {
            editMode: false,
            id: "",
            roles: [],
            form: {
                username: "",
                name: "",
                role_id: "",
                password: "",
                checkPassword: "",
                avatar: "",
                avatar_src: "",
            },
            rules: {
                username: [
                    {required: true, message: '请输入用户登录名', trigger: 'blur'},
                    {validator: vaildeUsername, trigger: ["blur", "change"]}
                ],
                name: [
                    {required: true, message: '请输入用户名', trigger: 'blur'},
                ],
                role_id: [
                    {required: true, message: '请选择角色', trigger: 'change'}
                ],
                password: [
                    {required: true, message: '请输入登录密码', trigger: 'blur'},
                    {validator: vaildePass, trigger: ["blur", "change"]}
                ],
                checkPassword: [
                    {required: true, message: '请再次输入登录密码', trigger: 'blur'},
                    {validator: vaildeChedkPass, trigger: ["blur", "change"]}
                ]
            }
        }
    },
    computed: {
        ...mapGetters({
            role: "auth/role",
            avatarSize: "local/imageSize"
        }),
        alertThumbTitle() {
            return `头像建议分辨率为336px*281px,大小小于${this.avatarSize}kb`;
        },
    },
    mounted() {
        if (this.role.role_id == 1) {
            this.loadData();
        }
        let {id} = this.$route.params;
        if (id && id != "add") {
            this.id = id;
            this.editMode = true;
            this.rules.password[0].required = false;
            this.rules.checkPassword[0].required = false;
            this.loadUser()
        } else {
            this.editMode = false;
        }
    },
    methods: {
        // 获取账号信息
        loadUser() {
            this.$apis.getUser({id: this.id})
                .then(res => {
                    console.log(res.data)
                    let {error_code, data, message} = res.data;
                    if (error_code == 2001) {
                        let {avatar, name, username, role_id, avatar_src} = data;
                        this.form.avatar = avatar;
                        this.form.avatar_src = avatar_src;
                        this.form.name = name;
                        this.form.username = username;
                        this.form.role_id = role_id;
                    } else {
                        this.$message.error(message);
                    }
                });
        },
        // 数据加载
        loadData() {
            if (this.roles.length) {
                return
            }
            this.$apis.getRole()
                .then(res => {
                    console.log(res.data)
                    let {error_code, data, message} = res.data;
                    if (error_code == 2001) {
                        this.roles = data;
                    } else {
                        this.$message.error(message);
                    }
                });
        },
        // 提交
        confirmUser() {
            this.$refs["form"].validate(vaild => {
                // console.log(vaild);
                if (vaild) {
                    if (this.editMode) {
                        if (this.role.role_id == 1) {
                            this.updateUser4Admin();
                        } else {
                            this.updateUser();
                        }
                    } else {
                        this.addUser();
                    }
                }
            })
        },
        // 上传文件
        uploadFilter() {
            return (file) => {
                let {type, size} = file;
                if (type.split("/")[0] != "image") {
                    this.$message.warning("请上传图片类型的文件");
                    return false;
                }
                if (size / 1024 > this.avatarSize) {
                    this.$message.warning(`请上传大小小于${this.avatarSize}kb的图片`);
                    return false;
                }
                this.$qiniu.upload(file)
                    .then(res => {
                        console.log(res);
                        let {file_id, img} = res;
                        this.form.avatar = file_id;
                        this.form.avatar_src = img;
                        console.log(this.form)
                    });
                return false;
            };
        },
        // 新增用户
        addUser() {
            let {username, password, role_id, avatar, name} = this.form;
            this.$apis.addUser({username, password, role_id, avatar, name})
                .then(res => {
                    console.log(res.data)
                    let {error_code, message} = res.data;
                    if (error_code == 2001) {
                        this.$message.success("添加成功");
                        this.$router.back();
                    } else {
                        this.$message.error(message);
                    }
                });
        },
        // 更新用户
        updateUser() {
            let {password, avatar, name} = this.form;
            let id = this.id;
            this.$apis.editUser({id, password, avatar, name})
                .then(res => {
                    console.log(res.data)
                    let {error_code, message} = res.data;
                    if (this.id == this.$store.getters["auth/member"].id) {
                        this.$updateUser();
                    }
                    if (error_code == 2001) {
                        this.$message.success("修改成功");
                        this.$router.back();
                    } else {
                        this.$message.error(message);
                    }
                });
        },
        // 更新用户管理员
        updateUser4Admin() {
            let {username, password, role_id, avatar, name} = this.form;
            let id = this.id;
            this.$apis.updateUser({id, username, password, role_id, avatar, name})
                .then(res => {
                    console.log(res.data)
                    let {error_code, message} = res.data;
                    if (this.id == this.$store.getters["auth/member"].id) {
                        this.$updateUser();
                    }
                    if (error_code == 2001) {
                        this.$message.success("修改成功");
                        this.$router.back();
                    } else {
                        this.$message.error(message);
                    }
                });
        },
    },
    watch: {
        role(n) {
            if (n) {
                if (this.role.role_id == 1) {
                    this.loadData();
                }
            }
        }
    },
}
</script>

<style lang="scss" scoped>
.el-select {
    width: 100%;
}

.custom-label {
    display: inline-block;

    .tips {
        font-size: 12px;
        color: green;
    }
}

.avatar {
    width: 200px;
    height: 200px;
    display: block;
}
</style>

<style lang="scss">
.user-edit {
    .el-form--label-top .el-form-item__label {
        line-height: 1.4;
    }

    .user-avatar-uploader {

        .el-upload {
            border: 1px dashed #d9d9d9;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            line-height: unset;
            vertical-align: unset;

            &:hover {
                border-color: #409eff;
            }

            .el-upload-dragger .el-upload__text {
                font-size: 12px;
            }
        }

        .el-upload .avatar-uploader-icon {
            font-size: 28px;
            color: #8c939d;
            text-align: center;
        }

        .el-upload-dragger {
            width: 200px;
            height: 200px;
        }
    }

    .thumbnail {
        width: 358px;
        height: 178px;
        display: block;
    }

}
</style>

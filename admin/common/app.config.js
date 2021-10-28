const baseUrl = "/admin/";
export const urls = {
  // 基础接口
  login: baseUrl + 'auth/login', // 登录
  getAuthUser: baseUrl + 'auth/getAuthUser_V2', // 获取用户信息
  loginOut: baseUrl + 'auth/loginOut', // 登出
  resetPaw: baseUrl + 'auth/resetPaw', // 修改账号密码接口
  authentication: baseUrl + 'auth/authentication', // 登录认证
  // 错误
  errorPush: baseUrl + 'errorLog/errorPush', // 页面错误推送接口
  // 文件相关
  getFile: baseUrl + 'file/getFile', // 获取文件接口
  uploadImage: baseUrl + 'file/uploadImage', // 图片上传接口
  uploadFile: baseUrl + 'file/uploadFile', // 文件上传接口
  getUploadConfig: baseUrl + 'file/getUploadConfig', // 获取上传配置
  getImageFileCheck: baseUrl + 'file/getImageFileCheck', // 检查文件是否存在
  addImageInfo: baseUrl + 'file/addImageInfo', // 图片信息写入数据库
  // 系统配置相关
  getConfig: baseUrl + 'config/getConfig', // 获取系统配置接口_v1.0
  updateConfig: baseUrl + 'config/updateConfig', // 更新系统配置接口_v1.0
  addConfig: baseUrl + 'config/addConfig', // 添加系统配置接口_v1.0
  getConfigArray: baseUrl + 'config/getConfigArray', // 获取系统配置(对象)
  // 菜单相关
  getUserMenu: baseUrl + 'menu/getUserMenu', // 获取个人权限菜单接口_v1.0
  getAllMenu: baseUrl + 'menu/getAllMenu', // 获取所有菜单接口
  addMenu: baseUrl + 'menu/addMenu', // 添加菜单接口
  updateMenu: baseUrl + 'menu/updateMenu', // 更新菜单接口
  delMenu: baseUrl + 'menu/delMenu', // 删除菜单接口
  menuSort: baseUrl + 'menu/menuSort', // 菜单排序
  // 控件管理
  listWidget: baseUrl + 'widget/listWidget', // 获取控件列表
  addWidget: baseUrl + 'widget/addWidget', // 添加控件
  updateWidget: baseUrl + 'widget/updateWidget', // 修改控件
  deleteWidget: baseUrl + 'widget/deleteWidget', // 删除控件

  // 角色相关
  getRole: baseUrl + 'role/getRole', // 获取所有角色接口_v1.0
  addRole: baseUrl + 'role/addRole', // 添加角色接口_v1.0
  updateRole: baseUrl + 'role/updateRole', // 更新角色接口_v1.0
  delRole: baseUrl + 'role/delRole', // 删除角色接口_v1.0
  // 角色权限
  getRM: baseUrl + 'roleMenu/getRM', // 获取角色关联的菜单接口
  updateRM: baseUrl + 'roleMenu/updateRM', // 修改角色菜单接口
  // 用户权限
  getPermission: baseUrl + 'userPermissions/getPermission', // 获取用户权限
  updatePermission: baseUrl + 'userPermissions/updatePermission', // 更新用户权限(私有部分)
  // 用户
  getAllUser: baseUrl + 'user/getAllUser', // 获取所有后台用户接口
  addUser: baseUrl + 'user/addUser', // 添加后台账号
  updateUser: baseUrl + 'user/updateUser', // 修改账号信息
  delUser: baseUrl + 'user/delUser', // 删除账号接口
  getUser: baseUrl + 'user/getUser', // 获取账号详情
  editUser: baseUrl + 'user/editUser', // 获取账号详情
  // 会员相关
  getAllMember: baseUrl + 'member/getAllMember', // 获取所有用户接口
  updateMember: baseUrl + 'member/updateMember', // 更新用户信息

  // aaa: baseUrl + 'bbb/aaa', //
}


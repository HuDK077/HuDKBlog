const baseUrl = "/admin/";
export const urls = {
    // 基础接口
    login: baseUrl + 'auth/login', // 登录
    getAuthUser: baseUrl + 'auth/getAuthUser', // 获取用户信息
    loginOut: baseUrl + 'auth/loginOut', // 登出
    resetPaw: baseUrl + 'auth/resetPaw', // 修改账号密码接口
    authentication: baseUrl + 'auth/authentication', // 登录认证
    // 错误
    errorPush: baseUrl + 'errorLog/errorPush', // 页面错误推送接口
    // 文件相关
    getFile: baseUrl + 'file/getFile', // 获取文件接口
    uploadImage: baseUrl + 'file/uploadImage', // 图片上传接口
    uploadFile: baseUrl + 'file/uploadFile', // 文件上传接口
    uploadPayCertificate: baseUrl + 'file/uploadPayCertificate', // 支付证书上传接口v1.0
    getUploadConfig: baseUrl + 'file/getUploadConfig', // 获取上传配置
    getImageFileCheck: baseUrl + 'file/getImageFileCheck', // 检查文件是否存在
    addImageInfo: baseUrl + 'file/addImageInfo', // 图片信息写入数据库
    // 菜单相关
    getUserMenu: baseUrl + 'menu/getUserMenu', // 获取个人权限菜单接口_v1.0
    getAllMenu: baseUrl + 'menu/getAllMenu', // 获取所有菜单接口
    addMenu: baseUrl + 'menu/addMenu', // 添加菜单接口
    updateMenu: baseUrl + 'menu/updateMenu', // 更新菜单接口
    delMenu: baseUrl + 'menu/delMenu', // 删除菜单接口
    menuSort: baseUrl + 'menu/menuSort', // 菜单排序
    getRM: baseUrl + 'roleMenu/getRM', // 获取角色关联的菜单接口
    updateRM: baseUrl + 'roleMenu/updateRM', // 修改角色菜单接口
    // 角色相关
    getRole: baseUrl + 'role/getRole', // 获取所有角色接口_v1.0
    addRole: baseUrl + 'role/addRole', // 添加角色接口_v1.0
    updateRole: baseUrl + 'role/updateRole', // 更新角色接口_v1.0
    delRole: baseUrl + 'role/delRole', // 删除角色接口_v1.0
    // 权限相关
    getPermission: baseUrl + 'permissions/getPermission', // 获取所有权限接口
    addPermission: baseUrl + 'permissions/addPermission', // 添加权限接口
    delPermission: baseUrl + 'permissions/delPermission', // 删除权限接口
    updatePermission: baseUrl + 'permissions/updatePermission', // 修改权限接口
    getRP: baseUrl + 'rolePermissions/getRP', // 获取角色关联的权限接口
    updateRP: baseUrl + 'rolePermissions/updateRP', // 修改角色权限接口
    // 系统配置相关
    getConfig: baseUrl + 'config/getConfig', // 获取系统配置接口_v1.0
    updateConfig: baseUrl + 'config/updateConfig', // 更新系统配置接口_v1.0
    addConfig: baseUrl + 'config/addConfig', // 添加系统配置接口_v1.0
    getConfigArray: baseUrl + 'config/getConfigArray', // 获取系统配置(对象)
    // 账号相关
    getAllUser: baseUrl + 'user/getAllUser', // 获取所有后台用户接口
    addUser: baseUrl + 'user/addUser', // 添加后台账号
    updateUser: baseUrl + 'user/updateUser', // 修改账号信息
    delUser: baseUrl + 'user/delUser', // 删除账号接口
    getUser: baseUrl + 'user/getUser', // 获取账号详情
    editUser: baseUrl + 'user/editUser', // 获取账号详情
    // 会员相关
    getAllMember: baseUrl + 'member/getAllMember', // 获取所有用户接口
    updateMember: baseUrl + 'member/updateMember', // 更新用户信息
    // 商户管理
    storeList: baseUrl + 'store/storeList', // 获取商户列表
    storeDetail: baseUrl + 'store/storeDetail', // 获取商户详情
    addStore: baseUrl + 'store/addStore', // 新增商户
    updateStore: baseUrl + 'store/updateStore', // 更新商户
    deleteStore: baseUrl + 'store/deleteStore', // 删除商户
    changeStoreStatus: baseUrl + 'store/changeStoreStatus', // 启用/禁用商户
    getStoreSimpleList: baseUrl + 'store/getStoreSimpleList', // 搜索商户信息
    // 公告
    getNoticesList: baseUrl + 'notice/getNoticesList', // 获取公告列表
    NoticeDetail: baseUrl + 'notice/NoticeDetail', // 获取公告详情
    addNotice: baseUrl + 'notice/addNotice', // 新增公告
    updateNotice: baseUrl + 'notice/updateNotice', // 更新公告
    deleteNotice: baseUrl + 'notice/deleteNotice', // 删除公告
    // 轮播
    bannerList: baseUrl + 'banner/bannerList', // 轮播列表接口_v1.0
    addBanner: baseUrl + 'banner/addBanner', // 添加轮播接口_v1.0
    updateBanner: baseUrl + 'banner/updateBanner', // 更新轮播接口_v1.0
    bannerDetail: baseUrl + 'banner/bannerDetail', // 轮播详情接口_v1.0
    deleteBanner: baseUrl + 'banner/deleteBanner', // 删除轮播接口_v1.0
    bannerSort: baseUrl + 'banner/bannerSort', // 轮播排序接口_v1.0
    // 活动
    getActivityList: baseUrl + 'activity/getActivityList', // 获取活动列表
    ActivityDetail: baseUrl + 'activity/ActivityDetail', // 获取活动详情
    addActivity: baseUrl + 'activity/addActivity', // 新增活动
    updateActivity: baseUrl + 'activity/updateActivity', // 更新活动
    deleteActivity: baseUrl + 'activity/deleteActivity', // 删除活动
    getActivityApplyList: baseUrl + 'activity/getActivityApplyList', // 获取活动报名列表
    getActivityApplyDetail: baseUrl + 'activity/getActivityApplyDetail', // 获取活动报名详情
    activityStatistics: baseUrl + 'activity/activityStatistics', // 活动报名统计
    getActivityTitleList: baseUrl + 'activity/getActivityTitleList', // 获取活动名称列表
    // 游玩攻略
    addStorePlayStrategy: baseUrl + 'PlayStrategy/addPlayStrategy', // 新增游玩攻略
    updateStorePlayStrategy: baseUrl + 'PlayStrategy/updateStorePlayStrategy', // 更新游玩攻略
    storePlayStrategy: baseUrl + 'PlayStrategy/playStrategyDetail', // 游玩攻略详情
    getPlayStrategyList: baseUrl + 'PlayStrategy/playStrategyList', // 游玩攻略列表
    deletePlayStrategy: baseUrl + 'PlayStrategy/deletePlayStrategy', // 删除游玩攻略
    // 交通指引
    updateStoreTrafficGuidance: baseUrl + 'TrafficGuidance/updateStoreTrafficGuidance', // 新增/更新交通指引
    storeTrafficGuidance: baseUrl + 'TrafficGuidance/storeTrafficGuidance', // 交通指引详情
    // 烧烤秘籍
    updateStoreBarbecueRecipe: baseUrl + 'BarbecueRecipe/updateStoreBarbecueRecipe', // 新增/更新烧烤秘籍
    storeBarbecueRecipe: baseUrl + 'BarbecueRecipe/storeBarbecueRecipe', // 烧烤秘籍详情
    // 基地
    baseInformationList: baseUrl + 'BaseInformation/baseInformationList', // 获取基地介绍列表
    baseInformationDetail: baseUrl + 'BaseInformation/baseInformationDetail', // 获取基地介绍详情
    addBaseInformation: baseUrl + 'BaseInformation/addBaseInformation', // 新增基地介绍
    updateBaseInformation: baseUrl + 'BaseInformation/updateBaseInformation', // 更新基地介绍
    deleteBaseInformation: baseUrl + 'BaseInformation/deleteBaseInformation', // 删除基地介绍
    // 分销设置
    distributionList: baseUrl + 'distributionSet/distributionList', // 商户分销佣金设置列表
    addDistributionset: baseUrl + 'distributionSet/addDistributionset', // 添加分销佣金设置
    updateDistributionSet: baseUrl + 'distributionSet/updateDistributionSet', // 更新分销佣金设置
    // 流水
    commissionLogList: baseUrl + 'CommissionLog/commissionLogList', // 获取佣金流水列表
    commissionLogDetail: baseUrl + 'CommissionLog/commissionLogDetail', // 获取佣金流水详情
    checkWithdrawDeposit: baseUrl + 'CommissionLog/checkWithdrawDeposit', // 提现审批
    // 关于我们
    storeAboutDetail: baseUrl + 'StoreAbout/storeAboutDetail', // 获取关于我们详情
    updateStoreAbout: baseUrl + 'StoreAbout/updateStoreAbout', // 新增/更新关于我们
    // 餐桌
    diningtableList: baseUrl + 'diningTable/diningtableList', // 商户餐桌列表接口
    addDiningTable: baseUrl + 'diningTable/addDiningTable', // 添加餐桌
    deleteDiningTable: baseUrl + 'diningTable/deleteDiningTable', // 删除餐桌
    reserveDiningTable: baseUrl + 'diningTable/reserveDiningTable', // 预留餐桌
    groupDiningTable: baseUrl + 'diningTable/groupDiningTable', // 批量操作预留或者删除
    checkDiningtable: baseUrl + 'diningTable/checkDiningtable', // 检查餐桌7日内预定
    // 菜品
    dishList: baseUrl + 'Dish/dishList', // 获取菜品列表
    dishDetail: baseUrl + 'Dish/dishDetail', // 获取菜品详情
    addDish: baseUrl + 'Dish/addDish', // 新增菜品
    updateDish: baseUrl + 'Dish/updateDish', // 更新菜品
    deleteDish: baseUrl + 'Dish/deleteDish', // 删除菜品
    dishNameList: baseUrl + 'Dish/dishNameList', // 获取菜品名列表
    // 套餐
    mealList: baseUrl + 'Meal/mealList', // 获取套餐列表
    mealDetail: baseUrl + 'Meal/mealDetail', // 获取套餐详情
    addMeal: baseUrl + 'Meal/addMeal', // 新增套餐
    updateMeal: baseUrl + 'Meal/updateMeal', // 更新套餐
    deleteMeal: baseUrl + 'Meal/deleteMeal', // 删除套餐
    // 预约时间
    updateStoreReservationTime: baseUrl + 'ReservationTime/updateStoreReservationTime', // 更新可预约时间段
    storeReservationTime: baseUrl + 'ReservationTime/storeReservationTime', // 获取可预约时间段列表
    // 订单
    orderList: baseUrl + 'order/orderList', // 预约订单列表
    deleteOrder: baseUrl + 'order/deleteOrder', // 预约订单删除
    editOrder: baseUrl + 'order/editOrder', // 预约订单改签
    orderDetail: baseUrl + 'order/orderDetail', // 预约订单详情
    refundOrder: baseUrl + 'order/refundOrder', // 预约订单退款
    // 优惠券
    originalCouponNum: baseUrl + 'coupon/originalCouponNum', // 获取商户原始券数量
    bossPublishedCouponList: baseUrl + 'coupon/bossPublishedCouponList', // 商户发布的券列表
    publishCoupon: baseUrl + 'coupon/publishCoupon', // 发布券接口
    deleteCoupon: baseUrl + 'coupon/deleteCoupon', // 删除优惠券
    pushOriginalCoupon: baseUrl + 'coupon/pushOriginalCoupon', // 发放原始券
    // 平台设置
    updateSystemData: baseUrl + 'SystemData/updateSystemData', // 新增/更新平台设置
    systemDataDetail: baseUrl + 'SystemData/getDataDetail', // 平台设置详情
    // 平台设置
    getDishTypeList: baseUrl + 'DishType/getDishTypeList', // 获取分类列表
    allDishTypeList: baseUrl + 'DishType/allDishTypeList', // 获取分类列表（全部）
    dishTypeDetail: baseUrl + 'DishType/dishTypeDetail', // 获取分类详情
    addDishType: baseUrl + 'DishType/addDishType', // 新增分类
    updateDishType: baseUrl + 'DishType/updateDishType', // 更新分类
    deleteDishType: baseUrl + 'DishType/deleteDishType', // 删除分类

    // aaa: baseUrl + 'bbb/aaa', //
}


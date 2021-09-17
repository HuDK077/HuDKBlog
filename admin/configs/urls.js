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

    // 工艺师类型相关
    designCraftsTypeList: baseUrl + 'designCraftsType/designCraftsTypeList', // 设计师、工艺师类型列表
    allDesignCraftsTypeList: baseUrl + 'designCraftsType/allDesignCraftsTypeList', // 设计师、工艺师类型列表
    addDesignCraftsType: baseUrl + 'designCraftsType/addDesignCraftsType', // 创建设计师、工艺师类型
    updateDesignCraftsType: baseUrl + 'designCraftsType/updateDesignCraftsType', // 更新设计师、工艺师类型
    deleteDesignCraftsType: baseUrl + 'designCraftsType/deleteDesignCraftsType', // 删除设计师、工艺师类型
    // 工艺师相关
    craftsmanList: baseUrl + 'craftsman/craftsmanList', // 工艺师列表
    craftsmanDetail: baseUrl + 'craftsman/craftsmanDetail', // 工艺师列表
    checkCraftsman: baseUrl + 'craftsman/checkCraftsman', // 审核工艺师
    deleteCraftsman: baseUrl + 'craftsman/deleteCraftsman', // 删除工艺师
    recommendedCraftsman: baseUrl + 'craftsman/recommendedCraftsman', // 推荐
    // 作品分类
    getCategoryList: baseUrl + 'workCategory/getCategoryList', // 作品分类列表
    addCategory: baseUrl + 'workCategory/addCategory', // 添加作品分类
    updateCategory: baseUrl + 'workCategory/updateCategory', // 更新作品分类
    deleteCategory: baseUrl + 'workCategory/delete', // 删除作品分类
    // 工艺师作品

    // 工艺师作品
    workList: baseUrl + 'craftsmanWork/workList', // 工艺师作品列表
    workDetail: baseUrl + 'craftsmanWork/workDetail', // 工艺师作品详情
    checkCraftswork: baseUrl + 'craftsmanWork/checkCraftswork', // 审核工艺师作品
    deleteWork: baseUrl + 'craftsmanWork/deleteWork', // 删除工艺师作品


    // 论文
    paperList: baseUrl + 'paper/paperList', // 论文列表
    paperDetail: baseUrl + 'paper/paperDetail', // 论文详情
    deletePaper: baseUrl + 'paper/deletePaper', // 删除论文
    checkPaper: baseUrl + 'paper/checkPaper', // 审核论文

    // 轮播图
    bannerList: baseUrl + 'banner/bannerList', // 轮播列表
    addBanner: baseUrl + 'banner/addBanner', // 添加轮播
    updateBanner: baseUrl + 'banner/updateBanner', // 更新轮播
    deleteBanner: baseUrl + 'banner/deleteBanner', // 删除轮播

    // 产权相关
    applyLists: baseUrl + 'Property/applyLists', // 证书申请列表
    applyDetails: baseUrl + 'Property/applyDetails', // 证书申请详情
    passApply: baseUrl + 'Property/passApply', // 更新证书申请状态
    rightsProtectionList: baseUrl + 'Property/rightsProtectionList', // 产权维权列表
    protectionDetails: baseUrl + 'Property/protectionDetails',
    changeProtectionStatus: baseUrl + 'Property/changeProtectionStatus', // 变更产权维权受理状态


    // 新闻资讯
    newsList: baseUrl + 'News/newsList', // 新闻资讯列表
    addNews: baseUrl + 'News/addNews', // 新增新闻资讯
    updateNews: baseUrl + 'News/updateNews', // 更新新闻资讯
    deleteNews: baseUrl + 'News/deleteNews', // 删除新闻资讯
    newsDetail: baseUrl + 'News/newsDetail', // 获取新闻资讯详情
    checkNews: baseUrl + 'News/checkNews', // 审核用户展览

    // 设计师相关
    designList: baseUrl + 'designer/getAllDesigner', // 设计师列表
    designTitleList: baseUrl + 'designer/getDesignerTitle', // 设计师名称列表
    designDetail: baseUrl + 'designer/getDesigner', // 设计师列表
    changeDesignState: baseUrl + 'designer/changeDesignerStatus', // 启用、禁用设计师
    checkDesign: baseUrl + 'designer/checkApply', // 审核设计师
    // deleteDesign: baseUrl + 'design/deleteDesign', // 删除设计师

    // 设计师图稿
    artworkList: baseUrl + 'designerArtwork/designerArtworkList', // 设计师图稿列表
    artworkDetail: baseUrl + 'designerArtwork/designerArtworkDetail', // 设计师图稿详情
    checkArtwork: baseUrl + 'designerArtwork/checkArtwork', // 审核设计师图稿
    // aaa: baseUrl + 'bbb/aaa', //

    // 商品相关
    goodsList: baseUrl + 'goods/goodsList', // 商品列表接口
    allGoodsIdTitle: baseUrl + 'goods/allGoodsIdTitle', // 搜索商品列表接口
    createGoods: baseUrl + 'goods/createGoods', // 创建商品接口
    goodsDetail: baseUrl + 'goods/goodsDetail', // 商品详情接口
    updateGoods: baseUrl + 'goods/updateGoods', // 更新商品
    deleteGoods: baseUrl + 'goods/deleteGoods', // 商品删除接口
    goodsRecommend: baseUrl + 'goods/goodsRecommend', // 商品推荐接口
    categoryList: baseUrl + 'goods/CategoryList', // 产品分类接口
    changeGoodsStatus: baseUrl + 'goods/changeGoodsStatus', // 上下架
    addGoodsStock: baseUrl + 'goods/addGoodsStock', // 添加库存

    // 商品分类
    getGoodsCategoryList: baseUrl + 'Category/getCategoryList', // 产品分类列表
    addGoodsCategory: baseUrl + 'Category/addCategory', // 添加产品分类
    updateGoodsCategory: baseUrl + 'Category/updateCategory', // 更新产品分类
    deleteGoodsCategory: baseUrl + 'Category/deleteCategory', // 删除产品分类

    // 泥料库相关
    mudList: baseUrl + 'MudCategory/getMudList', // 泥料库列表
    mudDetail: baseUrl + 'MudCategory/mudDetail', // 泥料库详情
    editMud: baseUrl + 'MudCategory/updateMud', // 编辑泥料库
    addMud: baseUrl + 'MudCategory/addMud', // 添加泥料库
    deleteMud: baseUrl + 'MudCategory/deleteMud', // 删除泥料库

    // 摹古馆相关
    ancientPotList: baseUrl + 'ancientPot/getPotList', // 摹古馆列表
    ancientPotDetail: baseUrl + 'ancientPot/ancientPotDetail', // 摹古馆详情
    editAncientPot: baseUrl + 'ancientPot/updatePot', // 编辑摹古馆
    addAncientPot: baseUrl + 'ancientPot/addPot', // 添加摹古馆
    deleteAncientPot: baseUrl + 'ancientPot/deletePot', // 删除摹古馆

    // 摹古馆相关
    budgetList: baseUrl + 'budget/getBudgetList', // 预算列表
    updateBudget: baseUrl + 'budget/updateBudget', // 编辑预算
    addBudget: baseUrl + 'budget/addBudget', // 添加预算
    deleteBudget: baseUrl + 'budget/deleteBudget', // 删除预算

    // 人物专栏
    characterList: baseUrl + 'character/characterList', // 获取所有人物
    characterDetail: baseUrl + 'character/characterDetail', // 获取人物信息详情
    addCharacter: baseUrl + 'character/addCharacter', // 新增人物
    updateCharacter: baseUrl + 'character/updateCharacter', // 更新人物
    deleteCharacter: baseUrl + 'character/deleteCharacter', // 删除人物
    characterSort: baseUrl + 'character/characterSort', // 人物排序

    // 紫砂百科
    encyclopediaList: baseUrl + 'encyclopedia/encyclopediaList', // 获取紫砂百科
    encyclopediaDetail: baseUrl + 'encyclopedia/encyclopediaDetail', // 获取紫砂百科详情
    addEncyclopedia: baseUrl + 'encyclopedia/addEncyclopedia', // 新增紫砂百科
    updateEncyclopedia: baseUrl + 'encyclopedia/updateEncyclopedia', // 更新紫砂百科
    deleteEncyclopedia: baseUrl + 'encyclopedia/deleteEncyclopedia', // 删除紫砂百科
    homepageEncyclopedia: baseUrl + 'encyclopedia/homepageEncyclopedia', // 首页显示

    //App设置
    getAboutTaoduwang: baseUrl + 'SystemData/getDataDetail',   //获取配置信息
    updateAboutTaoduwang: baseUrl + 'SystemData/updateSystemData', //更新配置信息
}


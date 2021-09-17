#! /bin/bash
p_path=$1   #项目路径
tag=$2      #镜像tag
con=order   #容器名称
server=zly  #服务器代号
if [ "${p_path}" == "" ] | [ "${tag}" == "" ]; then
    echo "使用方式 ./baler.sh [需要复制到容器内项目的路径] [镜像tag]";
    exit;
fi
echo "更新项目到最新..."
cd "${p_path}"
git pull
echo "编译前端文件..."
cd "${p_path}/admin" && yarn && yarn build;
echo "删除node缓存..."
rm -rf node_modules;
echo "正在拷贝文件到容器，路径:${con}:/var/www/";
docker cp "${p_path}" "${con}":/var/www/;
echo "提交镜像中..."
docker commit "${con}" "registry.cn-shanghai.aliyuncs.com/crackersw/${con}:${tag}";
echo "推送镜像到仓库中..."
docker push "registry.cn-shanghai.aliyuncs.com/crackersw/${con}:${tag}";
echo "下面是到服务器上面的操作.."
ssh "${server}" -tt << remotessh
    docker pull "registry.cn-shanghai.aliyuncs.com/crackersw/${con}:${tag}"
    docker images
    docker service update --image "registry.cn-shanghai.aliyuncs.com/crackersw/${con}:${tag}" ${con}
    exit
remotessh

echo "删除本地镜像...";
docker rmi "registry.cn-shanghai.aliyuncs.com/crackersw/${con}:${tag}";
echo "恢复node缓存...";
cd "${p_path}/admin" && yarn;

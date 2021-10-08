## 安装
* 根目录执行
```
composer install
```
## 启动
* 根目录 运行
```
php bin/laravels start -d
```
* 热更新
```
apt install inotify-tools
```
* 项目根目录-监听app目录
```
./bin/inotify ./app
```


##前端编译
* 第一步

进入到项目的admin目录执行 建议使用
```
yarn install 或者 npm install
```
* 第二步 
复制.env 配置文件
```
cp .env.example .env
```
* 第三步
```
yarn build 或者 npm build
```

## nginx配置文件
```nginx
# gzip on;
# gzip_min_length 1024;
# gzip_comp_level 2;
# gzip_types text/plain text/css text/javascript application/json application/javascript application/x-javascript application/xml application/x-httpd-php image/jpeg image/gif image/png font/ttf font/otf image/svg+xml;
# gzip_vary on;
# gzip_disable "msie6";

map $http_upgrade $connection_upgrade {
    default upgrade;
    ''      close;
}

upstream swoole {
    server 127.0.0.1:5200 weight=5 max_fails=3 fail_timeout=30s;
    keepalive 64;
}
server {
    listen 83;
    # 别忘了绑Host
    server_name order.wisdomyun.cn;
    client_max_body_size 100M;
    root /var/www/order/resources/views/admin;
    access_log /root/nginx/logs/order.wisdomyun.cn.access.log;
    error_log /root/nginx/logs/order.wisdomyun.cn.error.log;
    autoindex off;
    index index.html index.htm;
    # Nginx处理静态资源(建议开启gzip)，LaravelS处理动态资源。
    location / {
        try_files $uri $uri/ /index.html;
    }
    location ~ "(admin|api)" {
        try_files $uri @laravels;
        proxy_http_version 1.1; # 后端配置支持HTTP1.1，必须配
        proxy_set_header Connection "";   # 后端配置支持HTTP1.1 ,必须配置。
    }
    # 当请求PHP文件时直接响应404，防止暴露public/*.php
    #location ~* \.php$ {
    #    return 404;
    #}
    # Http和WebSocket共存，Nginx通过location区分
    # !!! WebSocket连接时路径为/ws
    # Javascript: var ws = new WebSocket("ws://laravels.com/ws");
    location /ws {
        # proxy_connect_timeout 60s;
        # proxy_send_timeout 60s;
        # proxy_read_timeout：如果60秒内被代理的服务器没有响应数据给Nginx，那么Nginx会关闭当前连接；同时，Swoole的心跳设置也会影响连接的关闭
        # proxy_read_timeout 60s;
        client_max_body_size 100m;
        proxy_http_version 1.1;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Real-PORT $remote_port;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header Server-Protocol $server_protocol;
        proxy_set_header Server-Name $server_name;
        proxy_set_header Server-Addr $server_addr;
        proxy_set_header Server-Port $server_port;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection $connection_upgrade;
        proxy_pass http://swoole;
    }
    location @laravels {
        client_max_body_size 100m;
        # proxy_connect_timeout 60s;
        # proxy_send_timeout 60s;
        # proxy_read_timeout 120s;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Real-PORT $remote_port;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header Server-Protocol $server_protocol;
        proxy_set_header Server-Name $server_name;
        proxy_set_header Server-Addr $server_addr;
        proxy_set_header Server-Port $server_port;
        # “swoole”是指upstream
        proxy_pass http://swoole;
    }
}

```

##supervisor 进程管理配置
```
[program:swooleserver]
command= php /var/www/order/bin/laravels start              ; 程序启动命令
autostart=true                           ; 在supervisord启动的时候也自动启动
startsecs=10                             ; 启动10秒后没有异常退出，就表示进程正常启动了，默认为1秒
autorestart=true                         ; 程序退出后自动重启,可选值：[unexpected,true,false]，默认为unexpected，表示进程意外杀死后才重启
startretries=3                           ; 启动失败自动重试次数，默认是3
user=root                              ; 用哪个用户启动进程，默认是root
priority=999                             ; 进程启动优先级，默认999，值小的优先启动
redirect_stderr=true                     ; 把stderr重定向到stdout，默认false
stdout_logfile_maxbytes=20MB                  ; stdout 日志文件大小，默认50MB
stdout_logfile_backups = 20                   ; stdout 日志文件备份数，默认是10
; stdout 日志文件，需要注意当指定目录不存在时无法正常启动，所以需要手动创建目录（supervisord 会自动创建日志文件）
stdout_logfile=/etc/supervisor/logs/swooleserver.logs
stopasgroup=false                         ;默认为false,进程被杀死时，是否向这个进程组发送stop信号，包括子进程
killasgroup=false                         ;默认为false，向进程组发送kill信号，包括子进程
```

##自动生成API文档
```
#生成命令
./auto_api_doc.sh
```
* 使用注释 打开粘贴

Preferences->Editor->File and Code Templates->Includes->PHP Function Doc Comment
```php
/**
 * showdoc
 * @catalog 测试文档/用户相关
 * @title ${CARET} 
 * @description 
 * @method POST
 * @url http://xx.com/api//${NAME}
 * @header token 必选 string 设备token 
 * @param xx 必选 string 用户名 
 * @return 
 * @return_param error_code int 返回码
 * @return_param message string 返回说明
 * @return_param name string 用户名称
 * @remark 这里是备注信息
 * @number 99
 * @DATE: ${DATE}
 * @TIME: ${TIME}
${THROWS_DOC}
*/
```



##数据迁移

* 从数据库创建数据迁移结构
```
php artisan migrate:generate #创建所有表的迁移
php artisan migrate:generate table1,table2,table3,table4,table5  #选择表迁移
php artisan migrate:generate --ignore="table3,table4,table5" #忽略表迁移
```

* 从数据库创建表数据文件
```
php artisan iseed my_table #生成单个表数据迁移文件
php artisan iseed my_table,another_table #生成多个表数据迁移文件
php artisan iseed my_table --classnameprefix=Customized  #给生成的表添加前缀
php artisan iseed admin_config,admin_menu,admin_permissions,admin_role_menu,admin_role_permissions,admin_role_users,admin_roles,admin_users
```

* 恢复数据库结构以及所有数据
```
php artisan migrate:fresh
php artisan db:seed
#或者这样使用 
php artisan migrate:fresh --seed
```

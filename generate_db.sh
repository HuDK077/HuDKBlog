#!/bin/bash
#
#
#
api_key=$1
api_token=$2
db_host=$3
db_port=$4
db_user=$5
db_password=$6
db_name=$7


host=${db_host}				#数据库所在地址。默认是localhost
port=${db_port}						#数据库所在端口。默认是3306
user=${db_user}   	 			#数据库的用户名
password=${db_password} 			#密码
db=${db_name} 					#要同步的数据库名。要同步多个db可以将本脚本复制多份
api_key=${api_key} 			#api_key
api_token=${api_token} 	#api_token
cat_name="数据字典" 	#可选。如果想把生成的文档都放在项目的子目录下，则这里填写子目录名。
url="https://www.showdoc.com.cn/server/?s=/api/open/updateDbItem" #可选。同步到的url。如果是使用www.showdoc.com.cn ，则不需要再改此项。如果是部署私有版版showdoc，请改此项为http://xx.com/server/index.php?s=/api/open/updateDbItem 。其中xx.com为你的部署域名
#
#
#
#
#
#
export MYSQL_PWD=${password}
COMMAND="set names utf8;select TABLE_NAME ,TABLE_COMMENT from tables where TABLE_SCHEMA ='${db}'  "
declare table_info=`mysql -h${host} -P${port} -u${user}  --show-warnings=false -D information_schema -e "${COMMAND}" `
#echo $table_info
COMMAND="set names utf8;select TABLE_NAME ,COLUMN_NAME, COLUMN_DEFAULT ,IS_NULLABLE ,COLUMN_TYPE ,COLUMN_COMMENT from COLUMNS where TABLE_SCHEMA ='${db}'  "
declare table_detail=`mysql -h${host} -P${port} -u${user}  --show-warnings=false -D information_schema -e "${COMMAND}" `
#echo $table_detail
table_info2=${table_info//&/_this_and_change_}
table_detail2=${table_detail//&/_this_and_change_}
curl -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'  "${url}" --data-binary @- <<CURL_DATA
from=shell&table_info=${table_info2}&table_detail=${table_detail2}&api_key=${api_key}&api_token=${api_token}&cat_name=${cat_name}
CURL_DATA
export MYSQL_PWD=""


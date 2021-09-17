#! /bin/bash
#
# 文档说明： https://www.showdoc.com.cn/page/741656402509783
#
api_key="9b61d53db50acb91af7ceb6a15d3530c1959446915" 			#api_key
api_token="3655fa9c0856898dc58116ecbf2f00c41498538853" 	#api_token
db_host="127.0.0.1"
db_port=3306
db_user="root"
db_password="rootroot"
db_name="blog"
url="https://showdoc.jobz.xin/server/?s=/api/open/fromComments" #同步到的url。使用www.showdoc.com.cn的不需要修改，使用私有版的请修改
#
#
#
#
#
# 如果第一个参数是目录，则使用参数目录。若无，则使用脚本所在的目录。
if [[ -z "$1" ]] || [[ ! -d "$1" ]] ; then #目录判断，如果$1不是目录或者是空，则使用当前目录
	curren_dir=$(dirname $(readlink -f $0))
else
	curren_dir=$(cd $1; pwd)
fi
#echo "$curren_dir"
# 递归搜索文件
searchfile() {

	old_IFS="$IFS"
	IFS=$'\n'            #IFS修改
	for chkfile in $1/*
	do
		filesize=`ls -l $chkfile | awk '{ print $5 }'`
		maxsize=$((1024*1024*1))  # 1M以下的文本文件才会被扫描
		if [[ -f "$chkfile" ]] &&  [ $filesize -le $maxsize ] && [[ -n $(file $chkfile | grep text) ]] ; then # 只对text文件类型操作
			echo "正在扫描 $chkfile"
			result=$(sed -n -e '/\/\*\*/,/\*\//p' $chkfile | grep showdoc) # 正则匹配
		if [ ! -z "$result" ] ; then
					txt=$(sed -n -e '/\/\*\*/,/\*\//p' $chkfile)
					#echo "sed -n -e '/\/\*\*/,/\*\//p' $chkfile"
					#echo $result
					if  [[ $txt =~ "@url" ]] && [[ $txt =~ "@title" ]]; then
						echo -e "\033[32m $chkfile 扫描到内容 , 正在生成文档 \033[0m "
						txt2=${txt//&/_this_and_change_}
						# 通过接口生成文档
curl -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'  "${url}" --data-binary @- <<CURL_DATA
from=shell&api_key=${api_key}&api_token=${api_token}&content=${txt2}
CURL_DATA
					fi
			fi
		fi

		if [[ -d $chkfile ]] ; then
			searchfile $chkfile
		fi
	done
	IFS="$old_IFS"
}


#执行搜索
searchfile './app/Http/Controllers'

echo "开始生成数据库字典..."
sh ./generate_db.sh "${api_key}" "${api_token}" "${db_host}" "${db_port}" "${db_user}" "${db_password}" "${db_name}"


#
#sys=$(uname)
#if [[ $sys =~ "MS"  ]] || [[ $sys =~ "MINGW"  ]] || [[ $sys =~ "win"  ]] ; then
#	read -s -n1 -p "按任意键继续 ... " # win环境下为照顾用户习惯，停顿一下
#fi


#!/usr/bin/python
# # -*- utf-8 -*-
import sys
from urllib import request

def writeFile(url):
  url = "http:" + url
  print(url)
  css = request.urlopen(url).read().decode("utf8")
  fo = open("./assets/css/iconfont.css", "w")
  fo.write(css)
  fo.flush()
  fo.close()
  print("替换完成")


url = sys.argv[1]
if (url.isspace()):
  print("url地址为空")
else:
  writeFile(url)




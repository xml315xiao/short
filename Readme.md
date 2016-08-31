# 短链接生成文档 （http://oo.cc )

Generates a shorter URI  Documentation.

##请求URL地址
[http://oo.cc/ajax/appshorter.php](http://oo.cc/ajax/appshorter.php)

## 请求参数 （GET）

>### 以下两参数是必须传递， 类型为字符串
 * url   输入要转换的原始长链接   
 * token  校验码 (校验规则： 长链接地址后面跟字符串 “ V0utjFzqmKmXAYGonmrM9 ” 两次MD5后的值）

> 示例：

> url 为 "http://www.google.com"  

> 那token为 md5(md5("http://www.google.com" + "V0utjFzqmKmXAYGonmrM9")) 生成的值  “62dfee8d1a7dd5b9cf28656a73345978”


## 返回结果 （JSON ）

 * **成功示例**
> http://oo.cc/ajax/appshorter.php?url=http://www.google.com&token=62dfee8d1a7dd5b9cf28656a73345978

>{"success":true,"short_url":"http://oo.cc/bktC9B","long_url":"http://www.google.com"}

> success:   true 

> short_url : 返回生成后的短链接

> long_url：返回原始长链接
 
 * **失败示例**
> http://oo.cc/ajax/appshorter.php?url=http://www.google.com&token=62dfee8d1a7dd5b9cf28656

> {"success":false,"error":"校验码异常","long_url":"http://www.google.com"}

> success: 　false
>  
> error : 返回错误异常信息
 
> long_url：返回原始长链接


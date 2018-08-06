# Sign 加签

### 插件介绍

请求参数加签验证

### 配置 Sign
如果你使用的是md5加签方式请在config/app.php文件中，添加 sign_key 配置。如果你使用的是Rsa加签方式请在config/app.php文件中，添加app.sign_rsa_private_key和app.sign_rsa_public_key配置

### 配置中间件
在app/Http/Kernel.php文件中，您需要把 'sign' => \luffyzhao\laravelTools\Middleware\VerifySign::class, 添加到$routeMiddleware属性中

### 使用

```php
<?php

Route::group(
    ['middleware' => 'sign:api'],
    function($route){
        Route::get('xxx', 'xxx');
    }
);
```


##### 加签方式 

  `rsa` 和 `md5` 

##### 参数排序

* 准备参数
* 添加 `timestamp` 字段
* 然后按照字段名的 ASCII 码从小到大排序（字典序）
* 生成 `url` 参数串
* 拼接 key 然后 md5 或者 rsa
        

如下所示：

```
{
	"name": "4sd65f4asd5f4as5df",
	"aimncm": "54854185",
	"df4": ["dfadsf"],
	"dfsd3": {
		"a": {
			"gfdfsg": "56fdg",
			"afdfsg": "56fdg"
		}
	}
}
```
排序后：
```
{
	"aimncm": "54854185",
	"df4": ["dfadsf"],
	"dfsd3": {
		"a": {
			"afdfsg": "56fdg",
			"gfdfsg": "56fdg"
		}
	},
	"name": "4sd65f4asd5f4as5df",
	"timestamp": "2018-05-29 17:25:34"
}
```
生成url参数串：

> aimncm=54854185&df4[0]=dfadsf&dfsd3[a][afdfsg]=56fdg&dfsd3[a][gfdfsg]=56fdg&name=4sd65f4asd5f4as5df&timestamp=2018-05-29 17:25:34

拼接 key :

> aimncm=54854185&df4[0]=dfadsf&dfsd3[a][afdfsg]=56fdg&dfsd3[a][gfdfsg]=56fdg&name=4sd65f4asd5f4as5df&timestamp=2018-05-29 17:25:34base64:Z9I7IMHdO+T9qD3pS492GWNxNkzCxinuI+ih4xC4dWY=

md5加密

> ddab78e7edfe56594e2776d892589a9c


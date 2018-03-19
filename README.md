# 前端项目技术栈
`nodejs+swig+less+Uglify`

# 安装nodejs

# 安装fute
一个类似于gulp的前端构建插件。
封装了less,swig编译模块，拥有js,css合并压缩，批量添加注释，重命名，内容替换，浏览器实时刷新等功能。
``` shell
npm i fute -g
```

# 安装phpstudy
1. 版本切换为 nginx

2. 修改nginx配置目录下 vhosts-ini
``` shell
server {
	listen       80;
	server_name  html.udo.com;
	root   "D:\workspace\udo\html\dist";
	location / {
		proxy_pass http://localhost:3000;
		proxy_redirect default;
	}
location /api {
	rewrite  ^/api/(.*)$ /$1 break;
	proxy_pass   https://www.udo.com;
}
```
3. 修改本地hosts 文件
html.udo.com  127.0.0.1

# 编译项目

1. 切换至项目目录
```shell
cd path...
```

2. 本地调式 http://html.udo.com
```shell
fute
```

3. 上线发布
> 会自动压缩所有静态文件
```shell
fute pro
```

4. 清空编译目录
```shell
fute clean
```

# 项目结构
root
|-- src // 源文件
|   |-- css // less文件
|       |-- @app // 页面样式
|       |-- @inc // 公共样式
|       |-- @lib // 组件样式
|       |-- @ui //  基础ui样式
|       |-- style.less  // 样式文件入口
|   |-- fonts // 字体文件
|   |-- images // 图片文件
|   |-- js // javascript文件
|       |-- @app // 页面样式
|       |-- @ext // 页面样式
|       |-- @lib // 页面样式
|       |-- app.js // 页面js入口
|       |-- core.js // 文件加载器
|       |-- ext.js // 扩展文件入口
|       |-- lib.js // 库文件入口
|   |-- html // html模板
|       |-- @inc // 公共模板
|       |-- ... // 模块
|       |-- index.tpl // 首页模板
|   
|-- dist // 编译打包目录
|-- package.json // 项目配置
|-- index.js // fute 任务文件

# 项目配置 package.json 

``` json
{
  "name": "融都网", // 应用名称
  "version": "0.0.1", // 版本
  "description": "html design", //描述
  "main": "index.js",
  "scripts": {},
  "keywords": [
    "fute"
  ],
  "author": "YangFei",
  "license": "MIT",
  "fute": { // 应用配置
    "port": 3000, // 本地调试端口
    "watch": true, // 自动监听文件
    "openBrowser": false, // 自动打开浏览器
    "reloadBrowser": true, // 自动刷新浏览器
    "src": "src", // 源文件目录
    "dist": "dist", // 编译文件目录
    "statics": "static", // 静态文件目录
    "filter": "@", // 过滤文件前缀
    "minifix": ".mini", // 文件压缩后缀标识
    "ext": { // 用于swig模板调用
	  "title":"静态测试",
	  "navigation": []
    }
  }
}
```

# 页面参数配置 html/@inc/config.tpl 
``` json
[%-
set config = {
	top_nav:[ // 顶部导航
		{
			"title": "首页", //页面标题
			"flag":"index" //页面标识
		},
		...
	],
	user_nav:[ // 用户中心导航
		{
			"title": "我的资料",
			"flag":"profile"
		},
		...
	],
	...
}	
%]
```

# 全站JS配置 js/@app/common/config.js

``` js
module.exports = {
		base:base,
		api : {
			// 用户注册
			reg: api + 'user/register.php?act=is_register',
			
			// 用户退出
			logout: api + 'user/logout.php',
			
			// 验证电话是否注册
			isTel:api + 'user/info.php?act=is_tel',

			// 验证昵称是否存在
			isNickname:api + 'user/info.php?act=is_nickname',
			
			// 验证验证码
			isSms:api + 'user/checkSMS.php?act=check_sms',
			
			// 验证审核状态
			isExamine:api + 'user/become.php?act=results',
			
			// 重新申请
			re_results:api + 'user/become.php?act=re_results',

			// 密码登录
			pass:api + 'user/login.php?act=is_password',
			
			// 短信登录
			sms:api + 'user/login.php?act=is_verification',
			
			// 获取验证码
			getVerCode: api + 'sms/sms.php',
			
			// 用户名片照
			upload: api + 'user/become.php?act=upload',

			// 申请领袖
			toleader: api + 'user/become.php?act=submit',
			
			// 找回密码
			checkUser:api + 'user/checkUser.php',
			
			// 获取图片验证码
			verification: api + 'user/checkUser.php?act=Verification',
			
			...
		}
	};
```
# PHP留言板
## 重要提示
- 应用创建后，默认会有一个管理员用户（用户名和密码均为`root`）。目前设计为只有这一个管理员用户，若投递生产环境，请及时修改密码！
- 要求中的“文件上传”功能，请见 https://cy.bail.asia/fileshare ，非PHP+mysql开发，且暂不开源。
- 本人实装链接： http://srv.bail.asia:8080/

## 软件特色
- 使用面向对象开发
- “使用Bail的重邮内部小站登录”（仅限本人实装，暂不提供对外环境）
- 防csrf、xss
- 使用refresh_token实现三天内免登录
- 将公聊、私聊、评论使用同一逻辑进行处理
- 使用链表结构组织数据库
- 消息的锚点跳转

## 主要功能
- 用户的注册和登录
- 三天内免登录
- 用户发表留言
- 对留言的点赞、评论、编辑、删除；对评论的上述功能；递归评论（可能会出现界面问题）
- 上传头像、查看头像、修改密码（不支持修改用户名，但可以扩展用户昵称功能）
- 用户间添加好友和私聊
- 管理员对任意消息的增删查改
- 消息的锚点跳转

## 尚未实现的功能
- 好友验证
- 管理员控制用户
- 群聊功能
- 用户注销
- 更多管理员用户
- 在防xss的前提下允许用户发送富文本、链接、图片等
- 其他更多功能

# 附录
## 目录结构
```
php_chatboard/
├── img/
│   ├── comment.jpeg            评论按钮图标
│   ├── delete.png              删除按钮图标
│   ├── edit.png                编辑按钮图标
│   ├── headphoto/              头像目录
│   │   └── default.svg         默认头像文件
│   ├── liked.png               已点赞按钮图标
│   └── like.png                点赞按钮图标
├── test/                       存放测试漏洞和
└── src/                        存放源码。将下面的所有文件部署到/var/www/html/
    ├── .test.php               （！请勿试图直接访问！）后门文件，只有在libconst.php中的DEBUG值为true且用户传入的$_GET['psw']与DEBUG_PSW相同的时候才允许执行指令，否则会直接卡死浏览器（curl勿扰）。建议使用test/test.py来使用
    ├── add_friend.php          添加好友
    ├── chatboard.php           留言面板，用于呈现留言
    ├── chhead.php              更改头像
    ├── chpsw.php               更改密码
    ├── delmsg.php              删除消息
    ├── index.php               首页，用于根据用户登录状况重定向
    ├── libclass.php            类型库，定义了留言板中用到的各种类
    ├── libconst.php            常量库，定义了各种常量
    ├── libcsrftoken.php        csrf_token的生成和校验相关的库
    ├── libmust_method.php      限定某个文件请求方式的库
    ├── libneed_login.php       限定某个文件只能被登录用户访问的库
    ├── libsql.php              数据库操作相关库
    ├── libverify_args.php      要求用户必须传递某些参数的库，防止因为用户不传入参数而导致的空安全问题
    ├── like.php                处理点赞操作
    ├── login.html              登录页面
    ├── login.php               处理登录操作
    ├── logout.php              处理登出操作
    ├── post_comment.php        处理发布留言操作
    ├── profile.php             呈现个人信息和相关操作
    ├── refresh.php             校验并下发refresh_token，维持用户登录状态
    ├── register.html           注册页面
    ├── register.php            处理注册操作
    └── wwwcqupt-login.php      处理从“Bail的重邮内部小站”进行登录的操作
```

## dockerhub链接
https://crpi-kvvb2qrgbcj5a6vu.cn-chengdu.personal.cr.aliyuncs.com/bailplus/php_chatboard

## git提交记录（截至本README.md）
```git log
commit 352e7ad37363c30ca644c01d2a9a6211ee5cc47b
Author: Bail <2915289604@qq.com>
Date:   Tue Jan 28 15:41:30 2025 +0800

    修改了直接运行lib时的行为

commit 0b4b0366f90562e5d3e90c1c6e83eaa2da36369d
Author: Bail <2915289604@qq.com>
Date:   Tue Jan 28 11:36:46 2025 +0800

    修改诸多问题

commit 995429e09f475190e50bd5fe6ce585554946ab5f
Author: Bail <2915289604@qq.com>
Date:   Mon Jan 27 20:10:40 2025 +0800

    修复了refresh_token的cookie参数错误
    优化了留言面板提交按钮的样式

commit 2c87e7dc112f50cf06db7c0ad84663c0599cc35b
Author: Bail <2915289604@qq.com>
Date:   Mon Jan 27 17:41:37 2025 +0800

    增加了编辑消息的功能,以及管理员编辑任意消息的特权

commit a190314c441dee4384f3e6fe52820a4df63c8c17
Author: Bail <2915289604@qq.com>
Date:   Mon Jan 27 16:47:07 2025 +0800

    增加管理员能力:进入任意聊天室并进行任意操作

commit e0fbb56e84b048cfaaf43a1bcbdfa6bf97b0aacd
Author: Bail <2915289604@qq.com>
Date:   Mon Jan 27 16:35:18 2025 +0800

    新增了管理员用户root,能力为删除他人消息

commit 7dd3dfcb233584d81e125b1d92777c730bbb7c5a
Author: Bail <2915289604@qq.com>
Date:   Mon Jan 27 16:27:25 2025 +0800

    增加了“三天内自动登录”选项
    
    Signed-off-by: Bail <2915289604@qq.com>

commit 3652829c1913aac62b2df52c4b2efea5e1ed9ebc
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 20:27:24 2025 +0800

    增加了删除消息功能

commit bd10490176c22ce8930c1da08eb305fe6bcb9281
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 19:15:15 2025 +0800

    更改了数据结构,新增抽象类Hangable,用于准备删除消息

commit ac32bc6e15a5852184ea57a49af3bc6461121f23
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 16:41:59 2025 +0800

    新版防xss（全局,但之后开发仍需注意）

commit 96fd9224180c042c53b009c84eb3f8264bc7c7e7
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 15:31:53 2025 +0800

    修复了用户名处的xss漏洞
    消息操作按钮变为图片

commit 9f1947fe6e185172464a822b6f06f5517ceade3c
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 12:56:42 2025 +0800

    更新了评论的数据结构

commit c34ead02de591f30c05331f50d693935f97cd3cb
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 11:53:15 2025 +0800

    获取对象接口统一调用libclass.php中的

commit 95a7e88f4206eca98b05f829684b0d9cefa9de96
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 11:43:21 2025 +0800

    加入了点赞功能

commit 47d65478de7fb244982b1631d8ea492fe0c38bef
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 10:36:29 2025 +0800

    移动了init.sql的位置

commit c1e0ae1bd51e8cc3ee91183d658cd3d2fc5a26c8
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 10:27:28 2025 +0800

    加入了重邮小站登录接口的ssrf防御

commit ccfc1d644decb4cb7730d17e1cb7fc1d86aed265
Author: Bail <2915289604@qq.com>
Date:   Sun Jan 26 10:14:33 2025 +0800

    加入了csrf防御

commit 5097502d14bda7aa494f8dbbe1e3f2b26bea7403
Author: Bail(OnePlus PJF110) <2915289604@qq.com>
Date:   Sun Jan 26 06:51:58 2025 +0800

    重邮小站登录接口增加了鉴权

commit 698da946881559a7195dac8353e54a53c17c3b2e
Author: Bail(OnePlus PJF110) <2915289604@qq.com>
Date:   Sun Jan 26 06:07:45 2025 +0800

    支持了锚点跳转

commit 4faa706e4b849913a2ca16f9a9ce3010692974c3
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 22:28:15 2025 +0800

    增加了评论功能

commit b5d9abdc765b0555d86084ce513fc76546230999
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 17:46:34 2025 +0800

    增加了初始化数据库

commit 43fb17016db75151206a2a4b29ac2203b2ece293
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 17:43:07 2025 +0800

    增加了添加好友功能

commit b024f96c03612df781301f1549274d38bd4c4537
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 16:26:12 2025 +0800

    增加了后门（

commit 9d30e7d666c8690692dcace96e7554ad7d333c33
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 16:17:49 2025 +0800

    增加了重邮内部小站登录入口

commit 847d0b02beacf6e955f4c0bc5c30c41bba9a29c6
Author: Bail <2915289604@qq.com>
Date:   Sat Jan 25 11:01:01 2025 +0800

    增加了聊天室类型，为开辟私聊做准备

commit 6f7eca9dbed4406c99b453f0088b99ada86bffce
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 22:34:36 2025 +0800

    修复了xss
    增加了消息的发送时间
    支持了多行消息

commit 6425dfe6e8e608badeeb5419deadaa5dc2509926
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 22:04:55 2025 +0800

    基本实现了发表留言

commit 373c6bddae2db88d85d0688835080287fe9a66b5
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 20:54:31 2025 +0800

    增加了基本消息操作

commit 72fb2c13a3b6721662f48ced38a5b6835f03b5d4
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 18:32:40 2025 +0800

    将login和register的html和php分离

commit 2b2b1b7555356d83e4a2ec3153c79d50827d1698
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 17:26:06 2025 +0800

    增加修改密码功能

commit 4ae5fca7ef67d349ea46b1c9404c40698c4d6be0
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 16:55:38 2025 +0800

    增加了更换头像功能

commit f1a81c5ca280b073b035194a3a8b71f3bda25add
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 13:17:04 2025 +0800

    实现了留言面板,头像功能;个人信息页面起步

commit a203231b8fdcd7d9d1babd829b921ec6f0c73dd3
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 10:34:57 2025 +0800

    增加退出登录
    增加登录用户自动跳转
    修复旁观模式超链接
    增加注册页面跳转登录

commit a5c554b2d7bdbd8f2d53512399fb7e90f9d8d751
Author: Bail <2915289604@qq.com>
Date:   Fri Jan 24 10:14:31 2025 +0800

    初步完成注册功能

commit 47bfbbd49e7948253444b51931e7b88ee2e76e3f
Author: Bail <2915289604@qq.com>
Date:   Thu Jan 23 22:44:17 2025 +0800

    初步实现登录页面和登录功能
```
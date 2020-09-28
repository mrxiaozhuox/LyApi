# LyAPI FrameWork
[![Travis](https://img.shields.io/badge/Language-PHP-blue.svg)](http://php.net)
[![Travis](https://img.shields.io/badge/License-MIT-brightgreen.svg)](https://mit-license.org)
[![Travis](https://img.shields.io/badge/Version-V1.7.0.5-orange.svg)](http://lyapi.wwsg18.com)

LyAPI是一款轻量级的PHP 接口开发框架，可快速开发出易维护、高性能的API接口。内置缓存、日志、数据库操作、国际化等功能。

如果您觉得项目还不错，请给我一个star，项目维护不易。谢谢！

> 框架已在 B站 更新 [教程视频](https://space.bilibili.com/40867466)。

## README 版本

[README - English](README-EN.md)

更多语言 - 您可以提交其他语言翻译的MarkDown文件！

## 核心重构

LyApi 核心代码为 **2018** 年开发，部分代码优化并不好。

所以作者将在这段时间里进行核心程序重构。

PS: 将不会影响老版接口程序运行！

日期：*2929/09/28*

## 支持功能

- 数据格式：通过配置文件修改数据格式。
- 国际化操作：返回不同语言的数据。
- 文件缓存：内置文件缓存功能。
- 其他缓存：内置PRedis封装，方便使用Redis。
- 日志记录：简单的日志记录功能。
- 数据库操作：使用第三方库 Medoo、NotORM。
- 注册树：将对象存入注册树，并在任何地方取出。
- 自定义配置：可创建自定义配置，方便程序调用。
- CURL：封装CURL操作，方便请求数据。
- 内部启动：在程序中直接调用接口函数获取数据。
- Cookie：对Cookie进行简单封装。
- 视图渲染：支持渲染HTML页面。
- 插件管理：可安装插件，使用插件完成快速开发。
- 脚本系统：使用方便的脚本开发接口（开发中）。
- 可视化管理：使用可视化管理工具操作框架（开发中）。
- 框架项目持续更新中...

## 后续组件

为了用户能更加方便的开发接口，我们将开发一些组件为用户提供支持：

* LyApi - Admin  --方便小白用户的接口管理程序 (开发中...)

## 安装方法

使用Composer构建LyApi项目:

    $ composer create-project mrxzx/lyapi

使用宝塔面板一键部署LyApi项目:

    详细教程: http://blog.wwsg18.com/index.php/archives/48/

直接下载（我们建议使用官方的下载地址）: [官方云盘](http://pan.wwsg18.com/s/3k8ge8dl)

## 应用Demo

框架自带 Demo 位于 “app\api\Demo.php”

## 简单Demo

    // ./app/api/Demo.php
    <?php
    
    namespace APP\api;
    
    use LyApi\core\API;
    
    class Demo extends API{
        public function User(){
            return array(
                'username' => 'mr小卓X',
                'password' => '12345678'
            );
        }
    }

#### 运行结果:

    {
        "code":"200",
        "data":{
            "username":"mr小卓X",
            "password":"12345678"
        },
        "msg":""
    }

## 图片演示

![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI1.png)
![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI2.png)

## 在线体验

不想下载？你可以使用[在线体验][1]功能！

## 在线文档

想深入了解LyAPI？快来看看[在线文档][4]吧！

文档看不懂？来看看作者录制的教程吧：[官方教程视频](https://space.bilibili.com/40867466)。

## 最近更新

> 框架最新稳定版本: V1.7.X （建议选用此版本）

- 框架更新请前往 [version.txt](version.txt) 查看更新

## 插件拓展

LyAPI将会不断的更新插件拓展：
- LyView 内置模板引擎的页面渲染系统
- LyDocs 根据注释自动生成接口文档
- PConfig 插件配置文件系统
- VisitRecord 接口访问数量统计

- [LyMaster](http://master.wwsg18.com) 在线接口管理系统

- [更多插件请前往论坛查看][5]

#### 插件安装

下载地址推荐: 

- 万物论坛: [插件专区][5]
- 框架交流群: [快速加入][6]

#### 插件使用
所有插件都在命名空间: plugin 下的
PS: 快速引入函数：DI::PluginDyn(插件名,获取类,参数...);

## 参与贡献

1. Fork代码到你的仓库
2. 增加功能并自行测试
3. 发起Pull Requests
4. 等待管理员审查

## 开源协议

LyAPI使用MIT协议，更多信息请查看[MIT协议官网][3]

## 联系作者

作者: mr小卓X

Q Q: 3507952990

交流群: 769094015 (加群提问)

个人博客: http://blog.wwsg18.com

Gitee: https://gitee.com/mrxzx/LyApi

GitHub: https://github.com/xiaozhuox/LyApi

PS: 任何问题直接联系我就行，我会第一时间解决问题。

## 已知问题

问题1：Composer创建的项目报错：

解决方法：删除vendor文件夹，重新install即可

问题2：FileCahce和Log无法正常使用：

解决方法：在根目录新建data文件夹，里面再建cahce和log文件夹

问题3：在配置了伪静态的情况下依旧显示404：

解决方法：更换PHP版本，目前测试过7.3的PHP是无法运行框架的。

> 如发现更多问题请 发布Issue 或 加群反馈 

[1]: http://lyapi.org/trial.html
[2]: https://packagist.org/users/wwsg18/
[3]: https://mit-license.org
[4]: https://mrxzx.gitee.io/lyapi-docs/#/
[5]: http://bbs.wwsg18.com/
[6]: //shang.qq.com/wpa/qunwpa?idkey=06e2f22cef00613b68463dda8983f689395d90e358115b76f912e7afc8854878

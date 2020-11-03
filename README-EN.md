# LyAPI FrameWork
[![Travis](https://img.shields.io/badge/Language-PHP-blue.svg)](http://php.net)
[![Travis](https://img.shields.io/badge/License-MIT-brightgreen.svg)](https://mit-license.org)
[![Travis](https://img.shields.io/badge/Version-V1.7.0.5-orange.svg)](http://lyapi.wwsg18.com)

Lyapi is a PHP Web Development Framework, which can quickly develop an easy to maintain, high-performance API System. Built in cache, logger, database operation, internationalization and other functions.

> We have Tutorial Video in BiliBili : [Video Link](https://space.bilibili.com/40867466)。

## README Version

[README - Chinese](README.md)


## Functions

- Data Pattern：Use config to change data pattern。
- Internationalization：According different user to return different language。
- File Cache：The Framework built in File Cache object。
- Other Cahce：Encapsulation PRedis object，you can use Redis system。
- Logger Save：You can use logger save function。
- Database operation：Use The third party Object:  Medoo、NotORM。
- Register Tree：Save object to the Register Tree。
- Custom Config：Can create。
- CURL System：Encapsulation CURL system，You can get data from other API。
- Inside Lanuch：Calling interface function directly in program to get data。
- Cookie：Encapsulation Cookie Functions。
- View Page Render：You can render HTML page。
- Plugin Manager：You can install plugin，And use it。
- Script System：Use script to develop API（Developing）。
- Visualization Develop：Use Visualization Application Manage Framework（Developing）。
- Project in continuous update ...

## More components

We will update more components for developers to use.

## How to install

Use Composer Create LyApi Project:

    $ composer create-project mrxzx/lyapi

Our use BT Panel:

    Chinese Course: http://blog.wwsg18.com/index.php/archives/48/

## A Small Demo

    // ./app/api/Demo.php
    <?php
    
    namespace APP\api;
    
    use LyApi\core\API;
    
    class Demo extends API{
        public function User(){
            return array(
                'username' => 'mrxiaozhuox',
                'password' => '12345678'
            );
        }
    }

#### Run Result:

    {
        "code":"200",
        "data":{
            "username":"mrxiaozhuox",
            "password":"12345678"
        },
        "msg":""
    }

## Image Demo

![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI1.png)
![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI2.png)

## Online Test

Want Use it in online？You can Use [Online Trial][1]！

## Online Document

Want to know more about LyAPI？You can lock [Online Document][4]！

## Recent Update

- Update log in file: version.txt

## Plugin Extend

LyAPI Will Constantly：
- LyView Create HTML page
- LyDocs Automatic generation API document
- PConfig Plugin config system
- VisitRecord Statistics of visits
- [More Plugin][5]

#### Plugin Install

Where Can Download Plugin: 

- WW BBS: [Plugin Group][5]
- QQ Group: [Join][6]

#### PLugin Used
All Plugin in the namespace of: plugin
PS: You can use DI to get plugin：DI::PluginDyn(pluginName,pluginClass,parameter...);

## Join Us

1. Fork code to your gitee\github
2. Write code and test it
3. Create Pull Requests
4. Wait administrator pass request

## Open Source License

LyAPI Use MIT，More information: [MIT License][3]

## Contact Author

author: mrxiaozhuox

Q Q: 3507952990

QQ Group: 769094015

My Blog: http://blog.wwsg18.com

Gitee: https://gitee.com/mrxzx/LyApi

GitHub: https://github.com/xiaozhuox/LyApi

PS: Contact me directly for any problem, and I will solve it as soon as possible.

> If you find any more problems, Please create issue or add group feedback

[1]: http://lyapi.wwsg18.com/trial.html
[2]: https://packagist.org/users/wwsg18/
[3]: https://mit-license.org
[4]: https://mrxzx.gitee.io/lyapi-docs/#/
[5]: http://bbs.wwsg18.com/forum.php?mod=forumdisplay&fid=41&filter=typeid&typeid=1&sortid=2
[6]: //shang.qq.com/wpa/qunwpa?idkey=06e2f22cef00613b68463dda8983f689395d90e358115b76f912e7afc8854878

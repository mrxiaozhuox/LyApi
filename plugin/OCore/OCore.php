<?php

namespace Plugin\OCore;

use APP\DI;
use LyApi\core\error\CustomException;
use Plugin\Core\Core;
use Plugin\PConfig\PConfig;

/**
 * Name: OCore.OCore
 * Author: LyAPI
 * ModifyTime: 2019/12/24
 * Purpose: OCore主程序
 */

class OCore extends Core
{

    private $Plugin_Config;

    //设置插件信息
    public function __construct()
    {
        $this->Plugin_Name = 'OCore';
        $this->Plugin_Version = 'V1.0.1';
        $this->Plugin_Author = 'mrxiaozhuox';
        $this->Plugin_About = '使用本插件接管框架ECore系统可获得更多功能（ 当前支持ECore for V1.6.7 ）';
        $this->Plugin_Examine = '';

        $this->Plugin_Config = new PConfig($this->Plugin_Name);

        // 插件配置文件
        $this->Plugin_Config->InitConfig("setting", array(
            "FilteWord" => "fuck,shit,cnm,mmp,sb,cao",
            "CacheMethod" => array(
                "Type" => "File",
                "Server" => array()
            )
        ));


        // 处理头文件
        $this->Plugin_Config->InitConfig("headers", array(
            "jpg" => "Content-type: image/jpg",
            "png" => "Content-type: image/jpg",
            "jpeg" => "Content-type: image/jpg",
            "js" => "Content-type: text/javascript",
            "css" => "Content-type: text/css",
            "json" => "Content-type: application/json",
            "zip" => "Content-Type: application/zip",
            "rar" => "Content-Type: application/zip",
            "pdf" => "Content-Type: application/pdf"
        ));

        // 文件路径映射
        $this->Plugin_Config->InitConfig("maps", array(
            "Resource" => LyApi . "/app/view/static/",
            "resource" => LyApi . "/app/view/static/",
        ));
    }

    // 接管函数：替换使用函数
    public function TargetFinding($using_namespace = '', $using_function = '')
    {
        // 这个函数会在调用函数使用前执行，你可以替换使用的函数

        // ---------- 这边给出Demo以供参考 ---------- //

        // PS: 当你不需要本功能，删除下方所有程序，提升接口运行效率

        // 获取 OCore 配置信息
        $setting = $this->Plugin_Config->ReadConfig('setting');

        // Demo首先对URL进行了简单对解析
        $all_path = explode('\\', $using_namespace . '\\' . $using_function);
        $first_path = $all_path[2];
        $maps = $this->Plugin_Config->ReadConfig('maps');
        // 判断当前访问对是不是静态资源页面

        if ((array_key_exists($using_function, $maps) && $first_path == '') ||
            array_key_exists($first_path, $maps)
        ) {

            if (array_key_exists($using_function, $maps)) {
                $nowmap = $maps[$using_function];
            } else {
                $nowmap = $maps[$first_path];
            }

            // 返回更新后使用的对象与函数
            return [
                'namespace' => 'APP\api\Root',                      // 转到的命名空间（包括对象）
                'function' => 'Index',                              // 转到的函数名
                'backval' => [                                      // 将数据传入处理函数，做第三个参数
                    'map' => $nowmap                                // 当前使用的资源目录
                ],
                'rewrite' => function ($type, $req, $backval) {         // 重写需要运行的函数

                    // 获取需要访问的文件
                    // var_dump($backval);
                    $uri = $_SERVER['REQUEST_URI'];

                    if (strrpos($uri, "?") != false) {
                        $uri = substr($uri, 0, strrpos($uri, "?"));
                    }

                    $path_list = array_filter(explode('/', $uri));
                    array_shift($path_list);

                    $file_path = implode('/', $path_list);

                    $config = new PConfig("OCore");
                    $setting = $config->ReadConfig("setting");

                    if (is_file($backval['map'] . $file_path)) {

                        $file = file_get_contents($backval['map'] . $file_path);

                        $suffix = pathinfo($file_path, PATHINFO_EXTENSION);
                        $headers = $this->Plugin_Config->ReadConfig("headers");

                        // 对一些特殊文件进行处理
                        if (array_key_exists($suffix, $headers)) {
                            header($headers[$suffix]);
                        }

                        return $file;
                    } else {
                        throw new CustomException('Invalid Request: Resource file not found');
                    }
                }
            ];
        }

        // ---------- 这边给出Demo以供参考 ---------- //

        // 处理接口程序头文件
        if (is_subclass_of($using_namespace, 'LyApi\core\classify\API')) {
            header('Content-type: application/json');
        }

        // 没有被特殊处理就正常运行
        return [
            'namespace' => $using_namespace,
            'function' => $using_function
        ];
    }

    // 接管函数：插件初始化
    public function InitPlugin($plugin_name = '', $plugin_version = '')
    {
        // 这个函数会在所有插件被初始化时调用，你可以在这里进行前置操作

        // 返回的数据将存入对象的 Tmp_Data 数据下
        return [];
    }
}

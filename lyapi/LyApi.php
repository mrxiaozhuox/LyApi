<?php

namespace LyApi;

use APP\DI;
use APP\program\Ecore;
use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;
use LyApi\core\error\OtherException;
use LyApi\core\request\Request;
use LyApi\tools\Config;
use LyApi\tools\Template;

class LyApi
{

    // LyAPI信息：
    public static $version = "1.7.0";


    //普通对象函数
    private $appConfig = [];

    public function __construct($Config = [])
    {
        // 对配置进行处理
        if (!array_key_exists("Http_Status_Set", $Config)) {
            $Config['Http_Status_Set'] = true;
        }

        $Config["apiConfig"] = Config::getConfig("api", "");

        $this->appConfig = $Config;

        DI::RegisterTree("LyApiObject", $this);
    }

    // 运行接口程序
    public function Run()
    {
        $Config = $this->appConfig;
        $resopnse = $this->processor();

        self::httpStatus($resopnse[0]['status'], $Config['Http_Status_Set']);

        $result = $resopnse[1];

        if ($resopnse[0]['type'] == "API") {
            header("content-type:application/json");
            $result = json_encode($result, JSON_PRETTY_PRINT);
        } else {
            header("content-type:text/html");
        }

        echo $result;
    }

    public function processor($env_focus = "API", $uri_info = null)
    {

        // 系统配置文件读取
        $apiConfig = Config::getConfig("api", "");
        $funConfig = Config::getConfig("func", "");

        $header = [
            "status" => 200,
            "type" => $env_focus
        ];

        if ($uri_info == null) {
            $uri_info = $_SERVER['REQUEST_URI'];
        }

        // 启动 ECore 拓展程序
        $usiEcore = null;
        if ($funConfig['USING_ECORE']) {
            $usiEcore = new Ecore();
        }

        $resopnse = $apiConfig['DEFAULT_RESPONSE'];
        $methods = $apiConfig['ACCESS_METHODS'];
        $service = $apiConfig['GET_METHOD_SETTING']['DEFAULT_SERVICE'];

        if ($methods == "URL" || Request::Request($service) != "") {

            $target_path = array();

            if ($methods == "URL") {

                $AccessUri = $uri_info;

                if (strrpos($AccessUri, "?") != false) {
                    $AccessUri = substr($AccessUri, 0, strrpos($AccessUri, "?"));
                }
                $AccessArray = explode("/", $AccessUri);
                $AccessArray = array_filter($AccessArray);
                $DelNum = $apiConfig['URL_METHOD_SETTING']['EFFECTIVE_POSITION'];
                array_splice($AccessArray, 0, $DelNum);
                if (sizeof($AccessArray) == 0) {
                    array_push($target_path, $apiConfig['URL_METHOD_SETTING']['DEFAULT_CLASS'], $apiConfig['URL_METHOD_SETTING']['INDEX_FUNCTION']);
                } elseif (sizeof($AccessArray) == 1) {
                    array_push($target_path, $apiConfig['URL_METHOD_SETTING']['DEFAULT_CLASS'], $AccessArray[0]);
                } else {
                    $target_path = $AccessArray;
                }
            } else {
                $target = $_REQUEST[$service];
                $target_path = explode($apiConfig['GET_METHOD_SETTING']['SERVICE_SEGMENTATION'], $target);
            }

            // 取得函数名称
            $func_name =  $target_path[sizeof($target_path) - 1];
            array_pop($target_path);

            $namespace = "APP\\api\\" . join('\\', $target_path);

            $object = null;
            $rewrite_func = null;

            // 处理重写函数
            if ($usiEcore) {

                $Target_Result = $usiEcore->TargetFinding($namespace, $func_name);

                $namespace = $Target_Result['namespace'];
                $func_name = $Target_Result['function'];
                if (isset($Target_Result['rewrite'])) {
                    $rewrite_func = $Target_Result['rewrite'];
                }
                if (isset($Target_Result['backval'])) {
                    $backval = $Target_Result['backval'];
                } else {
                    $backval = null;
                }
            }

            if (class_exists($namespace)) {
                $object = new $namespace;

                if (is_subclass_of($object, 'LyApi\core\classify\VIEW')) {

                    // 处理VIEW视图渲染

                    $header['type'] = "VIEW";

                    $methods = get_class_methods($namespace);
                    $funcConfig = Config::getConfig("func", '');

                    //调用初始函数
                    if (in_array($funcConfig['INIT_FUNC'], $methods)) {
                        $init_name = $funcConfig['INIT_FUNC'];
                        @$object->$init_name($func_name);
                    }

                    // 处理VIEW视图的异常
                    if (in_array($func_name, $methods)) {
                        try {

                            if ($rewrite_func == null) {
                                return [$header, $object->$func_name('API', $_REQUEST)];
                            } else {
                                return [$header, $rewrite_func('API', $_REQUEST, $backval)];
                            }
                        } catch (ClientException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("VIEW", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (ServerException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("VIEW", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (OtherException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("VIEW", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (CustomException $e) {
                            $header['type'] = "API";
                            return [$header, $e->ErrorMsg()];
                        }
                    } else {
                        $header['status'] = 404;
                        return [$header, self::ShowError("VIEW", 404, "目标程序不存在")];
                    }

                    //调用结束函数
                    if (in_array($funcConfig['AFTER_FUNC'], $methods)) {
                        $after_name = $funcConfig['AFTER_FUNC'];
                        @$object->$after_name($func_name);
                    }
                } elseif (is_subclass_of($object, 'LyApi\core\classify\API')) {

                    $all_data = array();

                    $header['type'] = "API";

                    $methods = get_class_methods($namespace);
                    $funcConfig = Config::getConfig("func", '');

                    //调用初始函数
                    if (in_array($funcConfig['INIT_FUNC'], $methods)) {
                        $init_name = $funcConfig['INIT_FUNC'];
                        @$object->$init_name($func_name);
                    }

                    // 处理API调用程序
                    if (in_array($func_name, $methods)) {
                        try {
                            if ($rewrite_func == null) {

                                $retinfo = $object->$func_name('API', $_REQUEST);
                                $retdata = $object->_FUNCDATA;

                                $result = $this->createStructure($retinfo, $retdata);
                                $header['status'] = $result[0];


                                return [$header, $result[1]];
                            } else {
                                return [$header, $rewrite_func('API', $_REQUEST, $backval)];
                            }
                        } catch (ClientException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("API", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (ServerException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("API", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (OtherException $e) {
                            $header['status'] = $e->ErrorCode();
                            return [$header, self::ShowError("API", $e->ErrorCode(), $e->ErrorMsg())];
                        } catch (CustomException $e) {
                            $header['type'] = "VIEW";
                            return [$header, $e->ErrorMsg()];
                        }
                    } else {
                        $function_not_find = $apiConfig['ERROR_MESSAGE']['function_not_find'];
                        $res = $this->createStructure($function_not_find)[1];

                        $header['status'] = $function_not_find['#code'];

                        return [$header, $res];
                    }

                    //调用结束函数
                    if (in_array($funcConfig['AFTER_FUNC'], $methods)) {
                        $after_name = $funcConfig['AFTER_FUNC'];
                        @$object->$after_name($func_name);
                    }
                }
            } else {
                $class_not_find = $apiConfig['ERROR_MESSAGE']['class_not_find'];

                $header['status'] = $class_not_find['#code'];
                $header['type'] = "API";
                return [$header, $this->showError("API", $class_not_find)[1]];
            }
        } else {
            $service_not_find = $apiConfig['ERROR_MESSAGE']['service_not_find'];


            $header['status'] = $service_not_find['#code'];
            $header['type'] = "API";
            return [$header, $this->showError("API", $service_not_find)[1]];
        }
    }

    // 接口结构生成
    public function createStructure($data, $func = [])
    {
        $apiConfig = $this->appConfig['apiConfig'];
        $resopnse = $apiConfig['DEFAULT_RESPONSE'];

        if (array_key_exists('CODE_CONTROLLER', $apiConfig)) {
            $codeController = $apiConfig['CODE_CONTROLLER'];
            if (substr($codeController, 0, 1) == '$') {
                $codeController = substr($codeController, 1, strlen($codeController) - 1);
            }
        } else {
            $codeController = "code";
        }

        $status = 200;
        $expand = [];
        $delete = [];

        if ($func != []) {
            $delete = array_merge($delete, $func['hiddens']);
        }

        $structure = array();

        if ($data == null) {
            $data = [];
        }

        if (is_string($data)) {
            $data = [
                "data" => $data
            ];
        } else {
            $temp_data = [];
            foreach ($data as $key => $value) {
                if (substr($key, 0, 1) == "#") {
                    $data[substr($key, 1, strlen($key) - 1)] = $value;
                } elseif (substr($key, 0, 1) == "~") {
                    $expand[substr($key, 1, strlen($key) - 1)] = $value;
                } elseif (substr($key, 0, 1) == "^") {
                    array_push($delete, substr($key, 1, strlen($key) - 1));
                } else {
                    $temp_data[$key] = $value;
                }
            }

            if ($temp_data != []) {
                $data['data'] = $temp_data;
            }
        }

        foreach ($resopnse as $key => $value) {
            if (substr($value, 0, 1) == "$") {
                $need_data = substr($value, 1, strlen($value) - 1);
                if (array_key_exists($need_data, $data)) {

                    if ($need_data == $codeController) {
                        $status = $data[$need_data];
                    }

                    $structure[$key] = $data[$need_data];
                } else {
                    if ($need_data == $codeController) {
                        $structure[$need_data] = 200;
                    } else {
                        $structure[$need_data] = "";
                    }
                }
            }
        }

        foreach ($expand as $key => $value) {
            $structure[$key] = $value;
        }

        foreach ($delete as $key) {
            unset($structure[$key]);
        }

        return [$status, $structure];
    }


    public function showError($focus = "API", $data = array(), $errinfo = "")
    {
        if ($focus != "API") {
            $DirPath = LyApi . '/app/view/error/';
            if (is_file($DirPath . $data . '.html')) {
                return Template::RenderTemplate(file_get_contents($DirPath . $data . '.html'), [
                    'ERRINFO' => $errinfo
                ]);
            } else {
                return Template::RenderTemplate(file_get_contents($DirPath . 'default.html'), [
                    "ERRINFO" => $errinfo,
                    "ERRCODE" => $data
                ]);
            }
        } else {
            return $this->createStructure($data);
        }
    }

    private static function httpStatus($num, $use_header = true)
    {

        if (!$use_header) {
            return;
        }

        static $http = array(
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        if (array_key_exists($num, $http)) {
            header($http[$num]);
        } else {
            header("HTTP/1.1 " . (string) $num . " Undefined");
        }
        return;
    }
}

<?php

namespace LyApi\tools;

use APP\DI;
use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\OtherException;
use LyApi\core\error\ServerException;

class Launch
{

    public static function LaunchApi($path)
    {
        $lyapi = DI::RegisterTree("LyApiObject");
        $res = $lyapi->processor("API",str_replace('.','/',$path));

        return $res[1];
    }

    public static function LaunchShell($Command)
    {
        // exec 函数默认是被禁用的，需要手动开启
        exec($Command, $output);
        return $output;
    }
}

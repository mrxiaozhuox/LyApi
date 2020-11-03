<?php

namespace LyApi\core\classify;

use LyApi\tools\Config;

class BASIC
{

    public static function GetMethod()
    {
        return  Config::getConfig('api', '')['ACCESS_METHODS'];
    }

    public static function GetParam()
    {
        if (self::GetMethod() == 'URL') {
            $AccessUri =  $_SERVER['REQUEST_URI'];

            if (strrpos($AccessUri, "?") != false) {
                $AccessUri = substr($AccessUri, 0, strrpos($AccessUri, "?"));
            }

            $AccessArray = explode("/", $AccessUri);
            $AccessArray = array_filter($AccessArray);
            return $AccessArray;
        } else {
            return $_REQUEST;
        }
    }
}

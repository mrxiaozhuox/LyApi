<?php

namespace APP;

use LyApi\cache\FileCache;
use LyApi\cache\RedisCache;
use LyApi\core\design\Register;
use LyApi\core\request\Cookie;
use LyApi\Logger\Logger;
use LyApi\model\TParty;
use LyApi\tools\CurlUtils;
use Unirest\Request;

class DI
{

    
    /*        DI Default Function        */

    // Medoo DataBase Handle Library
    public static function Medoo($ConfigSelect = 0)
    {
        return TParty::Medoo($ConfigSelect);
    }

    // PDO DataBase Handle Object
    public static function PDO($AutoLoad = null, $DSN = null)
    {
        return TParty::PDO($AutoLoad, $DSN);
    }

    // File Cache Handle System
    public static function FileCache($group = null)
    {
        return new FileCache($group);
    }

    // Redis Handle System
    public static function RedisCache($config)
    {
        return new RedisCache($config);
    }

    // Log Handle System
    public static function Logger()
    {
        return new Logger();
    }

    // Curl Tool System
    public static function CurlUtils($url, $responseHeader = 0)
    {
        return new CurlUtils($url, $responseHeader);
    }

    // Unirest Library Object
    public static function Unirest()
    {
        return new Request();
    }

    // Cookie Handle System
    public static function Cookie($path = null, $domain = null, $secure = false, $httponly = false)
    {
        return new Cookie($path, $domain, $secure, $httponly);
    }

    // Request Handle System
    public static function Request()
    {
        return new \LyApi\core\request\Request();
    }

    // Dynamic Plugin Call Function
    public static function PluginDyn($plugin, $class, ...$args)
    {
        $object = null;

        $class_path = '\\Plugin\\' . $plugin . '\\' . $class;
        if (class_exists($class_path)) {
            eval('$object = new $class_path(' . implode(",", $args) . ');');
        }

        return $object;
    }

    // Register Tree Object Save System
    public static function RegisterTree($name, $object = null)
    {
        if ($object == null) {
            return Register::Get($name);
        } else {
            return Register::Set($name, $object);
        }
    }

    /*        Custom Your DI Function        */
}

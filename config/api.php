<?php

/*
 * 接口基础配置文件
 */

return array(
    'DEFAULT_RESPONSE' => array(            //响应数据结构(Key可修改，Value请勿随便修改)
        'code' => '$code',
        'data' => '$data',
        'msg' => '$msg'
    ),
    
    'ACCESS_METHODS' => 'URL',              // 服务获取类型（ URL 或 GET ）
    
    'URL_METHOD_SETTING' => [               // 当上方类型选择为 URL 时的配置（ GET请无视 ）
        'EFFECTIVE_POSITION' => 0,          // URL从第几个开始解析（ 生产环境填 0 即可 ）
        'DEFAULT_CLASS' => 'Root',          // 默认使用对象
        'INDEX_FUNCTION' => 'Index'         // 默认 / 页面对应的函数名
    ],
    
    'GET_METHOD_SETTING' => [               // 当上方类型选择为 GET 时的配置（ URL请无视 ）
        'DEFAULT_SERVICE' => 'service',     // 服务参数名
        'SERVICE_SEGMENTATION' => '.',      // 类与函数之间的间隔符（ 默认 . ）
    
    ],
    'HTTP_STATUS_SET' => true,              // 接口是否使用Header返回状态码
        
    'ERROR_MESSAGE' => array(               // 自定义错误信息
        'service_not_find' => array(            // service找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Service does not exist'
        ),
        'class_not_extend' => array(            // class未继承时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Class does not extend API'
        ),
        'class_not_find' => array(              // class找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Class does not exist'
        ),
        'function_not_find' => array(           // function找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Function does not exist'
        )
    )
);

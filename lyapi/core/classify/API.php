<?php

namespace LyApi\core\classify;

class API extends BASIC
{

    public $_FUNCDATA = [
        "hiddens" => []
    ];

    //设置隐藏数据
    public function hiddenKeys($key)
    {
        if (is_array($key)) {
            array_merge($this->_FUNCDATA['hiddens'], $key);
        } elseif (is_string($key)) {
            if (!in_array($key, $this->_FUNCDATA['hiddens'])) {
                array_push($this->_FUNCDATA['hiddens'], $key);
            }
        }
    }
}

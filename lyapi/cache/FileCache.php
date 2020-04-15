<?php

namespace LyApi\cache;

class FileCache implements Cache
{
    private $dir;

    /**
     * 初始化缓存设置.
     */
    public function __construct($group = null)
    {
        if (is_null($group)) {
            $group = 'defualt';
        }
        $dir = LyApi . '/data/cache/' . $group;
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                return false;
            }
        }
        $this->dir = $dir;
    }

    /**
     * 设置一个缓存值.
     *
     * @param string $key 键名
     * @param string $data 数据
     * @param string $expire 过期时间
     *
     * @return boolean
     */
    public function set($key, $data, $expire = 0)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if ($expire != 0) {
            $duetime = time() + $expire;
            $datas = array(
                'data' => $data,
                'duetime' => $duetime
            );
        } else {
            $datas = array(
                'data' => $data
            );
        }

        $datas = json_encode($datas, JSON_UNESCAPED_UNICODE);
        $datas = base64_encode($datas);
        if (file_put_contents($filename, $datas)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取一个缓存值.
     *
     * @param string $key 键名
     *
     * @return string
     */
    public function get($key)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if (is_file($filename)) {
            $datas = file_get_contents($filename);
            $datas = base64_decode($datas);
            $datas = json_decode($datas, true);
            if (array_key_exists('duetime', $datas)) {
                if ($datas['duetime'] > time()) {
                    return $datas['data'];
                } else {
                    @unlink($filename);
                    return '';
                }
            } else {
                return $datas['data'];
            }
        }
    }

    /**
     * 判断一个缓存键是否存在.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function has($key)
    {
        if ($this->get($key) == '') {
            return false;
        } else {
            return true;
        }
    }


    /**
     * 删除一个缓存键.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function delete($key)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        return @unlink($filename);
    }


    /**
     * 清空所有缓存.
     */
    public function clean()
    {
        $dirs = scandir($this->dir);
        foreach ($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {
                @unlink($this->dir . '/' . $dir);
            }
        }
    }
}

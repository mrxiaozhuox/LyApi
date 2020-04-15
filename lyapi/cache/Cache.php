<?php


namespace LyApi\cache;


interface Cache
{

    /**
     * 初始化缓存设置.
     */
    public function __construct($group = null);

    /**
     * 获取一个缓存值.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function set($key, $data, $expire = 0);

    /**
     * 获取一个缓存值.
     *
     * @param string $key 键名
     *
     * @return string
     */
    public function get($key);

    /**
     * 判断一个缓存键是否存在.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function has($key);

    /**
     * 删除一个缓存键.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function delete($key);

    /**
     * 清空所有缓存.
     */
    public function clean();

}
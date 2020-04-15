<?php

namespace LyApi\cache;

use Predis;

class RedisCache implements Cache
{
    private $client;

    private $group;

    public function __construct($group = null)
    {
        Predis\Autoloader::register();

        $this->client = new Predis\Client('tcp://127.0.0.1:6379');

        $this->group = 'cache'.$group;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $data, $expire = 0)
    {
        if($expire == 0)
            return $this->client->set($this->group.$key,$data);
        else
            return $this->client->setex($this->group.$key,$expire,$data);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return $this->client->get($this->group.$key);
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return $this->client->exists($this->group.$key);
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        return $this->client->del($this->group.$key);
    }

    /**
     * @inheritDoc
     */
    public function clean()
    {
            $keys = $this->client->keys($this->group . '*');

            foreach ($keys as $key) {
                $this->delete($key);
            }

            return true;
    }
}

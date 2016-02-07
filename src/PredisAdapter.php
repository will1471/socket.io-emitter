<?php

namespace Will1471\SocketIoEmitter;

class PredisAdapter implements RedisInterface
{

    private $client;

    public function __construct(\Predis\Client $client)
    {
        $this->client = $client;
    }

    public function publish($channel, $data)
    {
        return $this->client->publish($channel, $data);
    }

}

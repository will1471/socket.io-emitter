<?php

namespace Will1471\SocketIoEmitter;

interface RedisInterface
{
    public function publish($channel, $data);
}

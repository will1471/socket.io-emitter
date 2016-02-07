<?php

namespace Will1471\SocketIoEmitter;

class MsgpackAdapter implements MsgpackInterface
{

    public function encode($data)
    {
        require_once __DIR__ . '/msgpack.php';
        return msgpack_pack($data);
    }

}

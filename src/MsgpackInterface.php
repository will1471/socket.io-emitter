<?php

namespace Will1471\SocketIoEmitter;

interface MsgpackInterface
{

    /**
     * @param mixed $data
     *
     * @return string
     */
    public function encode($data);

}

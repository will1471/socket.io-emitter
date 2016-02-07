<?php

namespace Will1471\SocketIoEmitter;

class Emitter
{

    const TYPE_EVENT = 2;
    const TYPE_BINARY_EVENT = 5;

    private $redis;
    private $msgpack;

    private $uid = 'emitter';
    private $prefix = 'socket.io';
    private $namespace = null;
    private $rooms = [];
    private $flags = [];


    /**
     * @param RedisInterface   $redis
     * @param MsgpackInterface $msgpack
     */
    public function __construct(RedisInterface $redis, MsgpackInterface $msgpack)
    {
        $this->redis = $redis;
        $this->msgpack = $msgpack;
    }


    /**
     * @param string $namespace
     *
     * @return self
     *
     * @throws \InvalidArgumentException on invalid namespace.
     */
    public function of($namespace)
    {
        if (! is_string($namespace) || empty($namespace)) {
            throw new \InvalidArgumentException('Expected non-empty string.');
        }

        $this->namespace = $namespace;
        return $this;
    }


    /**
     * Alias of in($room)
     *
     * Limit emission to a room.
     *
     * @param string $room
     *
     * @return self
     *
     * @throws \InvalidArgumentException on invalid room name.
     */
    public function to($room)
    {
        return $this->in($room);
    }


    /**
     * Limit emission to a room.
     *
     * @param string $room
     *
     * @return self
     *
     * @throws \InvalidArgumentException on invalid room name.
     */
    public function in($room)
    {
        if (! is_string($room) || empty($room)) {
            throw new \InvalidArgumentException('Expected non-empty string.');
        }

        if (! in_array($room, $this->rooms)) {
            $this->rooms[] = $room;
        }
        return $this;
    }


    /**
     * Send the message.
     *
     * Resets the namespace + rooms.
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function emit()
    {
        $args = func_get_args();
        $packet = [];
        $packet['type'] = self::TYPE_EVENT;

/*
        foreach ($args as $arg) {
            if (is_object($arg)) {
                throw new \InvalidArgumentException('Only handling strings ATM...');
            }
        }
*/
        $packet['data'] = $args;

        $packet['nsp'] = isset($this->namespace) ? $this->namespace : '/';
        unset($this->namespace);

        $opts = [
            'rooms' => $this->rooms,
            'flags' => $this->flags,
        ];

        $chn = $this->prefix . '#' . $packet['nsp'] . '#';
        $msg = $this->msgpack->encode([$this->uid, $packet, $opts]);

        if (count($opts['rooms']) > 0) {
            foreach ($opts['rooms'] as $room) {
                $chnRoom = $chn . $room . '#';
                $this->redis->publish($chnRoom, $msg);
            }
        } else {
            $this->redis->publish($chn, $msg);
        }

        $this->rooms = [];
        $this->flags = [];

        return $this;
    }

}

<?php

namespace Will1471\SocketIoEmitter\Test;

use Mockery as m;

class EmitterTest extends \PHPUnit_Framework_TestCase
{

    private $emitter;
    private $redis;
    private $msgpack;

    public function setUp()
    {
        $this->redis = m::mock(\Will1471\SocketIoEmitter\RedisInterface::class);
        $this->msgpack = m::mock(\Will1471\SocketIoEmitter\MsgpackInterface::class);
        $this->emitter = new \Will1471\SocketIoEmitter\Emitter($this->redis, $this->msgpack);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
    
    public function testDefaultNamespace()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#/#', 'xxx')->andReturn(true);
        $this->emitter->emit('some event');
    }

    public function testNonDefaultNamespace()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#foo#', 'xxx')->andReturn(true);
        $this->emitter->of('foo')->emit('some event');
    }

    public function testInRoom()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#/#foo#', 'xxx')->andReturn(true);
        $this->emitter->in('foo')->emit('some event');
    }

    public function testToRoom()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#/#foo#', 'xxx')->andReturn(true);
        $this->emitter->to('foo')->emit('some event');
    }

    public function testToRooms()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#/#foo#', 'xxx')->andReturn(true);
        $this->redis->shouldReceive('publish')->once()->with('socket.io#/#bar#', 'xxx')->andReturn(true);
        $this->emitter->to('foo')->to('bar')->emit('some event');
    }

    public function testToRoomsInNamespace()
    {
        $this->msgpack->shouldReceive('encode')->once()->withAnyArgs()->andReturn('xxx');
        $this->redis->shouldReceive('publish')->once()->with('socket.io#namespace#foo#', 'xxx')->andReturn(true);
        $this->redis->shouldReceive('publish')->once()->with('socket.io#namespace#bar#', 'xxx')->andReturn(true);
        $this->emitter->of('namespace')->to('foo')->to('bar')->emit('some event');
    }

}

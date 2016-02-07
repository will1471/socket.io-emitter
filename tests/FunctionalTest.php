<?php

namespace Will1471\SocketIoEmitter\Test;

use Cocur\BackgroundProcess\BackgroundProcess;
  
  
class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    
    public function testStuff()
    {
        $server = new BackgroundProcess("node tests/node/server.js");
        $serverLog = tempnam('/tmp', 'process');

        $client = new BackgroundProcess("node tests/node/client.js");
        $clientLog = tempnam('/tmp', 'process');

        $redisCli = new BackgroundProcess("redis-cli MONITOR");
        $redisLog = tempnam('/tmp', 'process');

        $redisCli->run($redisLog);
        sleep(1);
        $server->run($serverLog);
        sleep(2);
        $client->run($clientLog);
        sleep(2);

        $emitter = new \Will1471\SocketIoEmitter\Emitter(
            new \Will1471\SocketIoEmitter\PredisAdapter(new \Predis\Client),
            new \Will1471\SocketIoEmitter\MsgpackAdapter()
        );
        $emitter->to('room1')->emit('event1', ['foo' => 'bar']);
        $emitter->to('room2')->to('room3')->emit('event2', ['a' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890']);

        sleep(2);

        $client->stop();
        $server->stop();
        $redisCli->stop();

        var_dump(file_get_contents($serverLog));
        var_dump(file_get_contents($clientLog));
        var_dump(file_get_contents($redisLog));

        unlink($serverLog);
        unlink($clientLog);
        unlink($redisLog);
    }

}

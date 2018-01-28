<?php

namespace Swoft\Rpc\Server\Bootstrap\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\SwooleListener;
use Swoft\Bootstrap\Listeners\Interfaces\CloseInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ConnectInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ReceiveInterface;
use Swoole\Server;
use Swoft\Bootstrap\SwooleEvent;

/**
 *
 * @SwooleListener({
 *     SwooleEvent::ON_RECEIVE,
 *     SwooleEvent::ON_CONNECT,
 *     SwooleEvent::ON_CLOSE
 * },
 *     type=SwooleEvent::TYPE_PORT
 * )
 * @uses      RpcEventListener
 * @version   2018年01月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcEventListener implements ReceiveInterface,ConnectInterface,CloseInterface
{
    /**
     * RPC请求每次启动一个协程来处理
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $fromId
     * @param string $data
     */
    public function onReceive(Server $server, int $fd, int $fromId, string $data)
    {
        App::getBean('ServiceDispatcher')->doDispatcher($server, $fd, $fromId, $data);
    }

    /**
     * 连接成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $from_id
     *
     */
    public function onConnect(Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    /**
     * 连接断开成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onClose(Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
    }
}
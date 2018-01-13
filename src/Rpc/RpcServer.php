<?php

namespace Swoft\Rpc\Server\Rpc;

use Swoft\Bean\Collector\SwooleListenerCollector;
use Swoft\Bootstrap\SwooleEvent;
use Swoole\Server;
use Swoft\Bootstrap\Server\AbstractServer;

/**
 * RPC服务器
 *
 * @uses      RpcServer
 * @version   2017年10月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcServer extends AbstractServer
{

    /**
     * 启动服务器
     */
    public function start()
    {
        // rpc server
        $this->server = new Server($this->tcpSetting['host'], $this->tcpSetting['port'], $this->tcpSetting['model'], $this->tcpSetting['type']);

        // 设置回调函数
        $listenSetting = $this->getListenTcpSetting();
        $setting = array_merge($this->setting, $listenSetting);
        $this->server->set($setting);
        $this->server->on(SwooleEvent::ON_START, [$this, 'onStart']);
        $this->server->on(SwooleEvent::ON_WORKER_START, [$this, 'onWorkerStart']);
        $this->server->on(SwooleEvent::ON_MANAGER_START, [$this, 'onManagerStart']);

        $swooleEvents = $this->getSwooleEvents();
        $this->registerSwooleEvents($this->server, $swooleEvents);

        // before start
        $this->beforeStart();
        $this->server->start();
    }

    /**
     * @return array
     */
    private function getSwooleEvents()
    {
        $swooleListeners = SwooleListenerCollector::getCollector();
        $portEvents = $swooleListeners[SwooleEvent::TYPE_PORT][0]??[];
        $serverEvents = $swooleListeners[SwooleEvent::TYPE_SERVER]??[];
        return array_merge($portEvents, $serverEvents);
    }
}

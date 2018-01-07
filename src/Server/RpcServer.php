<?php

namespace Swoft\Rpc\Server\Server;

use Swoft\Server\AbstractServer;
use Swoole\Server;

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
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        $this->server->on('managerStart', [$this, 'onManagerStart']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('connect', [$this, 'onConnect']);
        $this->server->on('receive', [$this, 'onReceive']);
        $this->server->on('pipeMessage', [$this, 'onPipeMessage']);
        $this->server->on('close', [$this, 'onClose']);

        // before start
        $this->beforeStart();
        $this->server->start();
    }
}

<?php

namespace Swoft\Rpc\Server\Command;

use Swoft\Console\Bean\Annotation\Command;
use Swoft\Rpc\Server\Rpc\RpcServer;

/**
 * the group command list of rpc server
 *
 * @Command(coroutine=false,server=true)
 * @uses      RpcCommand
 * @version   2017年10月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcCommand
{
    /**
     * start rpc server
     *
     * @Usage
     * rpc:{command} [arguments] [options]
     *
     * @Options
     * -d,--d start by daemonized process
     *
     * @Example
     * php swoft.php rpc:start -d
     */
    public function start()
    {
        $rpcServer = $this->getRpcServer();
        
        // 是否正在运行
        if ($rpcServer->isRunning()) {
            $serverStatus = $rpcServer->getServerSetting();
            output()->writeln("<error>The server have been running!(PID: {$serverStatus['masterPid']})</error>", true, true);
        }

        // 选项参数解析
        $this->setStartArgs($rpcServer);
        $tcpStatus = $rpcServer->getTcpSetting();

        // tcp启动参数
        $tcpHost = $tcpStatus['host'];
        $tcpPort = $tcpStatus['port'];
        $tcpType = $tcpStatus['type'];
        $tcpModel = $tcpStatus['model'];

        // 信息面板
        $lines = [
            '                    Information Panel                     ',
            '**********************************************************',
            "* tcp | Host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, Model: <note>$tcpModel</note>, type: <note>$tcpType</note>",
            '**********************************************************',
        ];
        output()->writeln(implode("\n", $lines));

        // 启动
        $rpcServer->start();
    }

    /**
     * reload worker process
     *
     * @Usage
     * rpc:{command} [arguments] [options]
     *
     * @Options
     * -t only to reload task processes, default to reload worker and task
     *
     * @Example
     * php swoft.php rpc:reload
     */
    public function reload()
    {
        $rpcServer = $this->getRpcServer();

        // 是否已启动
        if (!$rpcServer->isRunning()) {
            output()->writeln('<error>The server is not running! cannot reload</error>', true, true);
        }

        // 打印信息
        output()->writeln("<info>Server {input()->getFullScript()} is reloading</info>");

        // 重载
        $reloadTask = input()->hasOpt('t');
        $rpcServer->reload($reloadTask);
        output()->writeln("<success>Server {input()->getFullScript()} reload success</success>");
    }

    /**
     * stop rpc server
     *
     * @Usage
     * rpc:{command} [arguments] [options]
     *
     * @Example
     * php swoft.php rpc:stop
     */
    public function stop()
    {
        $rpcServer = $this->getRpcServer();

        // 是否已启动
        if (!$rpcServer->isRunning()) {
            output()->writeln('<error>The server is not running! cannot stop</error>', true, true);
        }

        // pid文件
        $serverStatus = $rpcServer->getServerSetting();
        $pidFile = $serverStatus['pfile'];

        @unlink($pidFile);
        output()->writeln("<info>Swoft {input()->getFullScript()} is stopping ...</info>");

        $result = $rpcServer->stop();

        // 停止失败
        if (!$result) {
            output()->writeln("<error>Swoft {input()->getFullScript()} stop fail</error>", true, true);
        }

        output()->writeln("<success>Swoft {input()->getFullScript()} stop success!</success>");
    }

    /**
     * restart rpc server
     *
     * @Usage
     * rpc:{command} [arguments] [options]
     *
     * @Example
     * php swoft.php rpc:restart
     */
    public function restart()
    {
        $rpcServer = $this->getRpcServer();

        // 是否已启动
        if ($rpcServer->isRunning()) {
            $this->stop();
        }

        // 重启默认是守护进程
        $rpcServer->setDaemonize();
        $this->start();
    }

    /**
     * @return RpcServer
     */
    private function getRpcServer()
    {
        $script = input()->getScript();
        $rpcServer = new RpcServer();
        $rpcServer->setScriptFile($script);
        return $rpcServer;
    }

    /**
     * @param RpcServer $rpcServer
     */
    private function setStartArgs(RpcServer $rpcServer)
    {
        $daemonize = input()->hasOpt('d');

        // 设置后台启动
        if ($daemonize) {
            $rpcServer->setDaemonize();
        }
    }
}

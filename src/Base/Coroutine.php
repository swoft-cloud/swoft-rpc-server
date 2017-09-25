<?php

namespace Swoft\Base;

use Swoft\Helper\PhpHelper;
use Swoole\Coroutine as SwCoroutine;

/**
 * swoft协程
 *
 * @uses      Coroutine
 * @version   2017年09月25日
 * @author    inhere <in.798@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Coroutine
{
    /**
     * 协程ID映射表
     *
     * @var array
     * [
     *  child id => top id,
     *  child id => top id,
     *  ... ...
     * ]
     */
    private static $idMap = [];

    /**
     * 当前协程ID
     *
     * @return int|string
     */
    public static function id()
    {
        return SwCoroutine::getuid();
    }

    /**
     * 顶层协程ID
     *
     * @return int|string
     */
    public static function tid()
    {
        $id = SwCoroutine::getuid();
        return self::$idMap[$id] ?? $id;
    }

    /**
     * 创建子协程
     *
     * @param callable $cb
     *
     * @return bool
     */
    public static function create(callable $cb)
    {
        $tid = self::tid();
        return SwCoroutine::create(function () use ($cb, $tid) {
            $id = SwCoroutine::getuid();
            self::$idMap[$id] = $tid;

            PhpHelper::call($cb);
        });
    }

    /**
     * 挂起当前协程
     *
     * @param string $corId
     */
    public static function suspend($corId)
    {
        SwCoroutine::suspend($corId);
    }

    /**
     * 恢复某个协程，使其继续运行。
     *
     * @param string $corId
     */
    public static function resume($corId)
    {
        SwCoroutine::resume($corId);
    }
}
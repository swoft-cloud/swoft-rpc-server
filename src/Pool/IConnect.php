<?php

namespace Swoft\Pool;

/**
 * 连接接口
 *
 * @uses      IConnect
 * @version   2017年09月28日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IConnect
{
    /**
     * 创建连接
     *
     * @return mixed
     */
    public function createConnect();

    /**
     * 重新连接
     */
    public function reConnect();
}
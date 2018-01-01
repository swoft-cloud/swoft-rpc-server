<?php

namespace Swoft\Testing\Aop;

use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Bean\Annotation\Around;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\PointExecution;

/**
 * the aspect of test
 *
 * @Aspect()
 * @PointExecution(
 *     include={
 *      "Swoft\Testing\Aop\RegBean::methodParams",
 *     }
 * )
 * @uses      ExeByNewParamsAspect
 * @version   2017年12月28日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ExeByNewParamsAspect
{
    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return string
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint){
        $newArgs = [];
        $args = $proceedingJoinPoint->getArgs();
        foreach ($args as $arg){
            $newArgs[] = $arg."-new";
        }
        $tag = ' regAspect around before ';
        $result = $proceedingJoinPoint->proceed($newArgs);
        $tag .= ' regAspect around after ';
        return $result.$tag;
    }
}
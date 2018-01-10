<?php

namespace Swoft\Rpc\Server\Bean\Parser;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\Bean\Parser\AbstractParserInterface;
use Swoft\Rpc\Server\Bean\Collector\ServiceCollector;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Service注解
 *
 * @uses      ServiceParser
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServiceParser extends AbstractParserInterface
{
    /**
     * Service注解解析
     *
     * @param string  $className
     * @param Service $objectAnnotation
     * @param string  $propertyName
     * @param string  $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $beanName = $className;
        $scope    = Scope::SINGLETON;

        ServiceCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);

        return [$beanName, $scope, ""];
    }
}

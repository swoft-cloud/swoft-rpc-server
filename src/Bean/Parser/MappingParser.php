<?php

namespace Swoft\Rpc\Server\Bean\Parser;

use Swoft\Bean\Parser\AbstractParser;
use Swoft\Rpc\Server\Bean\Annotation\Mapping;
use Swoft\Rpc\Server\Bean\Collector\ServiceCollector;

/**
 * Mapping注解解析
 *
 * @uses      MappingParser
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MappingParser extends AbstractParser
{
    /**
     * Mapping注解解析解析
     *
     * @param string  $className
     * @param Mapping $objectAnnotation
     * @param string  $propertyName
     * @param string  $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $collector = ServiceCollector::getCollector();
        if (!isset($collector[$className])) {
            return;
        }

        ServiceCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}

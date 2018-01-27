<?php

namespace Swoft\Rpc\Server\Bean\Parser;

use Swoft\Bean\Parser\AbstractParserInterface;
use Swoft\Rpc\Server\Bean\Annotation\Mapping;
use Swoft\Rpc\Server\Bean\Collector\ServiceCollector;

/**
 * Mapping annotation parser
 */
class MappingParser extends AbstractParserInterface
{
    /**
     * @param string  $className
     * @param Mapping $objectAnnotation
     * @param string  $propertyName
     * @param string  $methodName
     * @param null    $propertyValue
     * @return mixed
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        $collector = ServiceCollector::getCollector();
        if (! isset($collector[$className])) {
            return;
        }

        ServiceCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}

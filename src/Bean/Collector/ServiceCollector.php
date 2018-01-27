<?php

namespace Swoft\Rpc\Server\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\Rpc\Server\Bean\Annotation\Mapping;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Service colletor
 */
class ServiceCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $serviceMapping = [];

    /**
     * @param string $className
     * @param null   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     */
    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        // collect service
        if ($objectAnnotation instanceof Service) {
            $serverName = $objectAnnotation->getName();
            self::$serviceMapping[$className]['name'] = $serverName;

            return;
        }

        // collect method
        if ($objectAnnotation instanceof Mapping) {
            $mapped = $objectAnnotation->getName();
            self::$serviceMapping[$className]['routes'][] = [
                'mappedName' => $mapped,
                'methodName' => $methodName,
            ];

            return;
        }
        if ($objectAnnotation === null && isset(self::$serviceMapping[$className])) {
            self::$serviceMapping[$className]['routes'][] = [
                'mappedName' => '',
                'methodName' => $methodName,
            ];
            return;
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$serviceMapping;
    }
}
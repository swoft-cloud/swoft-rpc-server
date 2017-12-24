<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\PointExecution;
use Swoft\Bean\Collector;

/**
 * the point annotation of parser
 *
 * @uses      PointExecutionParser
 * @version   2017年12月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class PointExecutionParser extends AbstractParser
{
    /**
     * pointExecution parsing
     *
     * @param string         $className
     * @param PointExecution $objectAnnotation
     * @param string         $propertyName
     * @param string         $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$aspects[$className])) {
            return null;
        }

        $include = $objectAnnotation->getInclude();
        $exclude = $objectAnnotation->getExclude();

        Collector::$aspects[$className]['point']['execution'] = [
            'include' => $include,
            'exclude' => $exclude,
        ];

        return null;
    }
}
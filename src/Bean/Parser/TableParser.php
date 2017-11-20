<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Table;
use Swoft\Bean\Collector;

/**
 * Table注解解析器
 *
 * @uses      TableParser
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TableParser extends AbstractParser
{

    /**
     * Table注解解析
     *
     * @param string $className
     * @param Table  $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        // 表映射收集
        $tableName = $objectAnnotation->getName();
        Collector::$entities[$className]['table']['name'] = $tableName;
        return null;
    }
}
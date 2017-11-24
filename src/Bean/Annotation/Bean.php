<?php

namespace Swoft\Bean\Annotation;

/**
 * bean注解
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @uses      Bean
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Bean
{
    /**
     * bean名称
     *
     * @var string
     */
    private $name = "";

    /**
     * bean类型
     *
     * @var int
     */
    private $scope = Scope::SINGLETON;

    /**
     * referenced bean, default is null
     *
     * @var string
     */
    private $ref = "";

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['scope'])) {
            $this->scope = $values['scope'];
        }
        if (isset($values['ref'])) {
            $this->ref = $values['ref'];
        }
    }

    /**
     * 获取bean名称
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 获取bean类型
     *
     * @return int
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * return name of referenced bean
     *
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }
}

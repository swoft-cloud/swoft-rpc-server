<?php

namespace Swoft\Rpc\Server\Bean\Annotation;

/**
 * Mapping annotation
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Mapping
{
    /**
     * 映射的名称，默认函数名称
     *
     * @var string
     */
    private $name = '';

    /**
     * Mapping constructor.
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
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

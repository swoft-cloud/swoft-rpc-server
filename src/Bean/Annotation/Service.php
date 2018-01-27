<?php

namespace Swoft\Rpc\Server\Bean\Annotation;

/**
 * RPC Servie annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Service
{
    /**
     * service名称
     *
     * @var string
     */
    private $name = '';

    /**
     * Service constructor.
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

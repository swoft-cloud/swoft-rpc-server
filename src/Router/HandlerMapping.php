<?php

namespace Swoft\Rpc\Server\Router;

use Swoft\Router\HandlerMappingInterface;

/**
 * Handler of service
 */
class HandlerMapping implements HandlerMappingInterface
{

    /**
     * Service suffix
     *
     * @var string
     */
    private $suffix = 'Service';

    /**
     * Service routes
     *
     * @var array
     */
    private $routes = [];

    /**
     * Get handler from router
     *
     * @param array ...$params
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getHandler(...$params): array
    {
        list($data) = $params;
        $func = $data['func'] ?? '';

        return $this->match($func);
    }

    /**
     * Auto register routes
     *
     * @param array $serviceMapping
     */
    public function register(array $serviceMapping)
    {
        foreach ($serviceMapping as $className => $mapping) {
            $prefix = $mapping['name'];
            $routes = $mapping['routes'];
            $prefix = $this->getPrefix($this->suffix, $prefix, $className);

            $this->registerRoute($className, $routes, $prefix);
        }
    }

    /**
     * Match route
     *
     * @param $func
     * @return array
     * @throws \InvalidArgumentException
     */
    public function match($func): array
    {
        if (! isset($this->routes[$func])) {
            throw new \InvalidArgumentException('the func of service is not exist，func=' . $func);
        }

        return $this->routes[$func];
    }

    /**
     * Register one route
     *
     * @param string $className
     * @param array  $routes
     * @param string $prefix
     */
    private function registerRoute(string $className, array $routes, string $prefix)
    {
        foreach ($routes as $route) {
            $mappedName = $route['mappedName'];
            $methodName = $route['methodName'];
            if (empty($mappedName)) {
                $mappedName = $methodName;
            }

            $serviceKey = $prefix . '::' . $mappedName;
            $this->routes[$serviceKey] = [$className, $methodName];
        }
    }

    /**
     * Get service from class name
     *
     * @param string $suffix
     * @param string $prefix
     * @param string $className
     * @return string
     */
    private function getPrefix(string $suffix, string $prefix, string $className): string
    {
        // The prefix of annotation is exist
        if (! empty($prefix)) {
            return $prefix;
        }

        // The prefix of annotation is empty
        $reg = '/^.*\\\(\w+)' . $suffix . '$/';
        $prefix = '';

        if ($result = preg_match($reg, $className, $match)) {
            $prefix = ucfirst($match[1]);
        }

        return $prefix;
    }
}

<?php

namespace Swoft\Router\Http;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\Bean;
use Swoft\Exception\Http\MethodNotAllowedException;
use Swoft\Exception\Http\RouteNotFoundException;
use Swoft\Exception\InvalidArgumentException;
use Swoft\Helper\PhpHelper;
use Swoft\Router\HandlerAdapterInterface;
use Swoft\Web\Request;
use Swoft\Web\Response;

/**
 * http handler adapter
 *
 * @Bean("httpHandlerAdapter")
 * @uses      HandlerAdapterMiddleware
 * @version   2017年11月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HandlerAdapter implements HandlerAdapterInterface
{
    /**
     * execute handler with controller and action
     * @param ServerRequestInterface $request request object
     * @param array $routeInfo handler info
     * @return \Swoft\Web\Response
     * @throws \Swoft\Exception\Http\MethodNotAllowedException
     * @throws \Swoft\Exception\Http\RouteNotFoundException
     */
    public function doHandler(ServerRequestInterface $request, array $routeInfo)
    {
        /**
         * @var int $status
         * @var string $path
         * @var array  $info
         */
        list($status, $path, $info) = $routeInfo;

        // not founded route
        if ($status === HandlerMapping::NOT_FOUND) {
            throw new RouteNotFoundException('Route not found for ' . $path);
        }

        // method not allowed
        if ($status === HandlerMapping::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowedException(sprintf(
                "Method '%s' not allowed for access %s, Allow: %s",
                $request->getMethod(), $path, implode(',', $routeInfo[2])
            ));
        }

        // handler info
        list($handler, $matches) = $this->createHandler($path, $info);

        if (\is_array($handler)) {
            $handler = $this->defaultHandler($handler);
        }

        // execute handler
        $params   = $this->bindParams($request, $handler, $matches);
        $response = PhpHelper::call($handler, $params);

        // response
        if (!$response instanceof Response) {
            $response = RequestContext::getResponse()->auto($response);
        }

        return $response;
    }

    /**
     * create handler
     *
     * @param string $path url path
     * @param array  $info path info
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function createHandler(string $path, array $info)
    {
        $handler = $info['handler'];
        $matches = $info['matches'] ?? [];

        // is a \Closure or a callable object
        if (\is_object($handler)) {
            return [$handler, $matches];
        }

        // is array ['controller', 'action']
        if (\is_array($handler)) {
            $segments = $handler;
        } elseif (\is_string($handler)) {
            // e.g `Controllers\Home@index` Or only `Controllers\Home`
            $segments = explode('@', trim($handler));
        } else {
            App::error('Invalid route handler for URI: ' . $path);
            throw new \InvalidArgumentException('Invalid route handler for URI: ' . $path);
        }

        $action    = '';
        $className = $segments[0];
        if (isset($segments[1])) {
            // Already assign action
            $action = $segments[1];
        } elseif (isset($matches[0])) {
            // use dynamic action
            $action = array_shift($matches);
        }

        $action     = HandlerMapping::convertNodeStr($action);
        $controller = App::getBean($className);
        $handler    = [$controller, $action];

        // Set Controller and Action info to Request Context
        RequestContext::setContextData([
            'controllerClass'  => $className,
            'controllerAction' => $action ?: 'index',
        ]);

        return [$handler, $matches];
    }

    /**
     * default handler
     *
     * @param array $handler handler info
     *
     * @return array
     */
    private function defaultHandler(array $handler)
    {
        list($controller, $actionId) = $handler;
        $httpRouter = App::getHttpRouter();

        $actionId = empty($actionId) ? $httpRouter->defaultAction : $actionId;
        if (!method_exists($controller, $actionId)) {
            throw new InvalidArgumentException("the {$actionId} of action is not exist!");
        }

        return [$controller, $actionId];
    }

    /**
     * binding params of action method
     *
     * @param ServerRequestInterface $request request object
     * @param mixed                  $handler handler
     * @param array                  $matches route params info
     *
     * @return array
     */
    private function bindParams(ServerRequestInterface $request, $handler, array $matches)
    {
        if (\is_array($handler)) {
            list($controller, $method) = $handler;
            $reflectMethod = new \ReflectionMethod($controller, $method);
            $reflectParams = $reflectMethod->getParameters();
        } else {
            $reflectMethod = new \ReflectionFunction($handler);
            $reflectParams = $reflectMethod->getParameters();
        }

        $bindParams = [];
        // $matches    = $info['matches'] ?? [];
        $response   = RequestContext::getResponse();

        // binding params
        foreach ($reflectParams as $key => $reflectParam) {
            $reflectType = $reflectParam->getType();
            $name        = $reflectParam->getName();

            // undefined type of the param
            if ($reflectType === null) {
                if (isset($matches[$name])) {
                    $bindParams[$key] = $matches[$name];
                } else {
                    $bindParams[$key] = null;
                }
                continue;
            }

            /**
             * defined type of the param
             * @notice \ReflectType::getName() is not supported in PHP 7.0, that is why use __toString()
             */
            $type = $reflectType->__toString();
            if ($type === Request::class) {
                $bindParams[$key] = $request;
            } elseif ($type === Response::class) {
                $bindParams[$key] = $response;
            } elseif (isset($matches[$name])) {
                $bindParams[$key] = $this->parserParamType($type, $matches[$name]);
            } else {
                $bindParams[$key] = $this->getDefaultValue($type);
            }
        }

        return $bindParams;
    }

    /**
     * parser the type of binding param
     *
     * @param string $type  the type of param
     * @param mixed  $value the value of param
     *
     * @return bool|float|int|string
     */
    private function parserParamType(string $type, $value)
    {
        switch ($type) {
            case 'int':
                $value = (int)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'double':
                $value = (double)$value;
                break;
        }

        return $value;
    }

    /**
     * the deafult value of param
     *
     * @param string $type the type of param
     *
     * @return bool|float|int|string
     */
    private function getDefaultValue(string $type)
    {
        $value = null;
        switch ($type) {
            case 'int':
                $value = 0;
                break;
            case 'string':
                $value = "";
                break;
            case 'bool':
                $value = false;
                break;
            case 'float':
                $value = 0;
                break;
            case 'double':
                $value = 0;
                break;
        }

        return $value;
    }
}

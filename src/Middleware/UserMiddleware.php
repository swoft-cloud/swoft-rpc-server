<?php

namespace Swoft\Middleware\Service;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Core\RequestHandler;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Collector;
use Swoft\Middleware\MiddlewareInterface;

/**
 * the annotation middlewares of action
 *
 * @Bean()
 * @uses      UserMiddleware
 * @version   2017年12月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface     $request
     * @param \Interop\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serviceHandler = $request->getAttribute(RouterMiddleware::ATTRIBUTE);
        list($className, $funcName) = $serviceHandler;

        $middlewares         = [];
        $middlewareCollector = Collector::$serviceMapping[$className]['middlewares']??[];
        $groupMiddlewares    = $middlewareCollector['group'] ?? [];
        $funcMiddlewares     = $middlewareCollector['actions'][$funcName]??[];

        $middlewares = array_merge($middlewares, $groupMiddlewares, $funcMiddlewares);
        if (!empty($middlewares) && $handler instanceof RequestHandler) {
            $handler->insertMiddlewares($middlewares);
        }

        return $handler->handle($request);
    }
}

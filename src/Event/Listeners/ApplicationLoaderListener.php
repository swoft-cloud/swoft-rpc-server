<?php

namespace Swoft\Rpc\Server\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\AppEvent;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Rpc\Server\Bean\Collector\ServiceCollector;

/**
 * the listener of applicatioin loader
 *
 * @Listener(AppEvent::APPLICATION_LOADER)
 * @uses      ApplicationLoaderListener
 * @version   2018年01月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ApplicationLoaderListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        /* @var \Swoft\Rpc\Server\Router\HandlerMapping $serviceRouter */
        $serviceRouter = App::getBean('serviceRouter');

        $serviceMapping = ServiceCollector::getCollector();
        $serviceRouter->register($serviceMapping);
    }
}
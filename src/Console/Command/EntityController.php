<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;

/**
 * the group command list of database entity
 *
 * @uses      EntityController
 * @version   2017年10月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityController extends ConsoleCommand
{
    /**
     * auto create entity by table structure
     *
     * @usage
     * entity:{command} [arguments] [options]
     *
     * @options
     * --ignore
     *
     * @example
     * php swoft.php entity:create
     */
    public function createCommand()
    {

    }
}
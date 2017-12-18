<?php

namespace Swoft\Testing\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Pool\BalancerSelector;
use Swoft\Pool\ProviderSelector;

/**
 * db slave  env pool config
 *
 * @Bean()
 * @uses      DbSlaveEnvPoolConfig
 * @version   2017年12月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DbSlaveEnvPoolConfig extends PropertyPoolConfig
{
    /**
     * the name of pool
     *
     * @Value(env="${DB_SLAVE_NAME}")
     * @var string
     */
    protected $name = "";

    /**
     * the maximum number of idle connections
     *
     * @Value(env="${DB_SLAVE_MAX_IDEL}")
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @Value(env="${DB_SLAVE_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(env="${DB_SLAVE_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @Value(env="${DB_SLAVE_TIMEOUT}")
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @Value(env="${DB_SLAVE_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(env="${DB_SLAVE_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(env="${DB_SLAVE_BALANCER}")
     * @var string
     */
    protected $balancer = BalancerSelector::TYPE_RANDOM;

    /**
     * the default provider is consul provider
     *
     * @Value(env="${DB_SLAVE_PROVIDER}")
     * @var string
     */
    protected $provider = ProviderSelector::TYPE_CONSUL;
}
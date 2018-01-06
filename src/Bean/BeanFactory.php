<?php

namespace Swoft\Bean;

use Monolog\Formatter\LineFormatter;
use Swoft\App;
use Swoft\Core\Config;
use Swoft\Helper\ArrayHelper;
use Swoft\Helper\DirHelper;
use Swoft\Pool\BalancerSelector;
use Swoft\Pool\ProviderSelector;
use Swoft\Web\Application;

/**
 * bean工厂
 *
 * @uses      BeanFactory
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanFactory implements BeanFactoryInterface
{

    /**
     * @var Container 容器
     */
    private static $container = null;

    /**
     * BeanFactory constructor.
     *
     * @param array $definitions
     */
    private function __construct(array $definitions)
    {
        $definitions = self::merge($definitions);

        self::$container = new Container();
        self::$container->addDefinitions($definitions);
        self::$container->autoloadAnnotations();
        self::$container->initBeans();
    }

    /**
     * 获取Bean
     *
     * @param string $name Bean名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return self::$container->get($name);
    }

    /**
     * 创建一个bean
     *
     * @param string $beanName
     * @param array  $definition
     */
    public static function createBean(string $beanName, array $definition)
    {
    }

    /**
     * bean是否存在
     *
     * @param string $name bean名称
     *
     * @return bool
     */
    public static function hasBean(string $name)
    {
        return self::$container->hasBean($name);
    }

    private static function coreBeans()
    {
        return [
            'config'             => [
                'class'      => Config::class,
                'properties' => value(function () {
                    $config = new Config();
                    $config->load('@properties', []);

                    return $config->toArray();
                }),
            ],
            'application'        => ['class' => Application::class],
            'balancerSelector'    => ['class' => BalancerSelector::class],
            'providerSelector'    => ['class' => ProviderSelector::class],
            "lineFormatter"      => [
                'class'      => LineFormatter::class,
                "format"     => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %messages%',
                'dateFormat' => 'Y/m/d H:i:s',
            ],
        ];
    }

    /**
     * 合并参数及初始化
     *
     * @param array $definitions
     *
     * @return array
     */
    private static function merge(array $definitions)
    {
        $definitions = ArrayHelper::merge(self::coreBeans(), $definitions);

        return $definitions;
    }

    /**
     * Reload bean definitions
     *
     * @param array $definitions append definitions to config loader
     */
    public static function reload($definitions = [])
    {
        $config = new Config();
        $config->load(App::getAlias('@beans'), [], DirHelper::SCAN_BFS, Config::STRUCTURE_MERGE);
        $configDefinitions = $config->toArray();
        $mergeDefinitions = ArrayHelper::merge($configDefinitions, $definitions);
        new self($mergeDefinitions);
    }
}

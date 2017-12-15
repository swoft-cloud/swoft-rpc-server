<?php

namespace Swoft;

use Swoft\Base\ApplicationContext;
use Swoft\Base\Config;
use Swoft\Base\RequestContext;
use Swoft\Base\Timer;
use Swoft\Log\Logger;
use Swoft\Pool\RedisPool;
use Swoft\Server\IServer;
use Swoft\Service\ConsulProviderInterface;
use Swoft\Web\Application;

/**
 * 应用简写类
 *
 * @uses      App
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class App
{

    /**
     * 应用对象
     *
     * @var Application
     */
    public static $app;

    /**
     * 服务器对象
     *
     * @var IServer
     */
    public static $server;

    /**
     * config bean配置对象
     *
     * @var Config
     */
    public static $properties;

    /**
     * Swoft系统配置对象
     *
     * @var Config
     */
    public static $appProperties;

    /**
     * 是否初始化了crontab
     *
     * @var bool
     */
    public static $isInitCron = false;

    /**
     * 是否处于自动化测试流程中
     *
     * @var bool
     */
    public static $isInTest = false;

    /**
     * 别名库
     *
     * @var array
     */
    private static $aliases
        = [
            '@Swoft' => __DIR__,
        ];

    /**
     * 获取mysqlBean对象
     */
    public static function getMysqlPool()
    {
        return self::getBean('mysql');
    }

    /**
     * swoft版本
     *
     * @return string
     */
    public static function version()
    {
        return '0.1.1';
    }

    /**
     * redis连接池
     *
     * @return RedisPool
     */
    public static function getRedisPool()
    {
        return self::getBean('redisPool');
    }

    /**
     * request router
     *
     * @return \Swoft\Router\Http\HandlerMapping
     */
    public static function getHttpRouter()
    {
        return App::getBean('httpRouter');
    }

    /**
     * consul对象
     *
     * @return ConsulProviderInterface
     */
    public static function getConsulProvider()
    {
        return self::getBean('consulProvider');
    }

    /**
     * 查询一个bean
     *
     * @param string $name 名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return ApplicationContext::getBean($name);
    }

    /**
     * @return Application
     */
    public static function getApplication()
    {
        return ApplicationContext::getBean('application');
    }

    /**
     * @return \Swoft\Web\DispatcherServer
     */
    public static function getDispatcherServer()
    {
        return ApplicationContext::getBean('dispatcherServer');
    }

    /**
     * @return \Swoft\Service\DispatcherService
     */
    public static function getDispatcherService()
    {
        return ApplicationContext::getBean('dispatcherService');
    }

    /**
     * 获取config bean
     *
     * @return Config
     */
    public static function getProperties()
    {
        return ApplicationContext::getBean('config');
    }

    /**
     * 初始化配置对象
     *
     * @param Config $properties 容器中config对象
     */
    public static function setProperties($properties = null)
    {
        if ($properties == null) {
            $properties = self::getProperties();
        }

        self::$properties = $properties;
    }

    /**
     * @return Config
     */
    public static function getAppProperties(): Config
    {
        return self::$appProperties;
    }

    /**
     * @param Config $appProperties
     */
    public static function setAppProperties(Config $appProperties)
    {
        self::$appProperties = $appProperties;
    }

    /**
     * 日志对象
     *
     * @return Logger
     */
    public static function getLogger()
    {
        return ApplicationContext::getBean('logger');
    }

    /**
     * the packer of rpc service
     *
     * @return \Swoft\Service\ServicePacker;
     */
    public static function getPacker()
    {
        return App::getBean('servicePacker');
    }

    /**
     * request对象
     *
     * @return Web\Request
     */
    public static function getRequest()
    {
        return RequestContext::getRequest();
    }

    /**
     * response对象
     *
     * @return Web\Response
     */
    public static function getResponse()
    {
        return RequestContext::getResponse();
    }

    /**
     * 获取定时器bean
     *
     * @return Timer
     */
    public static function getTimer()
    {
        return ApplicationContext::getBean('timer');
    }

    /**
     * 触发事件
     * @param string|\Swoft\Event\EventInterface $event 发布的事件名称|对象
     * @param mixed $target
     * @param array $params 附加数据信息
     * @return mixed
     */
    public static function trigger($event, $target = null, ...$params)
    {
        /** @var \Swoft\Event\EventManager $em */
        $em = ApplicationContext::getBean('eventManager');

        return $em->trigger($event, $target, $params);
    }

    /**
     * 语言翻译
     *
     * @param string $category 翻译文件类别，比如xxx.xx/xx
     * @param array  $params   参数
     * @param string $language 当前语言环境
     * @return string
     */
    public static function t(string $category, array $params, string $language = 'en')
    {
        return ApplicationContext::getBean('I18n')->translate($category, $params, $language);
    }

    /**
     * 注册多个别名
     *
     * @param array $aliases 别名数组
     *                       <pre>
     *                       [
     *                       '@root' => BASE_PATH
     *                       ......
     *                       ]
     *                       </pre>
     */
    public static function setAliases(array $aliases)
    {
        foreach ($aliases as $name => $path) {
            self::setAlias($name, $path);
        }
    }

    /**
     * 注册别名
     *
     * @param string $alias 别名
     * @param string $path  路径
     */
    public static function setAlias(string $alias, string $path = null)
    {
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }

        // 删除别名
        if ($path == null) {
            unset(self::$aliases[$alias]);

            return;
        }

        // $path不是别名，直接设置
        $isAlias = strpos($path, '@');
        if ($isAlias === false) {
            self::$aliases[$alias] = $path;

            return;
        }

        // $path是一个别名
        if (isset(self::$aliases[$path])) {
            self::$aliases[$alias] = self::$aliases[$path];

            return;
        }

        list($root) = explode('/', $path);
        if (!isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException("设置的根别名不存在，alias=" . $root);
        }

        $rootPath  = self::$aliases[$root];
        $aliasPath = str_replace($root, "", $path);

        self::$aliases[$alias] = $rootPath . $aliasPath;
    }

    /**
     * 获取别名路径
     *
     * @param string $alias
     *
     * @return string
     */
    public static function getAlias(string $alias)
    {
        if (isset(self::$aliases[$alias])) {
            return self::$aliases[$alias];
        }

        // $path不是别名，直接返回
        $isAlias = strpos($alias, '@');
        if ($isAlias === false) {
            return $alias;
        }

        list($root) = explode('/', $alias);
        if (!isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException("设置的根别名不存在，alias=" . $root);
        }

        $rootPath  = self::$aliases[$root];
        $aliasPath = str_replace($root, "", $alias);
        $path      = $rootPath . $aliasPath;

        return $path;
    }

    /**
     * trace级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function trace($message, array $context = array())
    {
        self::getLogger()->addTrace($message, $context);
    }

    /**
     * error级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function error($message, array $context = array())
    {
        self::getLogger()->error($message, $context);
    }

    /**
     * info级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function info($message, array $context = array())
    {
        self::getLogger()->info($message, $context);
    }

    /**
     * warning级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function warning($message, array $context = array())
    {
        self::getLogger()->warning($message, $context);
    }

    /**
     * debug级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function debug($message, array $context = array())
    {
        self::getLogger()->debug($message, $context);
    }

    /**
     * 标记日志
     *
     * @param string $key 统计key
     * @param mixed  $val 统计值
     */
    public static function pushlog($key, $val)
    {
        self::getLogger()->pushLog($key, $val);
    }

    /**
     * 统计标记开始
     *
     * @param string $name 标记名
     */
    public static function profileStart(string $name)
    {
        self::getLogger()->profileStart($name);
    }

    /**
     * 统计标记结束
     *
     * @param string $name 标记名，必须和开始标记名称一致
     */
    public static function profileEnd($name)
    {
        self::getLogger()->profileEnd($name);
    }

    /**
     * @return bool 当前是否是worker状态
     */
    public static function isWorkerStatus()
    {
        if (self::$server == null) {
            return false;
        }
        $server = self::$server->getServer();

        if ($server != null && property_exists($server, 'taskworker') && $server->taskworker == false) {
            return true;
        }

        return false;
    }

    /**
     * 命中率计算
     *
     * @param string $name  名称
     * @param int    $hit   命中
     * @param int    $total 总共
     */
    public static function counting(string $name, int $hit, $total = null)
    {
        self::getLogger()->counting($name, $hit, $total);
    }
}

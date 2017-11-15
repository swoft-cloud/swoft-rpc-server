<?php
/**
 * User: 黄朝晖
 * Date: 2017-11-13
 * Time: 3:30
 */

namespace Swoft\Testing;


class SwooleResponse extends \Swoole\Http\Response
{

    public $fd;
    public $header;
    public $cookie;
    public $trailer;

    /**
     * 结束Http响应，发送HTML内容
     * @param string $html
     */
    public function end($html = '')
    {
    }

    /**
     * 启用Http-Chunk分段向浏览器发送数据
     * @param $html
     */
    public function write($html)
    {
    }

    /**
     * 设置Http头信息
     *
     * @param      $key
     * @param      $value
     * @param null $ucwords
     */
    public function header($key, $value, $ucwords = NULL)
    {
    }

    /**
     * 设置Cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     */
    public function cookie($name, $value = NULL, $expires = NULL, $path = NULL, $domain = NULL, $secure = NULL, $httponly = NULL)
    {
    }

    /**
     * 设置HttpCode，如404, 501, 200
     * @param $code
     */
    public function status($code)
    {

    }

    /**
     * 设置Http压缩格式
     * @param int $level
     */
    function gzip($level = 1)
    {

    }

    /**
     * 发送静态文件
     *
     * @param      $filename
     * @param null $offset
     * @param null $length
     * @internal param string $level
     */
    function sendfile($filename, $offset = NULL, $length = NULL)
    {

    }

}
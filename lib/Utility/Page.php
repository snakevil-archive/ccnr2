<?php
/**
 * 定义抽象远端页面组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Utility;

use Zen\Core as ZenCore;

/**
 * 抽象远端页面组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
abstract class Page extends ZenCore\Component
{
    /**
     * 页面类型。
     *
     * @var string
     */
    const TYPE = '';

    /**
     * 数据驱动组件实例。
     *
     * @var PageDriver
     */
    protected $driver;

    /**
     * 最后更新时间。
     *
     * @var ZenCore\Type\DateTime
     */
    protected $lastModified;

    /**
     * 获取最后更新时间。
     *
     * @internal
     *
     * @return ZenCore\Type\DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * 解析指定页面地址。
     *
     * @param  string $uri 页面地址
     * @return self
     *
     * @throws ExPageDriverMissing 当内容分析驱动组件找不到时
     * @throws ExCurlFailure       当多次尝试抓取远端页面均失败时
     */
    final public static function parse($uri)
    {
        $a_parts = parse_url($uri);
        if (!isset($a_parts['scheme'])) {
            $uri = 'http://' . $uri;
            $a_parts = parse_url($uri);
        }
        $c_driver = array_slice(explode('.', $a_parts['host']), -2, 1)[0];
        if (is_numeric($c_driver[0])) {
            $c_driver = '_' . $c_driver;
        }
        $c_driver = 'snakevil\ccnr2\Driver\\'
            . $c_driver . '\\'
            . static::TYPE;
        if (!class_exists($c_driver)) {
            throw new ExPageDriverMissing($a_parts['host']);
        }
        $i_retries = 0;
        while (true) {
            ++$i_retries;
            try {
                list($o_time, $s_lob) = self::curl($uri);
                break;
            } catch (ExCurlFailure $ee) {
                if (2 < $i_retries) {
                    throw $ee;
                }
            }
        }
        $o_this = new static(new $c_driver($s_lob, $uri));
        $o_this->lastModified = $o_time;

        return $o_this;
    }

    /**
     * 构造函数
     *
     * @param PageDriver $driver 数据驱动组件实例
     */
    final protected function __construct(PageDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * 抓取指定页面地址内容。
     *
     * @internal
     *
     * @param  string $uri 页面地址
     * @return array
     *
     * @throws ExCurlFailure 当抓取远端页面失败时
     */
    final protected static function curl($uri)
    {
        $r_curl = curl_init($uri);
        curl_setopt_array(
            $r_curl,
            array(
                CURLOPT_FAILONERROR => true,
                CURLOPT_FILETIME => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_ENCODING => '',
                CURLOPT_USERAGENT => 'Sosospider+(+http://help.soso.com/webspider.htm)'
            )
        );
        $m_ret = curl_exec($r_curl);
        $i_ts = curl_getinfo($r_curl, CURLINFO_FILETIME);
        curl_close($r_curl);
        if (false === $m_ret) {
            throw new ExCurlFailure($uri);
        }
        $o_time = new ZenCore\Type\DateTime;
        if (-1 != $i_ts) {
            $o_time->setTimestamp($i_ts);
        }

        return array($o_time, $m_ret);
    }
}

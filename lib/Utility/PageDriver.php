<?php
/**
 * 定义抽象页面内容分析驱动组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Utility;

use ArrayAccess;

use Zen\Core as ZenCore;

/**
 * 抽象页面内容分析驱动组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
abstract class PageDriver extends ZenCore\Component implements ArrayAccess
{
    /**
     * 元信息集合。
     *
     * @var mixed[]
     */
    protected $metas;

    /**
     * 判断指定元素是否存在。
     *
     * @param  scalar $offset 元素名
     * @return bool
     */
    final public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->metas);
    }

    /**
     * 获取指定元素值。
     *
     * @param  scalar $offset 元素名
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->metas[$offset];
        }
    }

    /**
     * 设置指定元素。
     *
     * @param  scalar $offset 元素名
     * @param  mixed  $value  新值
     * @return void
     */
    final public function offsetSet($offset, $value)
    {
    }

    /**
     * 删除指定元素。
     *
     * @param  scalar $offset 元素名
     * @return void
     */
    final public function offsetUnset($offset)
    {
    }

    /**
     * 构造函数
     *
     * @param string $clob 待分析地内容代码
     */
    final public function __construct($clob)
    {
        $this->metas = $this->parse($clob);
    }

    /**
     * 分析指定内容 HTML 代码中地元信息。
     *
     * @param  string  $clob 待分析地内容代码
     * @return mixed[]
     */
    abstract protected function parse($clob);

    /**
     * 根据指定正则表达式匹配代码，并返回第一次匹配结果。
     *
     * @param  string        $clob    待分析地内容代码
     * @param  string        $pattern 正则表达式
     * @param  bool          $reverse 可选。是否将待分析地内容代码截取保留至第一次匹配之前
     * @return string[]|bool
     */
    final protected function estrstr(& $clob, $pattern, $reverse = false)
    {
        if (!preg_match($pattern, $clob, $a_ret)) {
            return false;
        }
        if ($reverse) {
            $clob = strstr($clob, $a_ret[0], true);
        } else {
            $i_pos = strpos($clob, $a_ret[0]);
            $clob = substr($clob, $i_pos + strlen($a_ret[0]));
        }

        return $a_ret;
    }
}

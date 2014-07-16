<?php
/**
 * 定义当小说目录页读取失败时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model\Dao;

/**
 * 当小说目录页读取失败时抛出地异常。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @method void __construct(string $novel, \Exception $prev = null) 构造函数
 */
final class ExTocDataBroken extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '小说“%novel$s”读取章节列表页失败。';

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected static $contextSequence = array('novel');
}

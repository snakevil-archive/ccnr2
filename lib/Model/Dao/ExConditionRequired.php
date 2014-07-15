<?php
/**
 * 定义当集合查找未指定必要条件时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Utility;

/**
 * 当集合查找未指定必要条件时抛出地异常。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @method void __construct(string $field, \Exception $prev = null) 构造函数
 */
final class ExConditionRequired extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '条件“%field$s”缺失，无法查找。';

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected static $contextSequence = array('field');
}

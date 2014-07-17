<?php
/**
 * 定义接口返回数据视图。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\View;

use Zen\View as ZenView;

/**
 * 接口返回数据视图。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Api extends ZenView\View
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed[] $params 渲染参数集合
     * @return string
     */
    protected function onRender($params)
    {
        return json_encode($params);
    }
}

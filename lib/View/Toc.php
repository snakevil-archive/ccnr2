<?php
/**
 * 定义章节列表页视图。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\View;

use Zen\View as ZenView;

/**
 * 章节列表页视图。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class Toc extends ZenView\View
{
    /**
     * {@inheritdoc}
     *
     * @param mixed[] $params 渲染参数集合
     *
     * @return string
     */
    protected function onRender($params)
    {
        return str_replace('<Novel>',
            "<?xml-stylesheet type=\"text/xsl\" href=\"../toc.xslt\"?>\n<Novel>",
            file_get_contents("var/db/${params[novel]}/toc.xml")
        );
    }
}

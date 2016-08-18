<?php
/**
 * 定义章节阅读页视图。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\View;

use Zen\View as ZenView;

/**
 * 章节阅读页视图。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class Chapter extends ZenView\View
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
        list($s_novel, $s_id) = explode('#', $params['chapter']);

        return str_replace('<Chapter ',
            "<?xml-stylesheet type=\"text/xsl\" href=\"/n/_/chapter.xslt\"?>\n<Chapter toc=\"/n/$s_novel/\" ",
            file_get_contents("var/db/$s_novel/$s_id.xml")
        );
    }
}

<?php
/**
 * 定义章节阅读页视图。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\View;

use DOMDocument;
use XSLTProcessor;

use Zen\View as ZenView;

/**
 * 章节阅读页视图。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Chapter extends ZenView\View
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed[] $params 渲染参数集合
     * @return string
     */
    protected function onRender($params)
    {
        $o_xml = new DOMDocument;
        $o_xml->load('var/db/' . str_replace('#', '/', $params['chapter']) . '.xml');
        $o_xsl = new DOMDocument;
        $o_xsl->load('share/xslt/chapter.xslt');
        $o_xslt = new XSLTProcessor;
        $o_xslt->setParameter(
            '',
            array(
                'toc' => realpath('var/db/' . $params['chapter']->novel . '/toc.xml'),
                'dev' => isset($params['@dev']) && $params['@dev']
            )
        );
        $o_xslt->importStyleSheet($o_xsl);

        return str_replace(
            array(
                '<link rel="stylesheet" href="//s.szen.in/n/ccnr2.min.css">',
                '<script src="//s.szen.in/n/ccnr2.min.js"></script>'
            ),
            array(
                '<style>' . file_get_contents('share/static/ccnr2.min.css') . '</style>',
                '<script>' . file_get_contents('share/static/ccnr2.min.js') . '</script>'
            ),
            $o_xslt->transformToXML($o_xml)
        );
    }
}

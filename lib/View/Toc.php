<?php
/**
 * 定义章节列表页视图。
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
 * 章节列表页视图。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Toc extends ZenView\View
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed[] $params 渲染参数集合
     * @return string
     */
    protected function onRender($params)
    {
        $s_xml = <<<XML
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Novel>
  <Title><![CDATA[{$params['novel']['title']}]]></Title>
  <Author><![CDATA[{$params['novel']['author']}]]></Author>
  <Chapters>

XML;
        foreach ($params['novel']->getChapters() as $o_chapter) {
            $s_xml .= <<<XML
    <Chapter ref="{$o_chapter['ref']}"><![CDATA[{$o_chapter['title']}]]></Chapter>

XML;
        }
        $s_xml .= <<<XML
  </Chapters>
</Novel>

XML;

        $o_xml = new DOMDocument;
        $o_xml->loadXml($s_xml);
        $o_xsl = new DOMDocument;
        $o_xsl->load('share/static/xslt/toc.xslt');
        $o_xslt = new XSLTProcessor;
        $o_xslt->importStyleSheet($o_xsl);

        return $o_xslt->transformToXML($o_xml);
    }
}

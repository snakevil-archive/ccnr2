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
        $s_xml = <<<XML
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Chapter ref="{$params['chapter']['ref']}">
  <Title><![CDATA[{$params['chapter']['title']}]]></Title>
  <Paragraphs>

XML;
        foreach ($params['chapter']->paragraphs as $s_paragraph) {
            $s_xml .= <<<XML
    <Paragraph><![CDATA[{$s_paragraph}]]></Paragraph>

XML;
        }
        $s_xml .= <<<XML
  </Paragraphs>
</Chapter>

XML;

        $o_xml = new DOMDocument;
        $o_xml->loadXml($s_xml);
        $o_xsl = new DOMDocument;
        $o_xsl->load('share/static/xslt/chapter.xslt');
        $o_xslt = new XSLTProcessor;
        $o_xslt->setParameter('', 'toc', realpath('var/cache/' . $params['chapter']->novel . '/toc.xml'));
        $o_xslt->importStyleSheet($o_xsl);

        return $o_xslt->transformToXML($o_xml);
    }
}

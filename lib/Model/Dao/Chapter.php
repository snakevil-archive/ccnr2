<?php
/**
 * 定义章节数据访问对象。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model\Dao;

use DOMDocument;
use DOMXPath;

use snakevil\ccnr2;

/**
 * 章节数据访问对象。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Chapter extends ccnr2\Component\Dao
{
    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return int
     *
     * @throws ExConditionRequired     当未指定小说时
     * @throws ExConditionNotSupported 当小说判断运算符不为等于时
     */
    public function count($conditions, $limit = 0, $offset = 0)
    {
        if (!isset($conditions['novel'][0][0])) {
            throw new ExConditionRequired('novel');
        }
        if (ccnr2\Model\ChapterSet::OP_EQ != $conditions['novel'][0][0]) {
            throw new ExConditionNotSupported('novel', $conditions['novel'][0][0]);
        }
        Novel::singleton()->read($conditions['novel'][0][1]);
        $o_dom = new DOMDocument;
        $o_dom->load('var/cache/' . $conditions['novel'][0][1] . '/toc.xml', LIBXML_NOCDATA);
        $o_xp = new DOMXPath($o_dom);
        $o_nodes = $o_xp->query('/Novel/Chapters/Chapter');

        return $o_nodes->length;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @param  array[] $oders      可选。排序方案
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return array[]
     *
     * @throws ExConditionRequired     当未指定小说时
     * @throws ExConditionNotSupported 当小说判断运算符不为等于时
     */
    public function query($conditions, $orders = array(), $limit = 0, $offset = 0)
    {
        if (!isset($conditions['novel'][0][0])) {
            throw new ExConditionRequired('novel');
        }
        if (ccnr2\Model\ChapterSet::OP_EQ != $conditions['novel'][0][0]) {
            throw new ExConditionNotSupported('novel', $conditions['novel'][0][0]);
        }
        $p_toc = 'var/cache/' . $conditions['novel'][0][1] . '/toc.xml';
        if (!is_file($p_toc)) {
            Novel::singleton()->read($conditions['novel'][0][1]);
        }
        $o_dom = new DOMDocument;
        $o_dom->load($p_toc, LIBXML_NOCDATA);
        $o_xp = new DOMXPath($o_dom);
        $o_nodes = $o_xp->query('/Novel/Chapters/Chapter');
        $a_ret = array();
        foreach ($o_nodes as $o_node) {
            $a_ret[] = array(
                'id' => $o_node->getAttribute('id'),
                'title' => $o_node->textContent,
                'novel' => $conditions['novel'][0][1]
            );
        }

        return $a_ret;
    }
}

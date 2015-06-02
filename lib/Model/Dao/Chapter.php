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

use SimpleXMLElement;

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
     * @param  scalar   $id 编号
     * @return scalar[]
     */
    public function read($id)
    {
        list($s_novel, $s_chapter) = explode('#', $id);
        $p_chapter = 'var/db/' . $s_novel . '/' . $s_chapter . '.xml';
        $a_ret = array(
            'id' => $id,
            'novel' => $s_novel
        );
        if (is_file($p_chapter)) {
            $a_ret['lastModified'] = filemtime($p_chapter);
            $o_sxe = new SimpleXMLElement($p_chapter, LIBXML_NOCDATA, true);
            $a_ret['ref'] = $o_sxe->xpath('/Chapter/@ref')[0];
            $a_ret['title'] = $o_sxe->xpath('/Chapter/Title')[0];
            $a_pgs = array();
            foreach ($o_sxe->xpath('/Chapter/Paragraphs/Paragraph') as $ii) {
                $a_pgs[] = (string) $ii;
            }
            $a_ret['paragraphs'] = json_encode($a_pgs);
        } else {
            $p_src = 'var/db/' . $s_novel . '/SOURCE';
            $p_src_ = $p_src . '_';
            if (is_file($p_src_) && is_readable($p_src_)) {
                rename($p_src_, $p_src);
            }
            if (!is_file($p_src) || !is_readable($p_src)) {
                throw new ExTocDataBroken($s_novel);
            }
            $p_toc = 'var/db/' . $s_novel . '/toc.xml';
            if (!is_file($p_toc)) {
                Novel::singleton()->read($s_novel);
            }
            $o_sxe = new SimpleXMLElement($p_toc, LIBXML_NOCDATA, true);
            $a_ret['ref'] = $o_sxe->xpath('/Novel/Chapters/Chapter[position()=' . $s_chapter . ']/@ref')[0];
            $o_chapter = ccnr2\Utility\ChapterPage::parse(trim(file_get_contents($p_src)) . $a_ret['ref']);
            $a_xml = array(
                'name' => 'Chapter',
                'attributes' => array(
                    'ref' => $a_ret['ref']
                ),
                'children' => array(
                    array(
                        'name' => 'Title',
                        'cdata' => $o_chapter->title
                    ),
                    array(
                        'name' => 'Paragraphs',
                        'children' => array()
                    )
                )
            );
            $a_pgs = array();
            foreach ($o_chapter->paragraphs as $ii) {
                $a_xml['children'][1]['children'][] = array(
                    'name' => 'Paragraph',
                    'cdata' => $ii
                );
                $a_pgs[] = $ii;
            }
            $s_lob = $this->xml($a_xml);
            if (!file_put_contents($p_chapter, $s_lob)) {
                throw new ExChapterDataBroken($id);
            }
            touch($p_chapter, $o_chapter->lastModified->getTimestamp());
            $a_ret['title'] = $o_chapter->title;
            $a_ret['paragraphs'] = json_encode($a_pgs);
            $a_ret['lastModified'] = $o_chapter->lastModified->getTimestamp();
        }

        return $a_ret;
    }

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
        $p_toc = 'var/db/' . $conditions['novel'][0][1] . '/toc.xml';
        if (!is_file($p_toc)) {
            Novel::singleton()->read($conditions['novel'][0][1]);
        }
        $o_sxe = new SimpleXMLElement($p_toc, LIBXML_NOCDATA, true);

        return count($o_sxe->xpath('/Novel/Chapters/Chapter'));
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
        $p_toc = 'var/db/' . $conditions['novel'][0][1] . '/toc.xml';
        if (!is_file($p_toc)) {
            Novel::singleton()->read($conditions['novel'][0][1]);
        }
        $o_sxe = new SimpleXMLElement($p_toc, LIBXML_NOCDATA, true);
        $a_ret = array();
        $ii = 0;
        foreach ($o_sxe->xpath('/Novel/Chapters/Chapter') as $o_node) {
            $a_ret[] = array(
                'id' => $conditions['novel'][0][1] . '#' . (++$ii),
                'title' => $o_node,
                'ref' => $o_node->xpath('@ref')[0],
                'novel' => $conditions['novel'][0][1],
                'paragraphs' => '[]',
                'lastModified' => 0
            );
        }

        return $a_ret;
    }
}

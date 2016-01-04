<?php
/**
 * 定义小说数据访问对象。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model\Dao;

use SimpleXMLElement;

use snakevil\ccnr2;

/**
 * 小说数据访问对象。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Novel extends ccnr2\Component\Dao
{
    /**
     * {@inheritdoc}
     *
     * @param  scalar   $id 编号
     * @return scalar[]
     *
     * @throws ExTocDataBroken 当数据读取失败时
     */
    public function read($id)
    {
        $p_toc = 'var/db/' . $id . '/toc.xml';
        $a_ret = array('id' => $id);
        if (is_file($p_toc)) {
            $a_ret['lastModified'] = filemtime($p_toc);
            $o_sxe = new SimpleXMLElement($p_toc, LIBXML_NOCDATA, true);
            $a_ret['title'] = $o_sxe->xpath('/Novel/Title')[0];
            $a_ret['author'] = $o_sxe->xpath('/Novel/Author')[0];
        } else {
            $p_src = 'var/db/' . $id . '/SOURCE';
            $p_src_ = $p_src . '_';
            if (is_file($p_src_) && is_readable($p_src_)) {
                rename($p_src_, $p_src);
            }
            if (!is_file($p_src) || !is_readable($p_src)) {
                throw new ExTocDataBroken($id);
            }
            $o_toc = ccnr2\Utility\TocPage::parse(trim(file_get_contents($p_src)));
            $a_xml = array(
                'name' => 'Novel',
                'children' => array(
                    array(
                        'name' => 'Title',
                        'cdata' => $o_toc->title
                    ),
                    array(
                        'name' => 'Author',
                        'cdata' => $o_toc->author
                    ),
                    array(
                        'name' => 'Chapters',
                        'children' => array()
                    )
                )
            );
            foreach ($o_toc->chapters as $ii => $jj) {
                $a_xml['children'][2]['children'][] = array(
                    'name' => 'Chapter',
                    'cdata' => $jj,
                    'attributes' => array(
                        'ref' => $ii
                    )
                );
            }
            $s_lob = $this->xml($a_xml);
            if (!file_put_contents($p_toc, $s_lob)) {
                throw new ExTocDataBroken($id);
            }
            touch($p_toc, $o_toc->lastModified->getTimestamp());
            touch($p_src, time());
            $a_ret['title'] = $o_toc->title;
            $a_ret['author'] = $o_toc->author;
            $a_ret['lastModified'] = $o_toc->lastModified->getTimestamp();
        }

        return $a_ret;
    }
}

<?php
/**
 * 定义小说数据访问对象。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model\Dao;

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
        $p_src = 'var/cache/' . $id . '/SOURCE';
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
        /** @var $jj Type **/
        foreach ($o_toc->chapters as $ii => $jj) {
            $a_xml['children'][2]['children'][] = array(
                'name' => 'Chapter',
                'cdata' => $jj,
                'attributes' => array(
                    'id' => $ii
                )
            );
        }
        $s_lob = $this->xml($a_xml);
        if (!file_put_contents('var/cache/' . $id . '/toc.xml', $s_lob)) {
            throw new ExTocDataBroken($id);
        }

        return array(
            'id' => $id,
            'title' => $o_toc->title,
            'author' => $o_toc->author
        );
    }
}

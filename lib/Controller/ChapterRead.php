<?php
/**
 * 定义小说章节阅读控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use snakevil\ccnr2;

/**
 * 小说章节阅读控制器。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class ChapterRead extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return ccnr2\View\Chapter
     */
    protected function onGet()
    {
        $s_id = $this->token['novel'] . '#' . $this->token['chapter'];
        $p_cache = $this->token['novel'] . '/' . $this->token['chapter'] . '.html';
        $o_chapter = ccnr2\Model\Chapter::load($s_id);
        $o_view = new ccnr2\View\Chapter(
            array(
                'chapter' => $o_chapter
            )
        );
        $this->cache($o_view, $p_cache, $o_chapter->lastModified);

        return $o_view;
    }
}

<?php
/**
 * 定义小说章节数据控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use DateTime;
use DateTimeZone;
use snakevil\ccnr2;

/**
 * 小说剩余章节查询控制器。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class ChapterData extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return zen\View\Json
     */
    protected function onGET()
    {
        $s_id = $this->token['novel'].'#'.$this->token['chapter'];
        $p_cache = $this->token['novel'].'/'.$this->token['chapter'].'.json';
        $o_chapter = ccnr2\Model\Chapter::load($s_id);
        $o_view = new ccnr2\View\ChapterData(
            array(
                'chapter' => $o_chapter,
            )
        );
        $this->cache($o_view, $p_cache, $o_chapter->lastModified);
        $o_tlm = clone $o_chapter->lastModified;
        $o_tlm->setTimezone(new DateTimeZone('GMT'));
        $o_tnow = new DateTime('+'.$this->config['caching.page'].' secs UTC');
        $this->output
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Last-Modified', $o_tlm->format('D, d M Y H:i:s').' GMT')
            ->header('Expires', $o_tnow->format('D, d M Y H:i:s').' GMT')
            ->header('Cache-Control', 'max-age='.$this->config['caching.page']);

        return $o_view;
    }
}

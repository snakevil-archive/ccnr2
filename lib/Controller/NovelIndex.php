<?php
/**
 * 定义小说章节列表控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use DateTime;
use snakevil\ccnr2;

/**
 * 小说章节列表控制器。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class NovelIndex extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return ccnr2\View\TOC
     */
    protected function onGet()
    {
        $o_novel = ccnr2\Model\Novel::load($this->token['novel']);
        $o_view = new ccnr2\View\Toc(
            array(
                '@dev' => $this->inDev(),
                'novel' => $o_novel,
            )
        );
        $o_tnow = new DateTime('+'.$this->config['caching.page'].' secs UTC');
        $this->output
            ->header('Content-Type', 'text/xml; charset=utf-8')
            ->header('Last-Modified', $o_novel->lastModified->format('D, d M Y H:i:s').' GMT')
            ->header('Expires', $o_tnow->format('D, d M Y H:i:s').' GMT')
            ->header('Cache-Control', 'max-age='.$this->config['caching.page']);

        return $o_view;
    }
}

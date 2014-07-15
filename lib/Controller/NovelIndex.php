<?php
/**
 * 定义小说章节列表控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use snakevil\ccnr2;

/**
 * 小说章节列表控制器。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @throws ExUnknownNovelCode 当小说代号无法识别时
 */
class NovelIndex extends ccnr2\Component\Controller
{
    protected function onGet()
    {
        return new ccnr2\View\TOC(
            array(
                'novel' => ccnr2\Model\Novel::load($this->token['novel'])
            )
        );
    }
}

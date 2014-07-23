<?php
/**
 * 定义首页控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use snakevil\ccnr2;

/**
 * 首页控制器。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Index extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return ccnr2\View\TOC
     */
    protected function onGet()
    {
        if (preg_match('|^[a-z]+=(http://)?(\w+\.)+\w+/.+$|', $this->input['server:QUERY_STRING'])) {
            $o_ctrl = new NovelFound($this->appliance);

            return $o_ctrl->act($this->token);
        }
    }
}

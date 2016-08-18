<?php
/**
 * 定义抽象控制器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Component;

use snakevil\zen;

/**
 * 抽象控制器组件。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
abstract class Controller extends zen\Controller\Web
{
    /**
     * {@inheritdoc}
     */
    protected function onClose()
    {
        if (isset($this->input['server:HTTP_ACCEPT_ENCODING'])) {
            $this->output->header('Vary', 'Accept-Encoding');
        }
    }
}

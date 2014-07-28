<?php
/**
 * 定义小说剩余章节查询控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Controller;

use DateTime;
use DateTimeZone;
use snakevil\zen;
use snakevil\ccnr2;

/**
 * 小说剩余章节查询控制器。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class ChapterOffset extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return ccnr2\View\Api
     */
    protected function onGet()
    {
        $o_novel = ccnr2\Model\Novel::load($this->token['novel']);
        $b_mod = !isset($this->input['server:HTTP_IF_MODIFIED_SINCE'])
            || $o_novel->lastModified->getTimestamp() > strtotime($this->input['server:HTTP_IF_MODIFIED_SINCE']);
        if ($b_mod) {
            $i_num = count($o_novel->getChapters()) - $this->token['chapter'];
        } else {
            $this->output->state(304);
        }
        $o_tlm = clone $o_novel->lastModified;
        $o_tlm->setTimezone(new DateTimeZone('GMT'));
        $o_tnow = new DateTime('+' . $this->config['caching.api'] . ' secs UTC');
        $this->output
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Last-Modified', $o_tlm->format('D, d M Y H:i:s') . ' GMT')
            ->header('Expires', $o_tnow->format('D, d M Y H:i:s') . ' GMT')
            ->header('Cache-Control', 'max-age=' . $this->config['caching.api']);
        if ($b_mod) {
            return new zen\View\Json(
                array(
                    'quantity' => $i_num
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  \Exception     $ee 捕获地异常
     * @return ccnr2\View\Api
     */
    protected function onError(\Exception $ee)
    {
        $this->output
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:01 GMT')
            ->header('Cache-Control', 'max-age=0');

        return new zen\View\Json(
            array(
                'quantity' => 0
            )
        );
    }
}

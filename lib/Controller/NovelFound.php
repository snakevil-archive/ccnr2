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
 */
class NovelFound extends ccnr2\Component\Controller
{
    /**
     * {@inheritdoc}
     *
     * @return ccnr2\View\TOC
     *
     * @throws ExStorageFailure 当数据写入失败时
     */
    protected function onGet()
    {
        parse_str($this->input['server:QUERY_STRING'], $a_args);
        list($s_id, $s_src) = each($a_args);
        $p_db = 'var/db/' . $s_id;
        $p_src = $p_db . '/SOURCE';
        if (!is_file($p_src)) {
            if (!is_dir($p_db) && !mkdir($p_db, 0755, true)) {
                throw new ExStorageFailure;
            }
            if (!file_put_contents($p_src, $s_src)) {
                rmdir($p_db);
                throw new ExStorageFailure;
            }
        }

        return $this->output->redirect('./' . $s_id . '/');
    }
}

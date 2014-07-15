<?php
/**
 * 定义飘天文学站小说目录页内容分析组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Driver\piaotian;

use snakevil\ccnr2;

/**
 * 飘天文学站小说目录页内容分析组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Toc extends ccnr2\Utility\PageDriver
{
    /**
     * {@inheritdoc}
     *
     * @param  string $clob 待分析地 HTML 代码
     * @return mixed
     *
     * @throws ExNovelTitleNotFound    当小说标题找不到时
     * @throws ExNovelAuthorNotFound   当小说作者找不到时
     * @throws ExNovelChaptersNotFound 当小说章节列表找不到时
     */
    protected function parse($clob)
    {
        $clob = iconv('gbk', 'utf-8//TRANSLIT', $clob);
        $a_ret = array();
        $a_match = $this->estrstr($clob, '|<title>(\S+)最新章节,|U');
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExTitleNotFound;
        }
        $a_ret['title'] = $a_match[1];
        $a_match = $this->estrstr($clob, '|<meta name="author" content="(\S+)" />|U');
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExNovelAuthorNotFound;
        }
        $a_ret['author'] = $a_match[1];
        $a_match = $this->estrstr($clob, '|<div class="list">正文</div>|');
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound;
        }
        $a_match = $this->estrstr($clob, '|<div class="bottom">\s+①若读者发现有小说|U', true);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound;
        }
        if (false === preg_match_all('|<li><a href="(\d+\.html)">(.+)</a></li>|U', $clob, $a_match)) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound;
        }
        $a_ret['chapters'] = array();
        for ($ii = 0, $jj = count($a_match[1]); $ii < $jj; $ii++) {
            $a_ret['chapters'][$a_match[1][$ii]] = $a_match[2][$ii];
        }

        return $a_ret;
    }
}

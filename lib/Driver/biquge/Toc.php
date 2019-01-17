<?php
/**
 * 定义笔趣阁小说目录页内容分析组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Driver\biquge;

use snakevil\ccnr2;

/**
 * 笔趣阁小说目录页内容分析组件。
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
        $a_ret = array();
        $s_regex = '|<meta property="og:title" content="(.+)"/>|U';
        $a_match = $this->estrstr($clob, $s_regex);
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExNovelTitleNotFound($this->ref, $s_regex);
        }
        $a_ret['title'] = $this->trim($a_match[1]);
        $s_regex = '|<meta property="og:novel:author" content="(.+)"/>|U';
        $a_match = $this->estrstr($clob, $s_regex);
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExNovelAuthorNotFound($this->ref, $s_regex);
        }
        $a_ret['author'] = $this->trim($a_match[1]);
        $s_regex = '~<div id="list">~';
        $a_match = $this->estrstr($clob, $s_regex);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $s_regex = '|<div class="dahengfu">|U';
        $a_match = $this->estrstr($clob, $s_regex, true);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $s_regex = '|<dd><a href="(\d+\.html)" title="[^"]+">(.+)</a></dd>|U';
        if (false === preg_match_all($s_regex, $clob, $a_match)) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $a_ret['chapters'] = array();
        for ($ii = 0, $jj = count($a_match[1]); $ii < $jj; $ii++) {
            $a_ret['chapters'][$a_match[1][$ii]] = $this->trim($a_match[2][$ii]);
        }

        return $a_ret;
    }
}

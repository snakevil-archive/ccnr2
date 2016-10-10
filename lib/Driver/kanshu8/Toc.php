<?php
/**
 * 定义看书吧网小说目录页内容分析组件。
 *
 * @author    Yao <yaogaoyu@gmail.com>
 * @copyright © 2016 yaogd.com
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Driver\kanshu8;

use snakevil\ccnr2;

/**
 * 看书吧网小说目录页内容分析组件。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class Toc extends ccnr2\Utility\PageDriver
{
    /**
     * {@inheritdoc}
     *
     * @param string $clob 待分析地 HTML 代码
     *
     * @return mixed
     *
     * @throws ExNovelTitleNotFound    当小说标题找不到时
     * @throws ExNovelAuthorNotFound   当小说作者找不到时
     * @throws ExNovelChaptersNotFound 当小说章节列表找不到时
     */
    protected function parse($clob)
    {
        $clob = iconv('gb18030', 'utf-8//TRANSLIT', $clob);
        $a_ret = array();
        $s_regex = '|<TITLE>(\S+)最新章节|U';
        $a_match = $this->estrstr($clob, $s_regex);
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExNovelTitleNotFound($this->ref, $s_regex);
        }
        $a_ret['title'] = $this->trim($a_match[1]);
        $s_regex = '|<span>作者：(\S+)</span>|U';
        $a_match = $this->estrstr($clob, $s_regex);
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExNovelAuthorNotFound($this->ref, $s_regex);
        }
        $a_ret['author'] = $this->trim($a_match[1]);
        $s_regex = '|<TABLE cellSpacing=1 cellPadding=1 style="MARGIN-BOTTOM: 10px" align=center>|';
        $a_match = $this->estrstr($clob, $s_regex);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $s_regex = '|<!-- Duoshuo Comment BEGIN -->|U';
        $a_match = $this->estrstr($clob, $s_regex, true);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $s_regex = '|<DIV class=dccss>\s*<a href="(\d+\.html)">(.+)</a>|U';
        if (false === preg_match_all($s_regex, $clob, $a_match)) {
            throw new ccnr2\Driver\ExNovelChaptersNotFound($this->ref, $s_regex);
        }
        $a_ret['chapters'] = array();
        for ($ii = 0, $jj = count($a_match[1]); $ii < $jj; ++$ii) {
            $a_ret['chapters'][$a_match[1][$ii]] = $this->trim($a_match[2][$ii]);
        }

        return $a_ret;
    }
}

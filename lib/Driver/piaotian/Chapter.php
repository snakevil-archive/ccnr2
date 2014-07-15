<?php
/**
 * 定义飘天文学站小说章节页内容分析组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Driver\piaotian;

use snakevil\ccnr2;

/**
 * 飘天文学站小说章节页内容分析组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class Chapter extends ccnr2\Utility\PageDriver
{
    /**
     * {@inheritdoc}
     *
     * @param  string $clob 待分析地 HTML 代码
     * @return mixed
     *
     * @throws ExChapterTitleNotFound      当章节标题找不到时
     * @throws ExChapterParagraphsNotFound 当章节段落列表找不到时
     */
    protected function parse($clob)
    {
        $clob = iconv('gbk', 'utf-8//TRANSLIT', $clob);
        $a_ret = array();
        $a_match = $this->estrstr($clob, '@<h1><a href=".+">\S+</a>\s*(?:|正文\s+)(\S+)</h1>@Ui');
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExChapterTitleNotFound;
        }
        $a_ret['title'] = $a_match[1];
        $a_match = $this->estrstr($clob, '|<div class="bottomlink">|', true);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExChapterParagraphsNotFound;
        }
        if (false === preg_match_all('@(?:&nbsp;){4}(.+)(?:<br(?:| /)>|\n</div>)@U', $clob, $a_match)) {
            throw new ccnr2\Driver\ExChapterParagraphsNotFound;
        }
        $a_ret['paragraphs'] = array();
        for ($ii = 0, $jj = count($a_match[1]); $ii < $jj; $ii++) {
            $a_ret['paragraphs'][] = $a_match[1][$ii];
        }

        return $a_ret;
    }
}

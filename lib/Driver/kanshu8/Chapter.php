<?php
/**
 * 定义看书吧网小说章节页内容分析组件。
 *
 * @author    Yao <yaogaoyu@gmail.com>
 * @copyright © 2016 yaogd.com
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Driver\kanshu8;

use snakevil\ccnr2;

/**
 * 看书吧网小说章节页内容分析组件。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class Chapter extends ccnr2\Utility\PageDriver
{
    /**
     * {@inheritdoc}
     *
     * @param string $clob 待分析地 HTML 代码
     *
     * @return mixed
     *
     * @throws ExChapterTitleNotFound      当章节标题找不到时
     * @throws ExChapterParagraphsNotFound 当章节段落列表找不到时
     */
    protected function parse($clob)
    {
        $clob = iconv('gb18030', 'utf-8//IGNORE', $clob);
        $a_ret = array();
        $s_regex = '@<H2>(.+)</H2>@U';
        $a_match = $this->estrstr($clob, $s_regex);
        if (!isset($a_match[1])) {
            throw new ccnr2\Driver\ExChapterTitleNotFound($this->ref, $s_regex);
        }
        $a_ret['title'] = $this->trim($a_match[1]);
        $s_regex = '@<P>@';
        $a_match = $this->estrstr($clob, $s_regex);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExChapterParagraphsNotFound($this->ref, $s_regex);
        }
        $s_regex = '@</P>@';
        $a_match = $this->estrstr($clob, $s_regex, true);
        if (false === $a_match) {
            throw new ccnr2\Driver\ExChapterParagraphsNotFound($this->ref, $s_regex);
        }
        $clob .= '<br>';
        $s_regex = '@(?:&nbsp;){4}(.+)(?:<br(?:| ?/)>)@U';
        if (false === preg_match_all($s_regex, $clob, $a_match)) {
            throw new ccnr2\Driver\ExChapterParagraphsNotFound($this->ref, $s_regex);
        }
        $a_ret['paragraphs'] = array();
        for ($ii = 0, $jj = count($a_match[1]); $ii < $jj; ++$ii) {
            if ('' != $a_match[1][$ii]) {
                $a_ret['paragraphs'][] = $a_match[1][$ii];
            }
        }

        return $a_ret;
    }
}

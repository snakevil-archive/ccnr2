<?php
/**
 * 定义章节数据接口视图。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\View;

use Zen\View as ZenView;

/**
 * 章节阅读页视图。
 *
 * @version 2.0.0
 *
 * @since   2.0.0
 */
class ChapterData extends ZenView\View
{
    /**
     * {@inheritdoc}
     *
     * @param mixed[] $params 渲染参数集合
     *
     * @return string
     */
    protected function onRender($params)
    {
        $a_ret = array(
            'n' => $params['chapter']->novel->title,
            't' => $params['chapter']->title,
            'p' => $params['chapter']->paragraphs,
            '-' => '#',
            '+' => '#',
        );
        $b_hit = false;
        foreach ($params['chapter']->novel->getChapters() as $o_chapter) {
            if ($b_hit) {
                $a_ret['+'] = array_pop(explode('#', $o_chapter->id));
                break;
            }
            if ($o_chapter->id == $params['chapter']->id) {
                $b_hit = true;
                continue;
            }
            $a_ret['-'] = array_pop(explode('#', $o_chapter->id));
        }

        return json_encode($a_ret);
    }
}

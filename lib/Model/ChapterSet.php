<?php
/**
 * 定义章节模型组合。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model;

use Zen\Model as ZenModel;

/**
 * 章节模型组合。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
class ChapterSet extends ZenModel\Set
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    const MODEL_CLASS = 'snakevil\ccnr2\Model\Chapter';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @return Dao\Chapter
     */
    protected function newDao()
    {
        return Dao\Chapter::singleton();
    }
}

<?php
/**
 * 定义远端章节页面组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Utility;

/**
 * 远端章节页面组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @property-read string                  $title        章节名
 * @property-read string[]                $paragraphs   段落
 * @property-read \Zen\Core\Type\DateTime $lastModified 最后更新时间
 */
class ChapterPage extends Page
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    const TYPE = 'Chapter';

    /**
     * 小说名。
     *
     * @internal
     *
     * @var string
     */
    protected $title;

    /**
     * 章节列表。
     *
     * @internal
     *
     * @var string[]
     */
    protected $paragraphs;

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  scalar $property 属性名
     * @return mixed
     */
    protected function onGetProperty($property)
    {
        switch ($property) {
            case 'title':
            case 'paragraphs':
                $this->$property = $this->driver[$property];
                break;
            default:
                return;
        }

        return $this->driver[$property];
    }
}

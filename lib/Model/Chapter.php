<?php
/**
 * 定义章节模型。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model;

use snakevil\ccnr2;

/**
 * 章节模型。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @property-read string $id     章节编号
 * @property-read string $title  章节标题
 * @property-read Novel  $novel  隶属小说
 */
class Chapter extends ccnr2\Component\Model
{
    /**
     * 小说标题。
     *
     * @internal
     *
     * @var string
     */
    protected $title;

    /**
     * 隶属小说。
     *
     * @internal
     *
     * @var Novel
     */
    protected $novel;

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
            case 'id':
            case 'title':
                break;
            case 'novel':
                if (!$this->novel instanceof Novel) {
                    $this->novel = Novel::load($this->novel);
                }
                break;
            default:
                return;
        }

        return $this->$property;
    }

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

    /**
     * 获取所有章节集合。
     *
     * @return ChapterSet
     */
    public function getParagraphs()
    {
        return ParagraphSet::all()->filterEq('chapter', $this);
    }
}

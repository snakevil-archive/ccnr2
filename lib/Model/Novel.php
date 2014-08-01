<?php
/**
 * 定义小说模型。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Model;

use Zen\Core as ZenCore;
use snakevil\zen;

/**
 * 小说模型。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 *
 * @property-read string                $id           小说编号
 * @property-read string                $title        小说标题
 * @property-read string                $author       作者名
 * @property-read ZenCore\Type\DateTime $lastModified 最后修改时间
 */
class Novel extends zen\Model
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
     * 作者名。
     *
     * @internal
     *
     * @var string
     */
    protected $author;

    /**
     * 最后修改时间。
     *
     * @internal
     *
     * @var ZenCore\Type\DateTime
     */
    protected $lastModified;

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  scalar $property 属性名
     * @return mixed
     */
    protected function zenGet($property)
    {
        switch ($property) {
            case 'id':
            case 'title':
            case 'author':
                break;
            case 'lastModified':
                if (!$this->lastModified instanceof ZenCore\Type\DateTime) {
                    $o_time = new ZenCore\Type\DateTime;
                    $o_time->setTimestamp($this->lastModified);
                    $this->lastModified = $o_time;
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
     * @return Dao\Novel
     */
    protected function newDao()
    {
        return Dao\Novel::singleton();
    }

    /**
     * 获取所有章节集合。
     *
     * @return ChapterSet
     */
    public function getChapters()
    {
        return ChapterSet::all()->filterEq('novel', $this);
    }
}

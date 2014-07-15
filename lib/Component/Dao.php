<?php
/**
 * 定义抽象数据访问对象组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Component;

use XMLWriter;

use Zen\Model as ZenModel;

/**
 * 抽象数据访问对象组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
abstract class Dao extends ZenModel\Dao\Dao
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed[] $fields 字段值集合
     * @return void
     *
     * @throws ExEntityReadOnly 当创建实体时
     */
    final public function create($fields)
    {
        throw new ExEntityReadOnly;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar   $id 编号
     * @return scalar[]
     */
    public function read($id)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar  $id     编号
     * @param  mixed[] $fields 字段值集合
     * @return void
     *
     * @throws ExEntityReadOnly 当更新实体时
     */
    final public function update($id, $fields)
    {
        throw new ExEntityReadOnly;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar $id 编号
     * @return void
     *
     * @throws ExEntityReadOnly 当删除实体时
     */
    final public function delete($id)
    {
        throw new ExEntityReadOnly;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return int
     */
    public function count($conditions, $limit = 0, $offset = 0)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @param  array[] $oders      可选。排序方案
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return array[]
     */
    public function query($conditions, $orders = array(), $limit = 0, $offset = 0)
    {
        return array();
    }

    /**
     * 将数据转化成 XML 格式。
     *
     * @param  array $xml 写入地数据
     * @return void
     */
    final protected function xml($data)
    {
        $o_xml = new XMLWriter;
        $o_xml->openMemory();
        $o_xml->startDocument('1.0', 'utf-8', 'yes');
        $this->addXml($o_xml, $data);
        $o_xml->endDocument();

        return $o_xml->outputMemory();
    }

    /**
     * 添加标签及其后代。
     *
     * @param  XMLWriter $xml  XML 操作组件
     * @param  array     $data 标签数据
     * @return void
     */
    final protected function addXml(XMLWriter $xml, $data)
    {
        $xml->startElement($data['name']);
        if (array_key_exists('attributes', $data)) {
            foreach ($data['attributes'] as $ii => $jj) {
                $xml->writeAttribute($ii, $jj);
            }
        }
        if (array_key_exists('children', $data)) {
            for ($ii = 0, $jj = count($data['children']); $ii < $jj; $ii++) {
                $this->addXml($xml, $data['children'][$ii]);
            }
        }
        if (array_key_exists('cdata', $data)) {
            $xml->writeCdata($data['cdata']);
        }
        $xml->endElement();
    }
}

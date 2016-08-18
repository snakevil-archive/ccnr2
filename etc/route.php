<?php
/**
 * 路由表。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */
$NS = 'snakevil\ccnr2\Controller\\';

return array(
    '/n/' => array(
        '' => $NS.'Index',
        '(?P<novel>\w+)/' => array(
            '' => $NS.'NovelIndex',
            '(?P<chapter>\d+)' => $NS.'ChapterRead',
        ),
    ),
);

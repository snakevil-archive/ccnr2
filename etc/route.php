<?php
/**
 * 路由表。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

$NS = 'snakevil\ccnr2\\';

return array(
    '/n/' => array(
        '\.cache/' => array(
            '(?P<novel>\w+)/' => array(
                'index\.html' => $NS . 'Controller\NovelIndex',
                '(?P<chapter>\d+)\.html' => $NS . 'Controller\ChapterRead'
            )
        )
    )
);

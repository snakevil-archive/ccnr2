<?php
/**
 * 单一入口程序。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2;

chdir(dirname(__DIR__));

require_once 'include/autoload.php';

Appliance::run('etc/config.php', 'etc/route.php');

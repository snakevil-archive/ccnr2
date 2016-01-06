#!/bin/sh
# 更新连载小说的 TOC 数据文件。
#
# @author    Snakevil Zen <zsnakevil@gmail.com>
# @copyright © 2016 SZen.in
# @license   GPL-3.0+
# @license   CC-BY-NC-ND-3.0

LOG=log/`'basename' "$0" .sh`.log

cd `'dirname' "$0"`/../../var;

'rm' -fr cache/*

'find' db -mindepth 2 -maxdepth 2 -type f -name 'SOURCE' -mmin +60 \
  | while read id; do
    id=`'basename' $('dirname' "$id")`;
    toc="db/$id/toc.xml";
    [ ! -f "$toc" ] || {
        'rm' -f "$toc";
        #'curl' "http://szen.in/n/$id/" > /dev/null 2>&1;
        echo `'date' '+%FT%T%:z'`" $id" >> "$LOG";
    }
  done

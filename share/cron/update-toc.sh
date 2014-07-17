#!/bin/sh
# 更新连载小说的 TOC 数据文件。
#
# @author    Snakevil Zen <zsnakevil@gmail.com>
# @copyright © 2014 SZen.in
# @license   GPL-3.0+
# @license   CC-BY-NC-ND-3.0

cd `'dirname' "$0"`/../../var;

'find' db -mindepth 2 -maxdepth 2 -type f -name 'SOURCE' \
  | while read fi; do \
      di=`'dirname' "$fi"`; \
      ID=`'basename' "$di"`; \
      cd "cache/$ID"; \
      fo=`'ls' *.html | 'sort' -rn | 'head' -n1`; \
      cd ../..; \
      'rm' -f "$di/toc.xml" "cache/$ID/index.html" "cache/$ID/$fo"; \
    done

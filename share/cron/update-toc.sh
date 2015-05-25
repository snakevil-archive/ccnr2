#!/bin/sh
# 更新连载小说的 TOC 数据文件。
#
# @author    Snakevil Zen <zsnakevil@gmail.com>
# @copyright © 2014 SZen.in
# @license   GPL-3.0+
# @license   CC-BY-NC-ND-3.0

LOG=log/`'basename' "$0" .sh`.log

cd `'dirname' "$0"`/../../var;

'find' db -mindepth 2 -maxdepth 2 -type f -name 'SOURCE' \
  | while read fi; do \
      di=`'dirname' "$fi"`; \
      ID=`'basename' "$di"`; \
      fs=''; \
      fd="db/$ID/toc.xml"; \
      [ ! -f "$fd" ] || { \
        fs="$fs "`'basename' "$fd"`; \
        'rm' -f "$fd"; \
        'curl' "http://szen.in/n/$ID/" > /dev/null 2>&1; \
      }; \
      [ ! -d "cache/$ID" ] || { \
        fd="cache/$ID/index.html"; \
        [ ! -f "$fd" ] || { \
          fs="$fs "`'basename' "$fd"`; \
          'rm' -f "$fd"; \
        }; \
        fo=`cd "db/$ID"; 'basename' $('ls' *.xml 2>/dev/null | 'sort' -rn | 'head' -n1) .xml`; \
        fd="cache/$ID/$fo.html"; \
        [ ! -f "$fd" ] || { \
          fs="$fs "`'basename' "$fd"`; \
          'rm' -f "$fd"; \
        }; \
      }; \
      [ -n "$fs" ] || fs=' SKIPPED'; \
      fs=":$fs"; \
      echo `'date' '+%FT%T%:z'`" $ID$fs" >> "$LOG"; \
    done

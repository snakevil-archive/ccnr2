'use strict';
(function ($, _procedures, _chain, _call) {
    // 动态功能池。
    _procedures = [],
    // 链式对象。
    _chain = $.Deferred().resolve(),
    // 执行功能。
    _call = function (_, page, procedure, _current, _done) {
        _current = $.Deferred(),
        _done = function () {
            _current.resolve();
        };
        _chain.done(function () {
            switch (typeof page) {
                case 'function':
                    procedure = page;
                    page = true;
                    break;
                case 'string':
                    page = page == '*' || page == _.p;
                    break;
                case 'object':
                    page = -1 != $.inArray(_.p, page);
                    break;
                default:
                    page = false;
            }
            if (!page)
                return _done();
            procedure.call(_, $, _done);
        });
        _chain = _current;
    };
    return {
        d: document,
        l: location,
        h: history,

        // 页面类型。
        p: (function (_page) {
            $.each($('html').attr('class').split(/\s+/), function (index, value) {
                if (_page)
                    return;
                value = value.split('-');
                if ('page' != value[0] || !value[1])
                    return;
                _page = value[1];
            });
            return _page || 'unknown';
        }()),

        // 注册动态功能。
        on: function (page, procedure, _this) {
            _this = this;
            _procedures.push([page, procedure]);
            _call(_this, page, procedure);
            return _this;
        },
        // 注册静态功能。
        once: function (page, procedure, _this) {
            _this = this;
            _call(_this, page, procedure);
            return _this;
        },

        // 错误警告
        e: function (message, _floor, _height, _css, $_error, $_panel, _width, _size) {
            $('body').append(
                '<div class="error"><div class="_"></div>' +
                '<dl><dt><span class="ico ico-sad"></span></dt>' +
                '<dd>' + message + '</dd>' +
                '<dd>请<a href="#" onclick="location.reload();return 0">刷新页面</a>以尝试解决这个问题。</dd>' +
                '</dl></div>'
            );
            _floor = 'floor',
            _height = 'height',
            _css = 'css',
            $_error = $('.error'),
            $_page = $_error.children('dl'),
            _width = $_error.width(),
            _size = Math.min(480, _width - 40);
            $_panel[_css]({
                width: _size,
                left: Math[_floor]((_width - _size) / 2)
            });
            $_panel[_css]('top', Math[_floor](($_error[_height]() - $_panel[_height]() - 40) / 2));
            $_error[_css]({
                top: 0,
                left: 0
            });
        },

        // 抑制默认行为的空的事件处理器
        o: function () {
            return false;
        },

        // 章节列表数据
        t: false,

        // 按键值记录
        w: -1,

        // 预加载章节数据状态
        s: false,

        // 还原历史状态
        r: function (state, _this, _text) {
            _this = this;
            if ($('h2').text() == state[1]) return;
            _this.d.title = state[0] + ' ' + state[1] + ' | CCNR v2';
            $('html').removeClass('prefetched');
            $('.badge').addClass('hidden');
            $('article').html(
                '<h2>' + state[1] + '</h2>' +
                $.map(state[2], function (p) {
                    return '<p>' + p + '</p>';
                }).join('')
            );
            _this.w = -1,
            _this.s = false;
            $.each(_procedures, function (index, procedure) {
                _call(_this, procedure[0], procedure[1]);
            });
        }
    };
}(jQuery))

// 赋予章节列表片段展开功能
.once('toc', function ($, done) {
    $('legend a').click(function () {
        $(this).closest('fieldset').addClass('expanded');
        return false;
    });
})

// 赋予「返回页首」按钮功能（检测页面是否发生滚屏）
.once(function ($, done, _scrolled, _disabled, _span, _top, _notop, _hasClass, _addClass, _removeClass, _children, $_body, $_html, $_top) {
    _scrolled = 'scrolled',
    _disabled = 'disabled',
    _span = 'span',
    _top = 'ico-top',
    _notop = 'ico-notop',
    _hasClass = 'hasClass',
    _addClass = 'addClass',
    _removeClass = 'removeClass',
    _children = 'children',
    $_body = $('body'),
    $_html = $_body.parent(),
    $_top = $('aside a:first').click(function () {
        if ($_html[_hasClass](_scrolled)) $_body.animate({ scrollTop: 0}, 250);
        return false;
    });
    $(window).scroll(function () {
        if (0 < $_body.scrollTop()) {
            $_html[_addClass](_scrolled);
            $_top[_removeClass](_disabled)
                [_children](_span)
                [_addClass](_top)
                [_removeClass](_notop);
        } else {
            $_html[_removeClass](_scrolled);
            $_top[_addClass](_disabled)
                [_children](_span)
                [_addClass](_notop)
                [_removeClass](_top);
        }
    });
    done();
})

// 加载外部样式
.once(function ($, done, $_head, _patch) {
    $_head = $('head'),
    _patch = function (text) {
        $('<style rel="stylesheet">' + text + '</text>').appendTo($_head);
    };
    $.each([
        '//cdn.bootcss.com/github-fork-ribbon-css/0.2.0/gh-fork-ribbon.min.css',
        '//fonts.gmirror.org/css?family=Space+Mono:400'
    ], function (index, url, _localStorage) {
        _localStorage = localStorage;
        if (_localStorage[url])
            return _patch(_localStorage[url]);
        $.get(url).done(function (data) {
            _localStorage[url] = data;
            _patch(data);
        });
    });
    done();
})

// 读取章节列表数据
.once('chapter', function ($, done, _this) {
    _this = this;
    $.get($('article').data('toc')).done(function (xml, _document, _title, $_xml) {
        _document = _this.d,
        _title = 'title',
        _this.t = $_xml = $(xml);
        _document[_title] = $_xml.find('Title').text() + ' '+ _document[_title];
        done();
    }).fail(function () {
        _this.e('章节列表数据读取失败。');
    });
})

// 修正底部导航面板中的前后章功能
.on('chapter', function ($, done, _this, _a, _href, _title, _disabled, __, _left, _right, _none, _attr, _addClass, _removeClass, _click, _off, _eq, _text, _index, $_chapters, $_a, $_badge) {
    _this = this,
    _a = 'aside a:eq(',
    _href = 'href',
    _title = 'title',
    _disabled = 'disabled',
    __ = '#',
    _left = '《',
    _right = '》',
    _none = '（无）',
    _attr = 'attr',
    _addClass = 'addClass',
    _removeClass = 'removeClass',
    _click = 'click',
    _off = 'off',
    _eq = 'eq',
    _text = 'text',
    _index = _this.l.pathname.split('/').pop() - 0,
    $_chapters = _this.t.find('Chapter'),
    $_a = $(_a + '1)');
    if (1 < _index) {
        $_a[_attr](_href, _index - 1)
            [_attr](_title, _left + $_chapters[_eq](_index - 2)[_text]() + _right)
            [_removeClass](_disabled)
            [_off](_click, _this.o);
    } else
        $_a[_attr](_href, __)
            [_attr](_title, _none)
            [_addClass](_disabled)
            [_click](_this.o);
    $_a = $(_a + '3)');
    if (_index < $_chapters.length) {
        $_a[_attr](_href, _index + 1)
            [_attr](_title, _left + $_chapters[_eq](_index)[_text]() + _right)
            [_removeClass](_disabled)
            [_off](_click, _this.o);
        $_badge = $_a.children('.badge')
            [_text]($_chapters.length - _index);
        if (!_this.d.referer && !_this.h.state)
            $_badge[_removeClass]('hidden');
    } else
        $_a[_attr](_href, __)
            [_attr](_title, _none)
            [_addClass](_disabled)
            [_click](_this.o);
    done();
})

// 显示底部导航面板并添加按键监听
.once(function ($, done, _this, _aside) {
    _this = this,
    _aside = 'aside';
    $(_aside).removeClass('hidden');
    $('body').keydown(function (event, _which, _index) {
        _which = 'which',
        _index = $.inArray(event[_which], [27, 37, 13, 39]);
        if (0 > _index || _index == _this.w)
            return;
        if (_index)
            _this.w = _index;
        $(_aside + ' a:eq(' + _index + ')')[0].click();
    });
    done();
})

// 转化为历史状态
.once('chapter', function ($, done, _this, _history, _state, _text, _replace, _data) {
    _this = this,
    _history = _this.h,
    _state = 'state',
    _text = 'text',
    _replace = 'replace',
    _data = [
        $('h2')[_text](),
        []
    ];
    if (!(_state in _history))
        return done();
    $(window).on('popstate', function (event) {
        _this.r(_history[_state]);
    });
    _data.unshift(_this.d.title.split(_data[0])[0][_replace](/\s*$/, ''));
    $('p').each(function (index, p) {
        _data[2].push($(p)[_text]());
    });
    _history.replaceState(_data, '', _this.l.href[_replace](/^.*\//, ''));
    done();
})

// 预加载并历史状态化下一章节
.on('chapter', function ($, done, _this, _history, _state, _index) {
    _this = this,
    _history = _this.h,
    _state = 'state',
    _index = $('aside a:last').attr('href');
    if (!_history[_state] || '#' == _index)
        return done();
    _this.s = [
        _history[_state][0],
        '',
        []
    ];
    $.get(_index).done(function (xml, _find, _text, $_xml) {
        _find = 'find',
        _text = 'text',
        $_xml = $(xml);
        _this.s[1] = $_xml[_find]('Title')[_text]();
        $_xml[_find]('Paragraph').each(function (index, p) {
            _this.s[2].push($(p)[_text]());
        });
        $('html').addClass('prefetched');
    });
    done();
})

// 调整已预加载地章节的切换模式
.once('chapter', function ($, done, _this, $_a) {
    _this = this,
    $_a = $('aside a:last');
    $_a.click(function (_data) {
        if (!$('html').hasClass('prefetched'))
            return true;
        _data = _this.s;
        _this.s = false;
        _this.h.pushState(_data, '', $_a.attr('href'));
        window.scrollTo(0, 0);
        _this.r(_data);
        $('.badge').addClass('hidden');
        return false;
    });
    done();
})

;

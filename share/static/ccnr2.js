'use strict';
(function ($) {
  return {
    // (P)age type
    p: (function (_page) {
      $.each($('html').attr('class').split(/\s+/), function (index, value) {
        if (_page) return;
        value = value.split('-');
        if ('page' != value[0] || !value[1]) return;
        _page = value[1];
      });
      return _page || 'unknown';
    }()),
    // (S)tatable
    s: 'pushState' in history,
    // (W)hich key code
    w: 0,
    // handlers (O)n different pages
    _o: [],
    // (D)eferred object for register queue
    _d: $.Deferred().resolve(),
    // (T)OC xml data
    _t: 0,
    // pre(F)etched next chapter
    // handler (I)nvoker
    _i: function (page, procedure, _this, _defer1) {
      _this = this;
      _defer1 = $.Deferred();
      _this._d.done(function (_defer2) {
        _defer2 = new $.Deferred();
        _defer2.done(function () {
          _defer1.resolve();
        });
        switch (typeof page) {
          case 'function':
            procedure = page;
            page = true;
            break;
          case 'string':
            page = page == '*' || page == _this.p;
            break;
          case 'object':
            page = -1 != $.inArray(_this.p, page);
            break;
          default:
            page = false;
        }
        if (!page) return _defer2.resolve();
        procedure.call(_this, $, _defer2.resolve);
      });
      _this._d = _defer1;
    },
    // register a repeatable handler for page states in variant pages
    on: function (page, procedure, _this) {
      _this = this;
      _this._o.push([page, procedure]);
      _this._i(page, procedure);
      return _this;
    },
    // register an once handler in variant pages
    once: function (page, procedure, _this) {
      _this = this;
      _this._i(page, procedure);
      return _this;
    },
    // (R)e-invoke handlers
    _r: function (_this) {
      _this = this;
      $.each(_this._o, function (index, hook) {
        _this._i(hook[0], hook[1]);
      });
    },
    // (R)estore previous state
    r: function (state, _html) {
      if ($('h2').text() == state.t) return;
      document.title = state.n + ' ' + state.t + ' | CCNR v2';
      _html = '<h2>' + state.t + '</h2>' +
        $.map(state.p, function (text) {
          return '<p>' + text + '</p>';
        }).join('') +
        '<footer><nav><ul>' +
        '<li><a href="' + state['-'] + '"';
      if ('#' != state['-']) _html += ' title="前一章"';
      _html += '><span class="iconfont icon-prev"></span></a></li>' +
        '<li><a href="." title="章节目录"><span class="iconfont icon-list"></span></a></li>' +
        '<li><a href="' + state['+'] + '"';
      if ('#' != state['+']) _html += ' title="后一章"';
      _html += '><span class="iconfont icon-next"></span></a></li>' +
        '</ul></nav></footer>';
      $('article').html(_html);
      this.w = 0;
      this._r();
    }
  };
}(jQuery))

// TOGGLEs novel title on scrolling
.once('toc', function ($, done, $_body) {
  $_body = $(document.body);
  $(window).scroll(function () {
    if (0 < window.scrollY) $_body.addClass('scrolled');
    else $_body.removeClass('scrolled');
  });
  done();
})

// FETCHes TOC xml data
.once('chapter', function ($, done, _this) {
  _this = this;
  $.get($('article').data('toc')).done(function (data, $_xml) {
    _this._t = $_xml = $(data);
    document.title = $_xml.find('Title').text() + ' ' + document.title; // ADD the novel title to the page title
    done();
  });
})

// SHOWs TOC links
.once('chapter', function ($, done, _this, _id, $_chapters, $_a, $_badge) {
  _this = this;
  _id = location.pathname.split('/').pop() - 0;
  $_chapters = _this._t.find('Chapter');
  $_a = $('footer nav a:first'); // ADD previous page link
  if (1 < _id) {
    $_a.attr('href', _id - 1)
      .attr('title', '《' + $($_chapters.get(_id - 2)).text() + '》');
  }
  $_a = $('footer nav a:last'); // ADD next page link
  if (_id < $_chapters.length) {
    $_a.attr('href', _id + 1)
      .attr('title', '《' + $($_chapters.get(_id + 1)).text() + '》');
    $_badge = $_a.children('.badge');
    $_badge.text($_chapters.length - _id);
    if (!document.referrer)
      $_badge.removeClass('hidden');
  }
  $('footer').removeClass('hidden'); // SHOW the nav links
  done();
})

// NAVIGATEs sibling chapter page on key press
.once('chapter', function ($, done, _this) {
  _this = this;
  $(document).keydown(function (event, _pos) {
    _pos = $.inArray(event.which, [37, 13, 39]);
    if (-1 == _pos || event.which == _this.w) return;
    _this.w = event.which;
    $('footer nav a:eq(' + _pos + ')').click();
  });
  done();
})

// AVOIDs links of illegal previous or next chapter
.on('chapter', function ($, done) {
  $('a[href="#"]').click(function () {
    return false;
  });
  done();
})

////////////////////////////////////////////////////////////// CHAPTER STATE ///

// CONVERTs to history state
.once('chapter', function ($, done, _this) {
  _this = this;
  if (!_this.s) return done();
  $(window).on('popstate', function (ev) {
    if (history.state) _this.r(history.state);
  });
  $(document).ready(function (_state) {
    _state = {
      t: $('h2').text(),
      p: [],
      '-': $('footer nav a:first').attr('href'),
      '+': $('footer nav a:last').attr('href')
    };
    _state.n = document.title.split(_state.t)[0].replace(/\s*$/, '');
    $('p').each(function (index, p) {
      _state.p.push($(p).text());
    });
    history.replaceState(_state, '', location.href.replace(/^.*\//, ''));
  });
  done();
})

// PREFETCHs the next chapter data
.on('chapter', function ($, done, _this, $_a, _id) {
  _this = this;
  $_a = $('footer nav a:last');
  _id = $_a.attr('href');
  if (!_this.s || '#' == _id) return done();
  _id -= 0;
  $.get(_id).done(function (data, $_xml, _state) {
    $_xml = $(data);
    _state = {
      n: history.state.n,
      t: $_xml.find('Title').text(),
      p: [],
      '-': '#',
      '+': '#'
    };
    $_xml.find('Paragraph').each(function (index, p) {
      _state.p.push($(p).text());
    });
    if (1 < _id)
      _state['-'] = _id - 1;
    if (_id < _this._t.find('Chapter').length)
      _state['+'] = _id + 1;
    $_a.attr('class', 'prefetched');
    _this._f = _state;
  });
  done();
})

// CHANGEs history state instead of page
.on('chapter', function ($, done, $_a, _this) {
  $_a = $('footer nav a:last[href!="#"]');
  _this = this;
  if (!_this.s) return done();
  $_a.click(function (_state) {
    _state = _this._f;
    if (!_state) return true;
    history.pushState(_state, '', $_a.attr('href'));
    window.scrollTo(0, 0);
    _this.r(_state);
    delete _this._f;
    return false;
  });
  done();
})
;

'use strict';
(function ($, $h) {
  $h = Hammer;
  return {
    page: (function (page) {
      $.each($('html').attr('class').split(/\s+/), function (index, value) {
        if (page) return;
        value = value.split('-');
        if ('page' != value[0] || !value[1]) return;
        page = value[1];
      });
      return page || 'unknown';
    }()),
    state: 'pushState' in history,
    which: 0,
    $h: new $h.Manager(document.body, {
      recognizers: [
        [$h.Tap],
        [$h.Swipe, {
          direction: $h.DIRECTION_HORIZONTAL
        }]
      ]
    }),
    _on: [],
    _invoke: function (page, procedure, _this) {
      _this = this;
      switch (typeof page) {
        case 'function':
          procedure = page;
          page = true;
          break;
        case 'string':
          page = page == '*' || page == _this.page;
          break;
        case 'object':
          page = -1 != $.inArray(_this.page, page);
          break;
        default:
          page = false;
      }
      if (page) procedure.call(_this, $);
    },
    on: function (page, procedure, _this) {
      _this = this;
      _this._on.push([page, procedure]);
      _this._invoke(page, procedure);
      return _this;
    },
    once: function (page, procedure) {
      this._invoke(page, procedure);
      return this;
    },
    refresh: function (_this) {
      _this = this;
      $.each(_this._on, function (index, hook) {
        _this._invoke(hook[0], hook[1]);
      });
    },
    update: function (state, html) {
      if ($('h2').text() == state.t) return;
      document.title = state.n + ' ' + state.t + ' | CCNR v2';
      html = '<h2>' + state.t + '</h2>' +
        $.map(state.p, function (text) {
          return '<p>' + text + '</p>';
        }).join('') +
        '<footer><nav><ul>' +
        '<li><a href="' + state['-'] + '"';
      if ('#' != state['-']) html += ' title="前一章"';
      html += '><span class="iconfont icon-prev"></span></a></li>' +
        '<li><a href="." title="《' + state.n + '》章节目录"><span class="iconfont icon-list"></span></a></li>' +
        '<li><a href="' + state['+'] + '"';
      if ('#' != state['+']) html += ' title="后一章"';
      html += '><span class="iconfont icon-next"></span></a></li>' +
        '</ul></nav></footer>';
      $('article').html(html);
      this.which = 0;
      this.refresh();
    }
  };
}(jQuery))

// TOGGLEs novel title on scrolling
.once('toc', function ($, $body) {
  $body = $(document.body);
  $(window).scroll(function () {
    if (0 < window.scrollY) $body.addClass('scrolled');
    else $body.removeClass('scrolled');
  });
})

// SHOWs incoming chapters badge in bookmarked chapter page
.once('chapter', function ($, $a) {
  $a = $('footer nav a:last');
  if (document.referrer || !$a.attr('href')) return;
  $.getJSON(location.href + '/cd', {}, function (data) {
    if (data.quantity) $a.children('.badge').text(data.quantity).removeClass('hidden');
  });
})

// NAVIGATEs sibling chapter page on key press
.once('chapter', function ($, _this) {
  _this = this;
  $(document).keydown(function (event, pos) {
    pos = $.inArray(event.which, [37, 13, 39]);
    if (-1 == pos || event.which == _this.which) return;
    _this.which = event.which;
    $('footer nav a:eq(' + pos + ')').click();
  });
})

// NAVIGATEs sibling chapter page on swiping
.once('chapter', function ($) {
  this.$h.on('swipeleft', function () {
    $('footer nav a:last').click();
  }).on('swiperight', function () {
    $('footer nav a:first').click();
  });
})

// DIMs read paragraphes temporarily
.once('chapter', function ($) {
  $(window).scroll(function (length) {
    length = window.scrollY;
    $('p').each(function (index, p, $p, offset, height) {
      $p = $(p);
      offset = $p.offset().top;
      if (offset > length || 'read' == $p.attr('class')) return;
      height = $p.height();
      if (length < offset + height) return;
      $p.attr('class', 'read');
    });
  });
})

// SCROLLs vertical further on tapping
.once('chapter', function ($) {
  this.$h.on('tap', function (event, distance) {
    distance = $(window).height();
    if ($(event.target).closest('ul').length) return;
    $('html, body').animate({
      scrollTop: (event.center.y * 2 > distance ? '+' : '-') + '=' + distance
    });
  });
})

// AVOIDs links of illegal previous or next chapter
.on('chapter', function ($) {
  $('a[href="#"]').click(function () {
    return false;
  });
})

////////////////////////////////////////////////////////////// CHAPTER STATE ///

// CONVERTs to history state
.once('chapter', function ($, _this) {
  _this = this;
  if (!_this.state) return;
  $(window).on('popstate', function (ev) {
    if (history.state) _this.update(history.state);
  });
  $(document).ready(function (id, state) {
    id = location.href.replace(/^.*\//, '');
    state = {
      t: $('h2').text(),
      p: [],
      '-': $('footer nav a:first').attr('href'),
      '+': $('footer nav a:last').attr('href')
    };
    state.n = document.title.split(state.t)[0].replace(/\s*$/, '');
    $('p').each(function (index, p) {
      state.p.push($(p).text());
    });
    history.replaceState(state, '', id);
  });
})

// PREFETCHs the next chapter data
.on('chapter', function ($, $a, id, _this) {
  $a = $('footer nav a:last');
  id = $a.attr('href');
  _this = this;
  if (!_this.state || '#' == id) return;
  $.getJSON(id + '.json', {}, function (data) {
    $a.attr('class', 'prefetched');
    _this.$state = data;
  });
})

// CHANGEs history state instead of page
.on('chapter', function ($, $a, _this) {
  $a = $('footer nav a:last[href!="#"]');
  _this = this;
  if (!_this.state) return;
  $a.click(function () {
    if (!_this.$state) return;
    history.pushState(_this.$state, '', $a.attr('href'));
    window.scrollTo(0, 0);
    _this.update(_this.$state);
    return false;
  });
})
;

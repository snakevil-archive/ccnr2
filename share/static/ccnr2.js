'use strict';
(function ($, ccnr2) {
  return {
    _page: (function (page) {
      $.each($('html').attr('class').split(/\s+/), function (index, value) {
        if (page) return;
        value = value.split('-');
        if ('page' != value[0] || !value[1]) return;
        page = value[1];
      });
      return page || 'unknown';
    }()),
    _hooks: [],
    _invoke: function (page, procedure) {
      switch (typeof page) {
        case 'function':
          procedure = page;
          page = true;
          break;
        case 'string':
          page = page == '*' || page == this._page;
          break;
        case 'object':
          page = -1 != $.inArray(this._page, page);
          break;
        default:
          page = false;
      }
      if (page) procedure.call(this, $, this._page);
    },
    on: function (page, procedure) {
      this._hooks.push([page, procedure]);
      this._invoke(page, procedure);
      return this;
    },
    refresh: function () {
      $.each(this._hooks, function (index, hook) {
        this._invoke(hook[0], hook[1]);
      });
    }
  };
}(jQuery))
// AVOIDs links of illegal previous or next chapter
.on('chapter', function ($) {
  $('a').click(function (ev, id) {
    id = $(this).attr('href');
    return '#' != id;
  });
})
// TOGGLEs novel title on scrolling
.on('toc', function ($, win, body) {
  win = window;
  body = $(document.body);
  $(win).scroll(function () {
    if (0 < win.scrollY) body.addClass('scrolled');
    else body.removeClass('scrolled');
  });
})
// SHOWs incoming chapters badge in chapter page
.on('chapter', function ($, a) {
  a = $('footer nav a:last');
  if (document.referrer || !a.attr('href')) return;
  $.getJSON(location.href + '/cd', {}, function (data) {
    if (data.quantity) a.children('.badge').text(data.quantity).removeClass('hidden');
  });
})
// PREPAREs keyboard navigation during chapter pages
.on('chapter', function ($, which) {
  $(document).keydown(function (event, pos) {
    pos = $.inArray(event.which, [37, 13, 39]);
    if (-1 == pos || event.which == which) return;
    which = event.which;
    $('footer nav a:eq(' + pos + ')')[0].click();
  });
})
;

'use strict';
(function ($, master) {
  master = {};
  $.each($('html').attr('class').split(/\s+/), function (index, value) {
    if (master.feature) return;
    value = value.split('-');
    if ('page' != value[0] || !value[1]) return;
    master.feature = value[1];
  });
  master.counter = 0;
  master.on = function (ability, worker) {
    master.counter++;
    switch (typeof ability) {
      case 'function':
        worker = ability;
        ability = true;
        break;
      case 'string':
        ability = ability == '*' || ability == master.feature;
        break;
      case 'object':
        ability = -1 != $.inArray(master.feature, ability);
        break;
      default:
        ability = false;
    }
    if (ability) worker($, master.feature);
    return master;
  };
  return master;
}(jQuery))
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

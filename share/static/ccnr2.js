jQuery(document).ready(function ($, doc, anav) {
  doc = document;
  anav = 'article footer nav ';

  // Hacks As' click() event
  $('a').click(function (ev) {
    if (!ev.toElement) return this.click();
  });

  // Navigates chapters on keyboard
  var code = 0;
  $(doc).keydown(function (ev) {
    var ift = $.inArray(ev.which, [37, 13, 39]); // Left, Return, Right
    if (-1 == ift || ev.which == code) return;
    code = ev.which;
    $(anav + 'a.btn:eq(' + ift + ')').click();
  });

  // Pops countdown badge of incoming chapters
  (function () {
    if (!doc.referrer && $(anav + 'a.btn:last').attr('href'))
      $.getJSON(location.href + '/cd', {}, function (data) {
        if (data.quantity)
          $(anav + '.badge').text(data.quantity).removeClass('hidden');
      });
  }());
});

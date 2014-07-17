jQuery(document).ready(function ($) {
  var code = 0;
  $('a').click(function (ev) {
    if (!ev.toElement) return this.click();
  });
  $(document).keydown(function (ev) {
    var ift = $.inArray(ev.which, [37, 13, 39]); // Left, Return, Right
    if (-1 == ift) return;
    if (ev.which == code) return;
    code = ev.which;
    $('article footer nav a.btn:eq(' + ift + ')').click();
  });
});

// Generated by CoffeeScript 1.6.3
(function() {
  jQuery(function($) {
    var $video, ratio;
    $video = $("iframe");
    ratio = 640 / 480;
    $(window).resize(function() {
      var h, w;
      w = $(window).width();
      h = $(window).height();
      if (w / h >= ratio) {
        $video[0].width = w;
        $video[0].height = w / ratio;
        $video[0].style.marginLeft = '0px';
        return $video[0].style.marginTop = (-(w / ratio - h) / 2) + 'px';
      } else {
        $video[0].height = h;
        $video[0].width = h * ratio;
        $video[0].style.marginTop = '0px';
        return $video[0].style.marginLeft = (-(h * ratio - w) / 2) + 'px';
      }
    });
    return $(window).trigger('resize');
  });

}).call(this);

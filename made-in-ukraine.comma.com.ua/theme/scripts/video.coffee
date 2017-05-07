jQuery ($) ->

  $video = $("iframe")
  ratio = 640 / 480;

  $(window).resize ->
    w = $(window).width()
    h = $(window).height()

    if w/h >= ratio
      $video[0].width = w
      $video[0].height = w / ratio
      $video[0].style.marginLeft = '0px'
      $video[0].style.marginTop = (-(w / ratio - h) / 2) + 'px'
    else
      $video[0].height = h
      $video[0].width = h * ratio
      $video[0].style.marginTop = '0px'
      $video[0].style.marginLeft = (-(h * ratio - w) / 2) + 'px'


  $(window).trigger('resize')

jQuery ($) ->

  activeClass = "list-item--active"
  $current = $ ".list .list-item.#{activeClass}"

  lockId = no

  prev = ->
    return unless $current.prev().length
    $current.removeClass activeClass
    $current = $current.prev().addClass activeClass

  next = ->
    return unless $current.next().length
    $current.removeClass activeClass
    $current = $current.next().addClass activeClass

  $(".list").bind
    mousewheel: (e) ->
      e.preventDefault()
      delta = e.originalEvent.deltaY

      return if Math.abs(delta) < 0.001

      unless lockId
        lockId = setTimeout (-> lockId = null), 1200
        next() if delta > 0
        prev() if delta < 0

  $(".js-link-next").click next
  $(".js-link-prev").click prev

  $(".js-share-facebook").click ->
    window.social.share.Facebook share: $(@).parent().data()
    no

  $(".js-share-vkontakte").click ->
    window.social.share.Vkontakte share: $(@).parent().data()
    no

  $(".js-share-twitter").click ->
    share = $(@).parent().data()
    share.description = share.twitterDescription || ""
    window.social.share.Twitter share: share
    no

  $(".tracks-share").click ->
    $track = $(@).parents ".tracks-track"
    $track.find(".tracks-track-share").addClass "tracks-track-share--active"
    $track.one 'mouseleave', ->
      $track.find(".tracks-track-share").removeClass "tracks-track-share--active"
    no

  class Player

    listeners: []
    index: null
    playing: false

    constructor: ( @tracks ) ->
      @audio = new Audio
      @audio.volume = .7

      @audio.addEventListener 'timeupdate', =>
        @trigger "timeupdate",  @toTime(Math.floor @audio.currentTime), @audio.currentTime / @audio.duration

      @audio.addEventListener 'ended', => @next()

    addTrack: ( src ) -> @tracks.push src

    toTime: (time) ->
      s = time % 60
      m = Math.floor time / 60

      s = "0" + s if s < 10
      m = "0" + m if m < 10

      "#{m}:#{s}"

    next: ->
      if @index+1 >= @tracks.length
        @stop()
        return no

      @play @index + 1

    volume: (vol) -> @audio.volume = vol

    prev: ->
      if @audio.currentTime > 2
        @seek 0
        return no
      else if @index <= 0
        @stop()
        return no

      @play @index - 1

    togglePlay: ->
      if @playing
        @pause()
      else
        @play()

    stop: ->
      @audio.pause()
      @audio.currentTime = 0
      @playing = no
      @trigger 'stop'

    pause: ->
      @audio.pause()
      @playing = no
      @trigger 'pause'

    seek: (pos) ->
      return unless @audio.duration
      pos = Math.min 1, Math.max 0, pos
      @audio.currentTime = pos * @audio.duration

    play: (index = null) ->
      index = 0 if index is null and @index is null

      if index isnt null
        @audio.src = @tracks[index].src
        @index = index
      else
        index = @index

      @audio.play()
      @playing = yes
      @trigger 'play', @tracks[index]

    addEventListener: (type, callback) ->
      @listeners[type] = [] unless @listeners[type]
      @listeners[type].push callback

    trigger: (type, args ... ) ->
      callback.apply @, args for callback in @listeners[type] if @listeners[type]
      no


  $(".js-player").each ->
    tracks = $(@).data 'tracks'

    return unless tracks

    $time = $(".js-player-time")
    $play = $(".js-player-playBtn")
    $pause = $(".js-player-pauseBtn")
    $name = $(".js-player-trackName")
    $progress = $(".js-player-progressBar")
    $next = $(".js-player-nextBtn")
    $prev = $(".js-player-prevBtn")
    $duration = $(".js-player-duration")
    $progressBar = $(".header-player-progress")
    $volumeBar = $(".header-player-volume-line")
    $volumeBarValue = $(".header-player-volume-value")

    $tracks = $(".js-track")
    tracksById = []
    $tracks.each -> tracksById[$(@).data 'trackId'] = $(@)

    player = new Player tracks

    player.addEventListener "play", (track) ->
      $name.text track.name
      $pause.show()
      $play.hide()
      $time.show().text "00:00"
      $duration.text track.duration
      $tracks.filter(".tracks-track--active").find(".icon-font-pause").hide()
      $tracks.filter(".tracks-track--active").find(".icon-font-play").show()
      $tracks.removeClass 'tracks-track--active'
      tracksById[track.id].addClass 'tracks-track--active'

      tracksById[track.id].find(".icon-font-pause").show()
      tracksById[track.id].find(".icon-font-play").hide()

    player.addEventListener "pause", ->
      $play.show()
      $pause.hide()

      $tracks.filter(".tracks-track--active").find(".icon-font-pause").hide()
      $tracks.filter(".tracks-track--active").find(".icon-font-play").show()

    player.addEventListener "stop", ->
      $play.show()
      $pause.hide()
      $time.hide()
      $tracks.removeClass 'tracks-track--active'

    player.addEventListener "timeupdate", (time, progress) ->
      $time.text time
      $progress.css width: progress * 100 + "%"

    $pause.click -> player.pause()
    $play.click -> player.play()
    $next.click -> player.next()
    $prev.click -> player.prev()

    getIndexById = (id) ->
      for index, track of tracks
        return parseInt index if parseInt(track.id) is parseInt(id)

      false

    $progressBar.click (e) ->
      x = e.pageX - $progressBar.offset().left
      player.seek x / $progressBar.width()

    $tracks.click ->
      index = getIndexById $(@).data 'trackId'
      return if index is false

      if player.index is index
        player.togglePlay()
      else
        player.play index

      no


    if volume = $.cookie "volume"
      volume = Math.min 1, Math.max 0, volume
      $volumeBarValue.css height: 100 * volume + "%"
      player.volume volume

    height = $volumeBar.height()
    setVol = (pageY) ->
      pos = (height - pageY + $volumeBar.offset().top) / height
      pos = Math.min 1, Math.max 0, pos
      $volumeBarValue.css height: 100 * pos + "%"
      player.volume pos

      $.cookie "volume", pos

    $volumeBar.bind
      touchstart: ->
        height = $volumeBar.height()
        $volumeBar.parent().addClass 'header-player-volume--active'

        $("body").bind 'touchmove.volume', (e) ->
          setVol e.originalEvent.targetTouches[0].pageY
          e.preventDefault()

        $("body").one 'touchend', ->
          $volumeBar.parent().removeClass 'header-player-volume--active'

    $volumeBar.mousedown ->
      height = $volumeBar.height()
      $volumeBar.parent().addClass 'header-player-volume--active'

      $("body").bind 'mousemove.volume', (e) ->
        setVol e.pageY

      $("body").one 'mouseup', ->
        $("body").unbind 'mousemove.volume'
        $volumeBar.parent().removeClass 'header-player-volume--active'

      no

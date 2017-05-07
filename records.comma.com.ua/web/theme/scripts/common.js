// Generated by CoffeeScript 1.10.0
(function() {
  var slice = [].slice;

  jQuery(function($) {
    var $current, Player, activeClass, lockId, next, prev;
    activeClass = "list-item--active";
    $current = $(".list .list-item." + activeClass);
    lockId = false;
    prev = function() {
      if (!$current.prev().length) {
        return;
      }
      $current.removeClass(activeClass);
      return $current = $current.prev().addClass(activeClass);
    };
    next = function() {
      if (!$current.next().length) {
        return;
      }
      $current.removeClass(activeClass);
      return $current = $current.next().addClass(activeClass);
    };
    $(".list").bind({
      mousewheel: function(e) {
        var delta;
        e.preventDefault();
        delta = e.originalEvent.deltaY;
        if (Math.abs(delta) < 0.001) {
          return;
        }
        if (!lockId) {
          lockId = setTimeout((function() {
            return lockId = null;
          }), 1200);
          if (delta > 0) {
            next();
          }
          if (delta < 0) {
            return prev();
          }
        }
      }
    });
    $(".js-link-next").click(next);
    $(".js-link-prev").click(prev);
    $(".js-share-facebook").click(function() {
      window.social.share.Facebook({
        share: $(this).parent().data()
      });
      return false;
    });
    $(".js-share-vkontakte").click(function() {
      window.social.share.Vkontakte({
        share: $(this).parent().data()
      });
      return false;
    });
    $(".js-share-twitter").click(function() {
      var share;
      share = $(this).parent().data();
      share.description = share.twitterDescription || "";
      window.social.share.Twitter({
        share: share
      });
      return false;
    });
    $(".tracks-share").click(function() {
      var $track;
      $track = $(this).parents(".tracks-track");
      $track.find(".tracks-track-share").addClass("tracks-track-share--active");
      $track.one('mouseleave', function() {
        return $track.find(".tracks-track-share").removeClass("tracks-track-share--active");
      });
      return false;
    });
    Player = (function() {
      Player.prototype.listeners = [];

      Player.prototype.index = null;

      Player.prototype.playing = false;

      function Player(tracks1) {
        this.tracks = tracks1;
        this.audio = new Audio;
        this.audio.volume = .7;
        this.audio.addEventListener('timeupdate', (function(_this) {
          return function() {
            return _this.trigger("timeupdate", _this.toTime(Math.floor(_this.audio.currentTime)), _this.audio.currentTime / _this.audio.duration);
          };
        })(this));
        this.audio.addEventListener('ended', (function(_this) {
          return function() {
            return _this.next();
          };
        })(this));
      }

      Player.prototype.addTrack = function(src) {
        return this.tracks.push(src);
      };

      Player.prototype.toTime = function(time) {
        var m, s;
        s = time % 60;
        m = Math.floor(time / 60);
        if (s < 10) {
          s = "0" + s;
        }
        if (m < 10) {
          m = "0" + m;
        }
        return m + ":" + s;
      };

      Player.prototype.next = function() {
        if (this.index + 1 >= this.tracks.length) {
          this.stop();
          return false;
        }
        return this.play(this.index + 1);
      };

      Player.prototype.volume = function(vol) {
        return this.audio.volume = vol;
      };

      Player.prototype.prev = function() {
        if (this.audio.currentTime > 2) {
          this.seek(0);
          return false;
        } else if (this.index <= 0) {
          this.stop();
          return false;
        }
        return this.play(this.index - 1);
      };

      Player.prototype.togglePlay = function() {
        if (this.playing) {
          return this.pause();
        } else {
          return this.play();
        }
      };

      Player.prototype.stop = function() {
        this.audio.pause();
        this.audio.currentTime = 0;
        this.playing = false;
        return this.trigger('stop');
      };

      Player.prototype.pause = function() {
        this.audio.pause();
        this.playing = false;
        return this.trigger('pause');
      };

      Player.prototype.seek = function(pos) {
        if (!this.audio.duration) {
          return;
        }
        pos = Math.min(1, Math.max(0, pos));
        return this.audio.currentTime = pos * this.audio.duration;
      };

      Player.prototype.play = function(index) {
        if (index == null) {
          index = null;
        }
        if (index === null && this.index === null) {
          index = 0;
        }
        if (index !== null) {
          this.audio.src = this.tracks[index].src;
          this.index = index;
        } else {
          index = this.index;
        }
        this.audio.play();
        this.playing = true;
        return this.trigger('play', this.tracks[index]);
      };

      Player.prototype.addEventListener = function(type, callback) {
        if (!this.listeners[type]) {
          this.listeners[type] = [];
        }
        return this.listeners[type].push(callback);
      };

      Player.prototype.trigger = function() {
        var args, callback, i, len, ref, type;
        type = arguments[0], args = 2 <= arguments.length ? slice.call(arguments, 1) : [];
        if (this.listeners[type]) {
          ref = this.listeners[type];
          for (i = 0, len = ref.length; i < len; i++) {
            callback = ref[i];
            callback.apply(this, args);
          }
        }
        return false;
      };

      return Player;

    })();
    return $(".js-player").each(function() {
      var $duration, $name, $next, $pause, $play, $prev, $progress, $progressBar, $time, $tracks, $volumeBar, $volumeBarValue, getIndexById, height, player, setVol, tracks, tracksById, volume;
      tracks = $(this).data('tracks');
      if (!tracks) {
        return;
      }
      $time = $(".js-player-time");
      $play = $(".js-player-playBtn");
      $pause = $(".js-player-pauseBtn");
      $name = $(".js-player-trackName");
      $progress = $(".js-player-progressBar");
      $next = $(".js-player-nextBtn");
      $prev = $(".js-player-prevBtn");
      $duration = $(".js-player-duration");
      $progressBar = $(".header-player-progress");
      $volumeBar = $(".header-player-volume-line");
      $volumeBarValue = $(".header-player-volume-value");
      $tracks = $(".js-track");
      tracksById = [];
      $tracks.each(function() {
        return tracksById[$(this).data('trackId')] = $(this);
      });
      player = new Player(tracks);
      player.addEventListener("play", function(track) {
        $name.text(track.name);
        $pause.show();
        $play.hide();
        $time.show().text("00:00");
        $duration.text(track.duration);
        $tracks.filter(".tracks-track--active").find(".icon-font-pause").hide();
        $tracks.filter(".tracks-track--active").find(".icon-font-play").show();
        $tracks.removeClass('tracks-track--active');
        tracksById[track.id].addClass('tracks-track--active');
        tracksById[track.id].find(".icon-font-pause").show();
        return tracksById[track.id].find(".icon-font-play").hide();
      });
      player.addEventListener("pause", function() {
        $play.show();
        $pause.hide();
        $tracks.filter(".tracks-track--active").find(".icon-font-pause").hide();
        return $tracks.filter(".tracks-track--active").find(".icon-font-play").show();
      });
      player.addEventListener("stop", function() {
        $play.show();
        $pause.hide();
        $time.hide();
        return $tracks.removeClass('tracks-track--active');
      });
      player.addEventListener("timeupdate", function(time, progress) {
        $time.text(time);
        return $progress.css({
          width: progress * 100 + "%"
        });
      });
      $pause.click(function() {
        return player.pause();
      });
      $play.click(function() {
        return player.play();
      });
      $next.click(function() {
        return player.next();
      });
      $prev.click(function() {
        return player.prev();
      });
      getIndexById = function(id) {
        var index, track;
        for (index in tracks) {
          track = tracks[index];
          if (parseInt(track.id) === parseInt(id)) {
            return parseInt(index);
          }
        }
        return false;
      };
      $progressBar.click(function(e) {
        var x;
        x = e.pageX - $progressBar.offset().left;
        return player.seek(x / $progressBar.width());
      });
      $tracks.click(function() {
        var index;
        index = getIndexById($(this).data('trackId'));
        if (index === false) {
          return;
        }
        if (player.index === index) {
          player.togglePlay();
        } else {
          player.play(index);
        }
        return false;
      });
      if (volume = $.cookie("volume")) {
        volume = Math.min(1, Math.max(0, volume));
        $volumeBarValue.css({
          height: 100 * volume + "%"
        });
        player.volume(volume);
      }
      height = $volumeBar.height();
      setVol = function(pageY) {
        var pos;
        pos = (height - pageY + $volumeBar.offset().top) / height;
        pos = Math.min(1, Math.max(0, pos));
        $volumeBarValue.css({
          height: 100 * pos + "%"
        });
        player.volume(pos);
        return $.cookie("volume", pos);
      };
      $volumeBar.bind({
        touchstart: function() {
          height = $volumeBar.height();
          $volumeBar.parent().addClass('header-player-volume--active');
          $("body").bind('touchmove.volume', function(e) {
            setVol(e.originalEvent.targetTouches[0].pageY);
            return e.preventDefault();
          });
          return $("body").one('touchend', function() {
            return $volumeBar.parent().removeClass('header-player-volume--active');
          });
        }
      });
      return $volumeBar.mousedown(function() {
        height = $volumeBar.height();
        $volumeBar.parent().addClass('header-player-volume--active');
        $("body").bind('mousemove.volume', function(e) {
          return setVol(e.pageY);
        });
        $("body").one('mouseup', function() {
          $("body").unbind('mousemove.volume');
          return $volumeBar.parent().removeClass('header-player-volume--active');
        });
        return false;
      });
    });
  });

}).call(this);
jQuery ($) ->

  # MESSAGES
  message = new (class
    constructor: ( @timeout = 6000 ) ->
      @$holder =
        $("<div class='messages'></div>").appendTo('body')
        .on('close', '.message', ->
          clearTimeout $(this).data 'timeout'
          $(this).fadeUp -> $(this).remove()
        )
        .on 'click', '.close-message', ->
          $(this).parents('.message').trigger 'close'

      top = parseInt @$holder.css 'top'
      $(window).on 'scroll', => @$holder.css 'top', Math.max top - $(window).scrollTop(), 10

    notify: ( header, text )  -> @message "", header, text
    success: ( header, text ) -> @message "success", header, text
    error: ( header, text )   -> @message "error", header, text
    info: ( header, text )    -> @message "", header, text

    message: ( type, header, text ) ->
      type = "message-" + type if type

      text = "" unless text

      $message = $ "<div class='message #{type}'><span class='close close-message'></span><header>#{header}</header>#{text}</div>"
      $message.appendTo @$holder
      $message.hide().fadeDown()

      $message.data 'timeout', setTimeout ->
        $message.trigger 'close'
      , @timeout
  )();

  Popup = class
    constructor: ( content = "", autoshow = true ) ->
      @blackout = $("<div class='blackout'></div>").appendTo $ 'body'
      @popup = $("<div class='popup'><div><div></div></div></div>").appendTo $ 'body'
      @popup.children().children().html(content)

      @show() if autoshow
      @hide() unless autoshow

      popup = @
      @popup.children().children().mousedown (e) -> popup.remove() if e.target is @ and e.button is 0
      @popup.find(".close-popup").click -> popup.remove(); no

    show: ->
      return if @showed
      @blackout.hide()
      setTimeout =>
        @blackout.fadeIn()
      , 100
      @popup.hide().fadeIn()
      @showed = yes

    hide: (callback) ->
      return unless @showed
      @popup.fadeOut()
      @blackout.fadeOut callback
      @showed = no

    remove: ->
      @hide =>
        @blackout.remove()
        @popup.remove()

  # defaults
  (->
    if $.fn.datepicker
      $.fn.datepicker.defaults = $.extend $.fn.datepicker.defaults,
        format: "dd.mm.yyyy"
        language: "ru"
        weekStart: 1
        autoclose: yes
        todayHighlight: yes

    if $.fn.timepicker
      $.fn.timepicker.defaults = $.extend $.fn.timepicker.defaults,
        showInputs: false
        #disableFocus:
        showMeridian: no

    if $.fn.redactor
      $.Redactor.opts = $.extend $.Redactor.opts,
        toolbarFixedBox: yes
        buttons: ["html", "|",
                  "formatting", "|",
                  "bold", "italic", "deleted", "|",
                  "unorderedlist", "orderedlist", "outdent", "indent", "|",
                  "image", "video", "file", "table", "link", "|",
                  "alignment", "|", "horizontalrule"]
        imageUpload: "/backend/fv/persist"
  )()

  initFormBehaviour = ->
    $(this).find("input.date").each ->
      $(this).datepicker() if $.fn.datepicker

    $(this).find("input.time").each ->
      $(this).timepicker() if $.fn.timepicker

    $(this).find("textarea.rich").each ->
      $(@).redactor() if $.fn.redactor

    $(this).find(".tags").each ->
      return unless $.fn.selectize

      url = $(@).data 'url'

      options =
        create: off
        allowEmptyOption: yes

      if $(@).attr('multiple')
        options.plugins = ['remove_button']

      if $(@).data 'lazy'
        options.preload = on
        options.load = (query, callback) ->
          $.ajax
            type: 'GET'
            url: url
            data: q: query
            dataType: 'json'
            error: -> callback()
            success: (res) -> callback res

      $(@).selectize options

    $(this).find("textarea:not(.rich)").each ->
      $(@).autosize() if $.fn.autosize

    $(this).find("nav a").click ->
      $(this).addClass('active').siblings('.active').removeClass('active')
      rel = $(this).data('rel')
      $(this).parent().siblings('.group').hide().filter("*[data-rel=#{rel}]").show()
      no

    $(this).find(".file-remove").click ->
      message.notify "Файл будет удалён после сохранения"
      $(this).hide().siblings(".file-preview, .image-crop").hide().parents('label').find("input[type=hidden]").val ''
      no

    $(this).find(".image-crop").click ->
      $label = $(this).parents('label')

      src = $(@).data 'src'
      url = $(@).data 'url'

      btns = "<a class='btn close-popup' href='#'>Отмена</a> <a class='btn close-popup btn-orange ok' href='#'>Обрезать</a>"
      popup = new Popup "<div class='popup-page image'><div><img/></div>#{btns}</div>"

      $img = popup.popup.find("img")

      $img.load ->
        origAspect = $img[0].width / $img[0].height
        popup.popup.find("div>div").css(maxWidth: 80 * origAspect + "vh")

        $img.cropper
          autoCropArea: .9
          zoomable: no

      $img.attr src: $(@).attr 'href'

      popup.popup.find(".ok").click ->
        popup.popup.fadeOut()

        $.ajax
          url: url
          dataType: 'json'
          data:
            src: src
            params: $img.cropper 'getData', yes
          success: (ans) ->
            if ans.success
              path = ans.file.split "/"
              fileName = path[path.length-1]
              $label.find("input[type=hidden]").val fileName
              $label.find(".file-preview").attr('href', ans.file).show()
              $label.find(".file-remove, .image-crop").show()
              return message.info "Временный файл создан в карантинной зоне"

            message.error "Произошла ошибка", ans.error || null
          error: ->
            popup.remove()

      no

    $(this).find(".image-preview").click ->
      url = $(@).data 'url'
      popup = new Popup "<div class='popup-page image'><div><img/></div><a class='btn close-popup' href='#'>Закрыть</a></div>"

      $img = popup.popup.find("img")
      $img.load ->
        origAspect = $img[0].width / $img[0].height
        popup.popup.find("div>div").css(maxWidth: 80 * origAspect + "vh")

      $img.attr src: $(@).attr 'href'
      no

    $(this).find("input[type=file]").change (e) ->
      $label = $(this).parents('label').addClass 'loading'

      $loader = $label.find('.progress>span')
      $loader.parent().addClass 'unrecognized'

      data = new FormData
      data.append $(@).attr('name'), @files[0]

      $.ajax
        url: '/backend/fv/upload'
        type: 'POST'
        data: data
        cache: false
        processData: false
        contentType: false
        context: @
        dataType: 'json'
        xhr: ->
          xhr = new window.XMLHttpRequest

          xhr.upload.addEventListener "progress", (evt) ->
            if evt.lengthComputable
              $loader.css width: ((evt.loaded / evt.total) * 100) + "%"
              $loader.parent().removeClass 'unrecognized'

          xhr

        success: (data) ->
          $label.removeClass 'loading'

          if data.success
            path = data.file.split "/"
            fileName = path[path.length-1]
            $label.find("input[type=hidden]").val fileName
            $label.find(".file-preview").attr('href', data.file).show()
            $label.find(".file-remove, .image-crop").show()
            message.info "Временный файл загружен в карантинную зону"
          else
            message.error "Ошибка при загрузке временного файла."

        error: ->
          $label.removeClass 'loading'
          message.error "Ошибка при загрузке временного файла"

    $(this).find("input, textarea").eq(0).focus()

    keyup = (e) =>
      return unless e.keyCode is 27
      $("body").off 'keyup', keyup
      $(this).find('.cancel').trigger 'click'

    $("body").on 'keyup', keyup

    $(this).find("input, textarea")
      .eq(0).focus()

  # MENU
  $(".header>.container>ul>li>a").click ->
    return if $(this).attr 'href'

    $(this).siblings('.drop-down').each ->
      show = =>
        $(this)
          .css( opacity: 0 )
          .show()
          .animate( { opacity: 1 }, 100 )
          .children('li:first-child')
          .css( marginTop: -200 )
          .animate( marginTop: 0, 100 )

      hide = =>
        $(this)
          .stop()
          .animate( { opacity: 0}, 100, -> $(this).hide() )
          .children('li:first-child')
          .animate( marginTop: -200, 100 )

      if $(this).is(':visible')
        hide()
      else
        show()
        setTimeout ->
          callback = ->
            hide()
            $(window).off 'click', callback

          $(window).on 'click', callback
        , 100

    $(window).trigger 'click'
    off

  # SORTING // SEARCH
  $(".entity-list").each ->
    $list = $ this
    getSort = -> {}
    search = $(this).parents(".container").find(".input-search").val()
    jqXHR = null

    reload = =>
      data =
        sort: getSort()
        search: search

      jqXHR.abort() if jqXHR

      if history.replaceState
        history.replaceState(data, document.title, location.pathname + "?" + $.param(data))

      jqXHR = $.ajax
        url: location.pathname
        data: data
        context: $list.find('.data').stop()
        success: (html) ->
          $(this).stop().html(html).animate(opacity: 1)
          jqXHR = null
        error: (xhr, err) ->
          return if err is "abort"

          alert "Не удалось загрузить данные"
          $(this).stop().animate(opacity: 1)
          jqXHR = null

    $(this).parents(".container").find(".input-search").bind
      keydown: (e) ->
        if e.keyCode is 27
          $(this).val search = ''
          reload()
        else
          setTimeout =>
            search = $(this).val()
            reload()
          , 10

    $(this).parents(".container").find(".search-button").click ->
      search = $(this).siblings('input').val()
      reload()

    $(this).on 'click', '.pager .btn', ->

      $.ajax
        url: this.getAttribute('href')
        context: this
        success: ( ans ) ->
          $(ans).children().filter(":not(.new)")
            .insertBefore($(this).parents('.pager'))
            .hide()
            .slideDown()
          $(this).parents('.pager').remove()

      no

    $(this).find(".sort").each ->
      getSort = ->
        sort = {}
        $list.find('.sort select').each ->
          if( $(this).val() )
            sort[$(this).val()] = (if $(this).next('.fa-sort-amount-asc').length > 0 then 1 else -1)

        sort

      $(this).on 'click', '.fa-sort-amount-asc', ->
        $(this).removeClass('fa-sort-amount-asc').addClass('fa-sort-amount-desc')
        reload()

      $(this).on 'click', '.fa-sort-amount-desc', ->
        $(this).removeClass('fa-sort-amount-desc').addClass('fa-sort-amount-asc')
        reload()

      $(this).on 'change', 'select', ->
        val = $(this).val()
        reload()

        $(this).parents('nobr').each ->
          return if $(this).prevAll('nobr').length > 1

          $(this).nextAll('nobr').remove();

          if $(this).find('option').length > 1 && val
            $(this).clone().insertAfter(this).each ->
              $(this).find("option[value='#{val}']").remove()
              $(this).find(".icon-sort-by-alphabet-alt").removeClass('icon-sort-by-alphabet-alt').addClass('icon-sort-by-alphabet')

  (->
    $base = $ '.entity-list.base'
    return unless $base.length

    rebaseTo = ( $list ) ->
      offset = $list.parents('.entity-list').length
      $base.animate left: -offset * 500

    $base.on 'refresh', '.entities>div', ->
      showed = $(this).is(":visible")
      $(this).load $(this).closest('.entity-list').attr('one').replace('$id', @id), ->
        $item = $(this).children().unwrap()
        $item.hide().slideDown() unless showed

    $base.on 'click', '.entities>div', ->
      return if $(this).hasClass('edit')

      $list = $(this).closest('.entity-list')
      top = $(this).offset().top - $list.offset().top

      rebaseTo $list

      $list.parent().find('.entities>.edit').removeClass 'edit'
      $list.parent().find('.entity-edit').html('')

      $(this).addClass 'edit'

      if $(this).hasClass('new')
        path = $list.attr('create')
      else
        path = $list.attr('edit').replace('$id', @id)

      $list
        .children('.data')
        .children('.entity-edit')
        .css( top: top, opacity: '')
        .hide()
        .load path, ->
            $list.addClass('inited')
            initFormBehaviour.call @
            $(this).show().css(right: -50).animate right: 0, ->
              $list.animate minHeight: top + $(this).height()

    $base.on 'click', '.entity-form .btn.cancel', ->
      $(this).closest('.entity-edit').hide()
      $list = $(this).closest('.entity-list').removeClass('inited')
      $item = $list.children('.data').children('.entities').children('.edit').removeClass('edit')
      rebaseTo $list.parent().closest '.entity-list'

      if $item.offset().top < $("body").scrollTop()
        $("html, body").animate scrollTop: $item.offset().top - 20

      false

    $base.on 'submit', '.entity-form form.forms', ->
      return false if $(this).data 'process'

      if $(@).find('.loading').length
        alert "Картинки всё ещё загружаются"
        return false

      $(this).data 'process', yes

      $list = $(this).closest('.entity-list')

      action = this.getAttribute 'action'
      method = this.getAttribute 'method'

      $(this).find(".error").remove()
      $(this).find(".input-error").removeClass('input-error')
      $(this).find(".over-blackout").removeClass('over-blackout')

      $.ajax
        url: action
        type: method
        data: $(this).serialize()
        context: this
        success: (ans, status, jqXHR) ->
          if jqXHR.getResponseHeader('success')
            id = jqXHR.getResponseHeader 'id'
            isNew = jqXHR.getResponseHeader 'isNew'

            if isNew
              $new = $list.children('.data').children('.entities').children('.new')
              $("<div id='#{id}'></div>").insertAfter($new).hide().trigger 'refresh'
            else
              $list.children('.data').children('.entities').children('#' + id).trigger 'refresh'

            try
              rollbackData = $.parseJSON ans

              rollback = "<a href='#' class='color-yellow rollback close-message'>отменить</a>";

              $message = message.info "Сохранено", "Данные успешно обновлены. #{rollback}"

              if rollbackData
                $message.find(".rollback").click (e) ->
                  $.ajax
                    url: action
                    type: method
                    data: rollbackData
                    success: -> $list.children('.data').children('.entities').children('#' + id).trigger 'refresh'

                  e.preventDefault()
            catch
              message.info "Сохранено", "Данные успешно обновлены."

            $(this).find('.btn.cancel').trigger 'click'
          else
            message.error "Ошибка", "Данные нельзя сохранить"
            first = yes

            $(ans).find(".input-error").each ->
              $el = $ "#" + @id;
              $el.addClass("input-error").parents("label").addClass('over-blackout')

              if $el.parents('.redactor_box').length
                $el = $el.parents('.redactor_box').addClass('input-error')

              $(@).parents('label').next(".error").insertAfter( $el.parents('label') )

              if first
                first = no

                $group = $el.parents '.group:not(:visible)'
                if $group.length > 0
                  rel = $group.data('rel')
                  $group.siblings('nav').children("[data-rel=#{rel}]").trigger('click')

                $("html, body").animate(scrollTop: $el.offset().top - 150)
                $fader = $("<div>").addClass "blackout"
                $el.parents('form').prepend $fader
                $fader.delay(2000).fadeOut 1000, -> $(@).remove()

              yes

            $(this).data 'process', no

        error: (xhr) ->
          $error = message.error "Ошибка", "Не удалось загрузить данные. <a class='close-message show-info color-red' href='#'>Детальная информация</a>"
          $error.find('.show-info').click ->
            new Popup "<div class='popup-page error-info'>#{xhr.responseText}<a class='btn close-popup' href='#'>Закрыть</a></div>"
            no

          $(this).data 'process', no


      false

    $base.on 'click', '.entity-form .btn.remove', ->
      return unless confirm("Вы уверены?")
      $list = $(this).closest('.entity-list').removeClass('inited')
      $(this).closest('.entity-edit').hide()

      $list.children('.data').children('.entities').children('.edit').removeClass('edit').each ->
        $.ajax
          url: $list.attr('remove').replace '$id', @id
          context: this
          success: (ans, status, jqXHR) ->
            if jqXHR.getResponseHeader('success')
              $(this).slideUp -> $(this).remove()
              message.success "Удалено"
            else
              message.error "Невозможно удалить"

      no
  )()

  $(".table-edit").each ->
    edit = this.getAttribute 'edit'
    remove = this.getAttribute 'remove'
    create = this.getAttribute 'create'
    one = this.getAttribute 'one'

    $table = $(this).find('tbody')

    $(this).on 'click', '.pager .btn', ->
      $.ajax
        url: this.getAttribute('href')
        context: this
        success: ( ans ) ->
          $(ans).insertBefore($(this).parents('.pager'))
          $(this).parents('.pager').remove()

      no


    $(this).on 'remove', 'tr', ->
      path = remove.replace '$id', this.id

      $.ajax
        url: path
        context: this
        success: (ans, status, jqXHR) ->
          if jqXHR.getResponseHeader('success')
            $(this).remove()
            message.success "Удалено"
          else
            message.error "Невозможно удалить"

    $(this).on 'click', '.remove', ->
      return no unless confirm "Удалить?"
      $(this).parents('tr').trigger 'remove'
      no


    search = $(this).parents(".container").find(".input-search").val()
    jqXHR = null

    reload = =>
      data = search: search

      jqXHR.abort() if jqXHR

      if history.replaceState
        history.replaceState(data, document.title, location.pathname + "?" + $.param(data))

      jqXHR = $.ajax
        url: location.pathname
        data: data
        context: $(this).find('.data').stop()
        success: (html) ->
          $(this).stop().html(html).animate(opacity: 1)
          jqXHR = null
        error: (xhr, err) ->
          return if err is "abort"

          alert "Не удалось загрузить данные"
          $(this).stop().animate(opacity: 1)
          jqXHR = null

    $(this).parents(".container").find(".input-search").bind
      keydown: (e) ->
        if e.keyCode is 27
          $(this).val search = ''
          reload()
        else
          setTimeout =>
            search = $(this).val()
            reload()
          , 10

    $(this).parents(".container").find(".search-button").click ->
      search = $(this).siblings('input').val()
      reload()

    $(this).on 'click', 'td', ->
      $(this).parent().find('.edit').trigger 'click'

    $(this).on 'click', '.edit, .create', ->
      if $(this).hasClass 'create'
        path = create
      else
        $tr = $(this).parents 'tr'
        path = edit.replace '$id', $tr[0].id

      $.ajax
        url: path
        success: (ans) ->
          popup = new Popup(ans)
          $popup = popup.popup

          $popup.find('.remove').click ->
            return no unless confirm "Удалить?"
            $tr.trigger 'remove'
            $popup.find('.cancel').trigger 'click'
            no

          $popup.find('.cancel').click ->
            popup.remove()
            no

          $form = $popup.find('.entity-form>form')

          initFormBehaviour.call $form, arguments
          $form.submit ->
            return false if $(this).data 'process'

            $(this).data 'process', yes

            $.ajax
              url: this.getAttribute('action')
              type: this.getAttribute('method')
              data: $(this).serialize()
              context: this
              success: (ans, status, jqXHR) ->
                if jqXHR.getResponseHeader('success')
                  message.success "Сохранено", "Данные успешно обновлены"

                  if $tr
                    $tr.load one.replace('$id', $tr[0].id), -> $(this).children().unwrap()
                  else
                    $.ajax
                      url: one.replace '$id', jqXHR.getResponseHeader('id')
                      success: (ans) => $table.prepend ans

                  $(this).find('.btn.cancel').trigger 'click'
                else
                  message.error "Ошибка", "Данные нельзя сохранить"

                  $(this).parents('.entity-form').html $(ans).html()

              error: ->
                alert "Не удалось загрузить данные"
                $(this).data 'process', no


            false

      no

  $("body").on 'change', '.btn-file.autosubmit', ->
    $(this).parents('form').submit()

  $('.btn-file').each -> $(@).replaceWith( $(@).clone( true ) );
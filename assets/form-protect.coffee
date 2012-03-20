(($, _) ->
    # TODO this does not really work with more than one form yet
    
    changed = []

    $(window).bind 'beforeunload', -> 
        if changed.length
            'Ihre Eingaben sind noch nicht gespeichert. Wollen Sie die Seite wirklich verlassen'.toLocaleString()

    $.fn.extend protect: ->
        @.filter('form').each ->
            $(@).find('input,select,textarea').change ->
                if @value == @defaultValue 
                    changed = _(changed).without(@id)
                else
                    changed.push(@id)

            $(@).find(':submit').click ->
                changed = []

)(jQuery, _)
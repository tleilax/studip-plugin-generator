(($, STUDIP) ->
    # hides tooltip just long enough to not trigger :hover again
    $('.generator dfn').live 'click', ->
        $(@).hide()
        _.defer => $(@).show()

    $ ->
        # adjust tab indices // TODO why did i do this?
        $('.generator fieldset button,input,select,textarea').each (index) ->
            $(@).attr 'tabindex', index + 1
        

    class Generator
        constructor: (@element) ->
            step = $(element).data().step ? 0
            
            if step == 'manifest'
                $('#description').keypress (event) -> event.keyCode != 13
            else if step == 'assets'
                # toggle assets content, TODO shorten (collapsable?)
                $('fieldset', element).find('input,select,textarea').each ->
                    name = $(@).attr 'name'
                    that = $('[name="' + name + '_content"]')
                    if that.length
                        $(@).closest('div').find('label').addClass('open').click ->
                            open = $(@).toggleClass('open closed').is('.open')
                            # $.toggle() didn't work, so we need a workaround
                            that.closest('div').css('display', if open then '' else 'none')
                            if open
                                that.focus()
                                return false
                        .click().find(':not(dfn)').wrap('<a/>')
            else if step == 'navigation'
                # make navigation sortable
                $('.navigation tbody', element).sortable
                    axis: 'y'
                    containment: 'parent'
                    handle: '.icon.move'
                    update: => @refresh()

                @refresh = ->
                    $('.navigation tbody tr').each (index, item) ->
                        cycle = if (index % 2) then 'cycle_odd' else 'cycle_even'
                        $(@).removeClass('cycle_even cycle_odd').addClass cycle
                        $('input', item).each ->
                            name = $(@).attr('name')
                            name = name.replace(/^navigation\[\d+\]/, "navigation[#{index}]")
                            $(@).attr('name', name)
                        $('button[name=delete]', item).val(index)

            # attach handlers
            _(@handlers).each (handlers, selector) ->
                $(element).delegate selector, handlers
                
        handlers:
            '#pluginclassname, .navigation input[name="navigation[0][path]"], .navigation input[name="navigation[0][title]"]':
                change: -> $(@).data 'changed', true
            '#pluginname':
                change: ->
                    # Automatically suggest plugin class name and navigation path and title
                    name = $(@).val()

                    classname = $('#pluginclassname')
                    if name.length and (not classname.val() or not classname.data('changed'))
                        ucfirst = (word) ->
                            word.substr(0, 1).toUpperCase() + word.substr(1).toLowerCase()

                        temp  = name
                        temp  = temp.replace(/[^a-z0-9\- ]/gi, '')         # remove unwanted characters
                        temp  = temp.replace(/\b[a-z0-9\-]+\b/gi, ucfirst) # camel-case
                        temp  = temp.replace(/\s/g, '')                    # remove whitespace
                        temp += 'Plugin' unless temp.toLowerCase().indexOf('plugin') isnt -1

                        classname.val(temp)
                            
                    path = $('.navigation input[name="navigation[0][path]"]')
                    if name.length and ((not path.val() or path.val() == '/') or not path.data('changed'))
                        temp = name
                        
                        temp = temp.replace(/[^a-z0-9\-]/gi, '')         # remove unwanted characters
                        temp = temp.toLowerCase()
                        path.val("/#{temp}")

                    title = $('.navigation input[name="navigation[0][title]"]')
                    if name.length and (not title.val() or not title.data('changed'))
                        title.val(name)
            '.navigation button[name=add]':
                click: (event) ->
                    path = $('.generator .navigation tr:last input[name*="path"]').val() ? '/'
                    $(@).closest('table')
                        .find('tbody')
                        .append """
                                <tr>
                                    <td><div class="move icon"></div></td>
                                    <td><input type="text" name="navigation[0][path]" value="#{path}"></td>
                                    <td><input type="text" name="navigation[0][title]"></td>
                                    <td><input type="text" name="navigation[0][action]"></td>
                                    <td>
                                        <button name="delete" value="0" style="white-space: nowrap;">
                                            <img src="assets/images/icons/16/blue/trash.png" alt="#{"Löschen".toLocaleString()}">
                                        </button>
                                    </td>
                                </tr>
                                """
                    STUDIP.PluginGenerator.refresh()
                    
                    event.preventDefault()
            '.navigation button[name=delete]':
                click: (event) ->
                    $(@).closest('tr').remove()
                    STUDIP.PluginGenerator.refresh()
                    event.preventDefault()

    # attach generator as jquery plugin
    $.fn.extend generator: -> 
        @each -> 
            STUDIP.PluginGenerator = new Generator @

)(jQuery, STUDIP)

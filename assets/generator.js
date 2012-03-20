(function($, STUDIP) {
  var Generator;
  $('.generator dfn').live('click', function() {
    var _this = this;
    $(this).hide();
    return _.defer(function() {
      return $(_this).show();
    });
  });
  $(function() {
    return $('.generator fieldset button,input,select,textarea').each(function(index) {
      return $(this).attr('tabindex', index + 1);
    });
  });
  Generator = (function() {

    function Generator(element) {
      var step, _ref,
        _this = this;
      this.element = element;
      step = (_ref = $(element).data().step) != null ? _ref : 0;
      if (step === 'manifest') {
        $('#description').keypress(function(event) {
          return event.keyCode !== 13;
        });
      } else if (step === 'assets') {
        $('fieldset', element).find('input,select,textarea').each(function() {
          var name, that;
          name = $(this).attr('name');
          that = $('[name="' + name + '_content"]');
          if (that.length) {
            return $(this).closest('div').find('label').addClass('open').click(function() {
              var open;
              open = $(this).toggleClass('open closed').is('.open');
              that.closest('div').css('display', open ? '' : 'none');
              if (open) {
                that.focus();
                return false;
              }
            }).click().find(':not(dfn)').wrap('<a/>');
          }
        });
      } else if (step === 'navigation') {
        $('.navigation tbody', element).sortable({
          axis: 'y',
          containment: 'parent',
          handle: '.icon.move',
          update: function() {
            return _this.refresh();
          }
        });
        this.refresh = function() {
          return $('.navigation tbody tr').each(function(index, item) {
            var cycle;
            cycle = index % 2 ? 'cycle_odd' : 'cycle_even';
            $(this).removeClass('cycle_even cycle_odd').addClass(cycle);
            $('input', item).each(function() {
              var name;
              name = $(this).attr('name');
              name = name.replace(/^navigation\[\d+\]/, "navigation[" + index + "]");
              return $(this).attr('name', name);
            });
            return $('button[name=delete]', item).val(index);
          });
        };
      }
      _(this.handlers).each(function(handlers, selector) {
        return $(element).delegate(selector, handlers);
      });
    }

    Generator.prototype.handlers = {
      '#pluginclassname, .navigation input[name="navigation[0][path]"], .navigation input[name="navigation[0][title]"]': {
        change: function() {
          return $(this).data('changed', true);
        }
      },
      '#pluginname': {
        change: function() {
          var classname, name, path, temp, title, ucfirst;
          name = $(this).val();
          classname = $('#pluginclassname');
          if (name.length && (!classname.val() || !classname.data('changed'))) {
            ucfirst = function(word) {
              return word.substr(0, 1).toUpperCase() + word.substr(1).toLowerCase();
            };
            temp = name;
            temp = temp.replace(/[^a-z0-9\- ]/gi, '');
            temp = temp.replace(/\b[a-z0-9\-]+\b/gi, ucfirst);
            temp = temp.replace(/\s/g, '');
            if (temp.toLowerCase().indexOf('plugin') === -1) temp += 'Plugin';
            classname.val(temp);
          }
          path = $('.navigation input[name="navigation[0][path]"]');
          if (name.length && ((!path.val() || path.val() === '/') || !path.data('changed'))) {
            temp = name;
            temp = temp.replace(/[^a-z0-9\-]/gi, '');
            temp = temp.toLowerCase();
            path.val("/" + temp);
          }
          title = $('.navigation input[name="navigation[0][title]"]');
          if (name.length && (!title.val() || !title.data('changed'))) {
            return title.val(name);
          }
        }
      },
      '.navigation button[name=add]': {
        click: function(event) {
          var path, _ref;
          path = (_ref = $('.generator .navigation tr:last input[name*="path"]').val()) != null ? _ref : '/';
          $(this).closest('table').find('tbody').append("<tr>\n    <td><div class=\"move icon\"></div></td>\n    <td><input type=\"text\" name=\"navigation[0][path]\" value=\"" + path + "\"></td>\n    <td><input type=\"text\" name=\"navigation[0][title]\"></td>\n    <td><input type=\"text\" name=\"navigation[0][action]\"></td>\n    <td>\n        <button name=\"delete\" value=\"0\" style=\"white-space: nowrap;\">\n            <img src=\"assets/images/icons/16/blue/trash.png\" alt=\"" + ("Löschen".toLocaleString()) + "\">\n        </button>\n    </td>\n</tr>");
          STUDIP.PluginGenerator.refresh();
          return event.preventDefault();
        }
      },
      '.navigation button[name=delete]': {
        click: function(event) {
          $(this).closest('tr').remove();
          STUDIP.PluginGenerator.refresh();
          return event.preventDefault();
        }
      }
    };

    return Generator;

  })();
  return $.fn.extend({
    generator: function() {
      return this.each(function() {
        return STUDIP.PluginGenerator = new Generator(this);
      });
    }
  });
})(jQuery, STUDIP);
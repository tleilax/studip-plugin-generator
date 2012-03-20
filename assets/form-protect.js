(function($, _) {
  var changed;
  changed = [];
  $(window).bind('beforeunload', function() {
    if (changed.length) {
      return 'Ihre Eingaben sind noch nicht gespeichert. Wollen Sie die Seite wirklich verlassen'.toLocaleString();
    }
  });
  return $.fn.extend({
    protect: function() {
      return this.filter('form').each(function() {
        $(this).find('input,select,textarea').change(function() {
          if (this.value === this.defaultValue) {
            return changed = _(changed).without(this.id);
          } else {
            return changed.push(this.id);
          }
        });
        return $(this).find(':submit').click(function() {
          return changed = [];
        });
      });
    }
  });
})(jQuery, _);
window.jQuery(function ($) {
	$('body').addClass('js');

	if (!$('.generator').length) {
		return;
	}

	// Disallow line breaks for description
	$('#description').keypress(function (event) {
		return event.keyCode !== 13;
	});

	// Polyfill: <input type="number">
	var test = document.createElement('input');
	test.type = 'number';
	document.body.appendChild(test);
	if (test.type !== 'number') {
		$('input[type=number]').keypress(function (event) {
			var that = this;
			setTimeout(function () {
				// TODO: Preserver cursor position?
				if (/\D/.test(that.value)) {
					that.value = that.value.replace(/\D/g, '');
				}
			}, 0);
		});
	}
	test.parentNode.removeChild(test);

	// Adjust tab indices
	$('fieldset').find('button,input,select,textarea').each(function (index) {
		$(this).attr('tabindex', index + 1);
	});

	// Automatically suggest plugin class name
	$('#name').change(function () {
		var name = $(this).val();
		if (name.length && !$('#pluginclassname').val().length) {
			name = name.replace(/[^a-z0-9\- ]/i, '');
			name = name.replace(/\b[a-z0-9\- ]+\b/gi, function (word) {
				return word.substr(0, 1).toUpperCase() + word.substr(1).toLowerCase();
			});
			name = name.replace(/ /g, '');
			$('#pluginclassname').val(name + 'Plugin').change();
		}
	}).change();

	//
	$('fieldset').find('input,select,textarea').each(function () {
		var name = $(this).attr('name').replace(/(^plugin\[|\]$)/g, ''),
			that = $('[name="plugin[' + name + '_content]"]');
		if (that.length) {
			$(this).closest('div').find('label').addClass('open').click(function () {
				var open = $(this).toggleClass('open closed').is('.open');
				// $.toggle() didn't work, so we need a workaround
				that.closest('div').css('display', open ? '' : 'none');
				if (open) {
					that.focus();
					return false;
				}
			}).click().find(':not(var)').wrap('<a/>');
		}
	});

	// Polyfill: required-Attribute
	// !! Always activated to have a consistent error handling
	var onsubmit = [],
		error_template = 'Das Feld "#{title}" muss angegeben werden.';
//	if (typeof document.createElement('input').required === 'undefined') {
		$('[required]').each(function () {
			var title = $(this).closest('div').find('label').clone().find('var').remove().end().text(),
				error = error_template.replace(/#\{title\}/, $.trim(title)),
				that = this;
			onsubmit.push(function () {
				if (!that.value.length) {
					return [{
						element: that,
						message: error
					}];
				}
				return [];
			});
		}).removeAttr('required').closest('div').addClass('required');
//	}

	// Validate generator
	$('.generator').submit(function () {
		var errors = [],
			messages = [],
			element = [],
			checkboxes = $('[name="plugin[interface][]"]', this);

		$.each(onsubmit, function () { errors = errors.concat(this.call()); });

		// Verify any plugin type is checked
		if (checkboxes.filter(':checked').length === 0) {
			errors.push({
				element: checkboxes[0],
				message: 'Es muss mindestens ein Plugin-Interface ausgewählt werden.'
			});
		}
		// Stop submission if neccessary
		if (errors.length > 0) {
			elements = $.map(errors, function (e) { return e.element; });
			messages = $.map(errors, function (e) { return e.message; });

			$(elements).closest('div').addClass('error');

			elements[0].focus();
			alert('Folgende Fehler sind aufgetreten:' + "\n\n" + messages.join("\n"));
			return false;
		}
	});

	$('.error [required], .error :checkbox').live('change', function () {
		$(this).closest('.error').removeClass('error');
	});
});

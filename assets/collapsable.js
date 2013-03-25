(function () {
	if (typeof Storage === 'undefined') {
		return;
	}

	if (!Storage.session.get('collapsed')) {
		var collapsed = {};
		$('[data-id]').each(function (index) {
			collapsed[$(this).data().id] = index > 0;
		});
		Storage.session.set('collapsed', collapsed);
	}

	$.each(Storage.session.get('collapsed'), function (id, collapsed) {
		$('[data-id="' + id + '"]').toggleClass('collapsed', collapsed);
	});

	$('form.collapsable').on('click', 'legend', function () {
		var collapsed = Storage.session.get('collapsed'),
			id = $(this).closest('fieldset').toggleClass('collapsed').data().id;
		collapsed[id] = !collapsed[id];
		Storage.session.set('collapsed', collapsed);
	});
}());

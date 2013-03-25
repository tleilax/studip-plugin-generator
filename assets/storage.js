(function (container) {
	if (typeof sessionStorage === 'undefined' || typeof localStorage === 'undefined') {
		return;
	}

	function getWrapper(storage) {
		return {
			get: function (key) {
				return JSON.parse(storage.getItem(key));
			},
			remove: function (key) {
				storage.removeItem(key);
			},
			set: function (key, value) {
				storage.setItem(key, JSON.stringify(value));
			}
		}
	}

	container.Storage = {
		local: getWrapper(localStorage),
		session: getWrapper(sessionStorage)
	};

}(window));
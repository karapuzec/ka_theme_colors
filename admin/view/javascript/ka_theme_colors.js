/* Ka Theme Colors: color picker <-> hex input sync and per-field reset (client side only, saved with the Save button) */
(function() {
	function normalize(v) {
		v = (v || '').trim().toLowerCase();
		if (v && v[0] != '#') v = '#' + v;
		if (/^#[0-9a-f]{3}$/.test(v)) v = '#' + v[1] + v[1] + v[2] + v[2] + v[3] + v[3];
		return /^#[0-9a-f]{6}$/.test(v) ? v : '';
	}

	function picker(hex) {
		return document.querySelector('.ka-color-picker[data-target="' + hex.id + '"]');
	}

	document.addEventListener('input', function(e) {
		var el = e.target;
		if (el.classList.contains('ka-color-picker')) {
			var hex = document.getElementById(el.dataset.target);
			hex.value = el.value;
			hex.classList.remove('is-invalid');
		} else if (el.classList.contains('ka-color-hex')) {
			var v = normalize(el.value);
			el.classList.toggle('is-invalid', el.value.trim() !== '' && !v);
			if (v) picker(el).value = v;
		}
	});

	document.addEventListener('click', function(e) {
		var btn = e.target.closest('.ka-color-reset');
		if (!btn) return;
		var hex = document.getElementById(btn.dataset.target);
		hex.value = btn.dataset.default;
		hex.classList.remove('is-invalid');
		picker(hex).value = btn.dataset.default || btn.dataset.auto || '#000000';
	});
})();

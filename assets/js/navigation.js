/*
 * Mrittika — Navigation
 * Mobile menu toggle, header search drawer, submenu keyboard support.
 * Vanilla JS, no dependencies.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var nav = document.getElementById('site-navigation');
		var menuToggle = nav ? nav.querySelector('.menu-toggle') : null;

		// --- Mobile menu ---
		if (menuToggle && nav) {
			menuToggle.addEventListener('click', function () {
				var open = nav.classList.toggle('is-open');
				menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				document.body.classList.toggle('menu-open', open);
			});

			// Close on Escape.
			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' && nav.classList.contains('is-open')) {
					nav.classList.remove('is-open');
					menuToggle.setAttribute('aria-expanded', 'false');
					document.body.classList.remove('menu-open');
					menuToggle.focus();
				}
			});
		}

		// --- Header search drawer ---
		var searchToggle = document.querySelector('.search-toggle');
		var searchDrawer = document.getElementById('header-search');
		if (searchToggle && searchDrawer) {
			searchToggle.addEventListener('click', function () {
				var isHidden = searchDrawer.hasAttribute('hidden');
				if (isHidden) {
					searchDrawer.removeAttribute('hidden');
					searchToggle.setAttribute('aria-expanded', 'true');
					var field = searchDrawer.querySelector('input[type="search"]');
					if (field) { field.focus(); }
				} else {
					searchDrawer.setAttribute('hidden', '');
					searchToggle.setAttribute('aria-expanded', 'false');
				}
			});
		}

		// --- Submenu touch/keyboard toggle ---
		var parents = document.querySelectorAll('.primary-menu-list .menu-item-has-children > a');
		parents.forEach(function (link) {
			link.addEventListener('keydown', function (e) {
				if (e.key === 'Enter') {
					var sub = link.parentNode.querySelector('.sub-menu');
					if (sub) {
						e.preventDefault();
						sub.style.display = sub.style.display === 'block' ? '' : 'block';
					}
				}
			});
		});
	});
})();

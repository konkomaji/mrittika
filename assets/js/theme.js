/*
 * Mrittika — Theme behaviors
 * Color scheme toggle (persisted), reading progress, smooth anchor focus.
 * Vanilla JS, no dependencies.
 */
(function () {
	'use strict';

	var KEY = (window.mrittikaConfig && window.mrittikaConfig.themeKey) || 'mrittika-color-scheme';
	var root = document.documentElement;

	function currentScheme() {
		var attr = root.getAttribute('data-theme');
		if (attr) { return attr; }
		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}

	function setScheme(value) {
		root.setAttribute('data-theme', value);
		try { localStorage.setItem(KEY, value); } catch (e) {}
	}

	document.addEventListener('DOMContentLoaded', function () {
		var toggle = document.querySelector('[data-scheme-toggle]');
		if (toggle) {
			toggle.addEventListener('click', function () {
				setScheme(currentScheme() === 'dark' ? 'light' : 'dark');
			});
		}

		// --- Reading progress bar on single posts ---
		var article = document.querySelector('.entry-single .entry-content');
		if (article) {
			var bar = document.createElement('div');
			bar.className = 'reading-progress';
			bar.setAttribute('aria-hidden', 'true');
			document.body.appendChild(bar);

			var update = function () {
				var rect = article.getBoundingClientRect();
				var total = article.offsetHeight - window.innerHeight;
				var scrolled = Math.min(Math.max(-rect.top, 0), Math.max(total, 1));
				var pct = total > 0 ? (scrolled / total) * 100 : 0;
				bar.style.width = pct + '%';
			};
			window.addEventListener('scroll', update, { passive: true });
			window.addEventListener('resize', update, { passive: true });
			update();
		}

		// --- Skip-link focus fix ---
		var skip = document.querySelector('.skip-link');
		if (skip) {
			skip.addEventListener('click', function () {
				var target = document.getElementById('primary');
				if (target) {
					target.setAttribute('tabindex', '-1');
					target.focus();
				}
			});
		}
	});
})();

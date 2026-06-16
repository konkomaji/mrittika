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

		// --- Back to top FAB ---
		var fab = document.createElement('button');
		fab.className = 'back-to-top';
		fab.type = 'button';
		fab.setAttribute('aria-label', 'Back to top');
		fab.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m18 15-6-6-6 6"/></svg>';
		document.body.appendChild(fab);
		fab.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
		var toggleFab = function () {
			if (window.scrollY > 600) { fab.classList.add('is-visible'); }
			else { fab.classList.remove('is-visible'); }
		};
		window.addEventListener('scroll', toggleFab, { passive: true });
		toggleFab();

		// --- Table of contents: collapse + scrollspy ---
		var toc = document.querySelector('[data-toc]');
		if (toc) {
			var tocToggle = toc.querySelector('.toc-toggle');
			if (tocToggle) {
				tocToggle.addEventListener('click', function () {
					var collapsed = toc.getAttribute('data-collapsed') === 'true';
					toc.setAttribute('data-collapsed', collapsed ? 'false' : 'true');
					tocToggle.setAttribute('aria-expanded', collapsed ? 'true' : 'false');
				});
			}
			var links = Array.prototype.slice.call(toc.querySelectorAll('a[href^="#"]'));
			var targets = links.map(function (l) { return document.getElementById(decodeURIComponent(l.getAttribute('href').slice(1))); }).filter(Boolean);
			if ('IntersectionObserver' in window && targets.length) {
				var spy = new IntersectionObserver(function (entries) {
					entries.forEach(function (en) {
						if (en.isIntersecting) {
							links.forEach(function (l) { l.parentNode.classList.remove('is-active'); });
							var active = toc.querySelector('a[href="#' + en.target.id + '"]');
							if (active) { active.parentNode.classList.add('is-active'); }
						}
					});
				}, { rootMargin: '0px 0px -75% 0px', threshold: 0 });
				targets.forEach(function (t) { spy.observe(t); });
			}
		}

		// --- Copy link buttons ---
		document.querySelectorAll('.copy-link[data-copy-url]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var url = btn.getAttribute('data-copy-url');
				var done = function () {
					var original = btn.textContent;
					btn.classList.add('copied');
					btn.textContent = 'Copied';
					setTimeout(function () { btn.classList.remove('copied'); btn.textContent = original; }, 1600);
				};
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(url).then(done).catch(done);
				} else {
					var t = document.createElement('textarea');
					t.value = url; document.body.appendChild(t); t.select();
					try { document.execCommand('copy'); } catch (e) {}
					document.body.removeChild(t); done();
				}
			});
		});

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

/*
 * Mrittika — admin settings interactions: tabs, media picker.
 */
(function ($) {
	'use strict';

	$(function () {
		// --- Tabs (with hash persistence) ---
		var $tabs = $('.mrittika-tabs .m-tab');
		var $panels = $('.m-panel');

		function activate(tab) {
			$tabs.removeClass('is-active').attr('aria-selected', 'false');
			$panels.removeClass('is-active');
			$tabs.filter('[data-tab="' + tab + '"]').addClass('is-active').attr('aria-selected', 'true');
			$panels.filter('[data-panel="' + tab + '"]').addClass('is-active');
		}

		$tabs.on('click', function () {
			var tab = $(this).data('tab');
			activate(tab);
			if (window.history.replaceState) {
				window.history.replaceState(null, '', '#' + tab);
			}
		});

		if (window.location.hash) {
			var initial = window.location.hash.replace('#', '');
			if ($tabs.filter('[data-tab="' + initial + '"]').length) {
				activate(initial);
			}
		}

		// --- Media picker for image fields ---
		var frame;
		$('.m-image-pick').on('click', function (e) {
			e.preventDefault();
			var $field = $(this).closest('.m-image-field');
			frame = wp.media({ title: 'Select image', multiple: false, library: { type: 'image' } });
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				$field.find('.m-image-url').val(att.url);
				$field.find('.m-image-preview').html('<img src="' + att.url + '" alt="">');
			});
			frame.open();
		});

		$('.m-image-clear').on('click', function (e) {
			e.preventDefault();
			var $field = $(this).closest('.m-image-field');
			$field.find('.m-image-url').val('');
			$field.find('.m-image-preview').empty();
		});

		// --- Tools: regenerate thumbnails (batched, one image per request) ---
		var cfg = window.mrittikaAdmin || {};
		var i18n = cfg.i18n || {};
		var $btn = $('#mrittika-regen-start');
		var $status = $('#mrittika-regen-status');
		var $bar = $('#mrittika-regen-bar');
		var $fill = $('#mrittika-regen-fill');

		function fmt(str, a, b) {
			return String(str).replace('%1$d', a).replace('%2$d', b).replace('%d', a);
		}

		$btn.on('click', function () {
			$btn.prop('disabled', true);
			$status.text(i18n.counting || 'Counting…');
			$bar.prop('hidden', false);
			$fill.css('width', '0%');

			$.post(cfg.ajaxUrl, { action: 'mrittika_regen_count', nonce: cfg.regenNonce })
				.done(function (res) {
					if (!res || !res.success || !res.data || !res.data.ids.length) {
						$status.text(i18n.none || 'No images found.');
						$btn.prop('disabled', false);
						$bar.prop('hidden', true);
						return;
					}
					runQueue(res.data.ids);
				})
				.fail(function () {
					$status.text(i18n.failed || 'Failed.');
					$btn.prop('disabled', false);
				});
		});

		function runQueue(ids) {
			var total = ids.length, done = 0;

			function next() {
				if (!ids.length) {
					$fill.css('width', '100%');
					$status.text(fmt(i18n.done || 'Done. %d image(s).', done));
					$btn.prop('disabled', false);
					return;
				}
				var id = ids.shift();
				$.post(cfg.ajaxUrl, { action: 'mrittika_regen_one', nonce: cfg.regenNonce, id: id })
					.always(function () {
						done++;
						$fill.css('width', Math.round((done / total) * 100) + '%');
						$status.text(fmt(i18n.working || 'Regenerating %1$d of %2$d…', done, total));
						next();
					});
			}
			next();
		}
	});
})(jQuery);

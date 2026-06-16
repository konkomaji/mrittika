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
	});
})(jQuery);

(function ($) {
	function appendModal() {
		return '<div class="modal ie11-warning" tabindex="-1"></div>';
	}

	function modalDialog() {
		var modalDialog = '<div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 960px;">';
		modalDialog += '  <div class="modal-content">';
		modalDialog += '    <div class="modal-header" style="border: none; padding: 0;">';
		modalDialog += '      <button type="button" class="close" style="background: none; color: #404040; border: none; padding: .5rem; margin: 1rem 1rem 0 auto;" data-dismiss="modal" aria-label="' + ie11_warning.close_aria + '">';
		modalDialog += '        <span style="font-size: 2rem; line-height: 1rem; display: block;" aria-hidden="true">&times;</span>';
		modalDialog += '      </button>';
		modalDialog += '    </div>';
		modalDialog += '    <div class="modal-body" style="text-align: center; padding: 0 2rem 3rem;">';
		modalDialog += '      <h1 class="modal-title" style="margin-bottom: 2rem;">' + ie11_warning.title + '</h1>';
		modalDialog += '      <p style="margin-bottom: 4rem; padding: 0 4rem;">' + ie11_warning.body + '</p>';
		modalDialog += '      <div class="container">';
		modalDialog += '        <div class="row">';

		var count = 1;
		$.each(ie11_warning.browsers, function (image, browser) {
			var border = '';
			if (count < Object.keys(ie11_warning.browsers).length) {
				border = ' border-right: 1px solid lightgrey;';
			}

			modalDialog += '          <div class="col" style="text-align: center;' + border + '">';
			modalDialog += '            <img width="90" height="90" style="margin-bottom: 1rem;" src="' + ie11_warning.icon_path + image + '.svg">';
			modalDialog += '            <span style="display: block; font-weight: bold;">' + browser.name + '</span>';
			modalDialog += '            <a href="' + browser.download_link + '">' + ie11_warning.download_text + '</a>';
			modalDialog += '          </div>';

			++count;
		});

		modalDialog += '        </div>';
		modalDialog += '      </div>';
		modalDialog += '    </div>';
		modalDialog += '  </div>';
		modalDialog += '</div>';

		return modalDialog;
	}

	function isIE() {
		return window.navigator.userAgent.match(/(MSIE|Trident)/);
	}

	$(document).ready(function () {
		if (!Cookies.get('_ie_check')) {
			$(appendModal()).appendTo('body');
			var $modal = $('.modal.ie11-warning');

			if ($modal.css('position') != 'fixed') {
				console.log('Bootstrap styling is not enabled for IE11 warning modal');
				$modal.remove();
			} else {
				if (isIE()) {
					$modal.html(modalDialog()).modal('show');
				} else {
					$modal.remove();
				}

				Cookies.set('_ie_check', true);
			}
		}
	});

}(jQuery));
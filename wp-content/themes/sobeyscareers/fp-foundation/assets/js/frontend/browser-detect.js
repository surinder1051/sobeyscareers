// Requires node_modules/browser-detect

jQuery(function($) {
	var result = browserDetect();
	if (typeof result == 'object') {
		$('body').attr('data-browser', result.name);
		$('body').attr('data-browser-media', (false === result.mobile ? 'desktop' : 'mobile')  );
	}
} );
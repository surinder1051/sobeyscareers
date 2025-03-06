// Prevent BB editor field from taking too much vertical room

function restrict_editor_height() {
	var iframeWin = jQuery('iframe[id$=_ifr]').filter(':visible').height('auto');
	if (iframeWin[0]) {

		// Setup observer to watch for height changes
		var observer = new MutationObserver(function () {

			// jQuery("[id$=_content_ifr]").height('auto');
			jQuery('.fl-builder-settings iframe').height('auto');
		});
		observer.observe(iframeWin[0], { attributes: true });
	}


}

// This needs to be on a timeout, otherwise it's not apply the triggers.
if (typeof FLBuilder == 'object') {
	setTimeout(() => {
		FLBuilder.addHook('settings-form-init', restrict_editor_height);
	}, 1000);
}

window.FP_BB_Module = window.FP_BB_Module || {};
window.FP_BB_Module.utilities = window.FP_BB_Module.utilities || {};
window.FP_BB_Module.utilities.embedVideo = (function($) {
	'use strict';

	var options;
	var instance;
	var $el;
	var videoType;
	var player;
	var apiReady = false;

	/**
	 * @constructor
	 * @param {Object} options
	 */
	function embedVideo(options) {
		$el = $('[data-js-embed_video]');
		if (! $el.length) {
			return; // Return if component isn't on the page
		}

		this.options = $.extend( {}, this.defaults, options || {} );
		options = $.extend( {}, this.defaults, options || {} );
		instance = this;

		// Tie the onready to our local function.
		for (let i = 0; i < $el.length; i++) {
			let element = $el[i];
			videoType = $(element).data('jsEmbed_video');
			if ('vimeo' === videoType) {
				window.onload = this.playerApiReady;
			} else if ('youtube' === videoType) {
				window.onYouTubeIframeAPIReady = this.playerApiReady;
			}
		}

		// Register event listeners
		this._bindEvents();
	}

	embedVideo.prototype = $.extend( {

		/**
		 * Load the video API
		 */
		init: function() {
			let src = null;
			for (let i = 0; i < $el.length; i++) {
				let element = $el[i];
				videoType = $(element).data('jsEmbed_video');
				if ('youtube' === videoType) {
					src = 'https://www.youtube.com/iframe_api';
				} else if ('vimeo' === videoType) {
					src = 'https://player.vimeo.com/api/player.js';
				}

				if (null !== src && 1 > $('head script[src="' + src + '"]').length) {

					// Create script tag
					var tag = document.createElement('script');
					tag.src = src;

					// Insert script tag
					var firstScriptTag = document.getElementsByTagName('script')[0];
					firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				}
				src = null;
			}
		},

		/**
		 * Callback for when the API has loaded.
		 */
		playerApiReady: function() {
			apiReady = true;
			$el.find('.video-defer').on('click', instance.loadVideo);
		},

		/**
		 * Load the video, this happens when the overlapping image has been clicked.
		 */
		loadVideo: function(e) {
			if (! apiReady) {
				throw Error('Cannot load video, API is not ready.');
			}

			var $video = $(e.target).closest('[data-js-embed_video]');
			$video.find('.video-remove').fadeOut(1000, function() {
				$video.find('.video-remove').remove();
			} );

			var videoID = $video.find('.video-defer').data('embed');
			var playerID = $video.find('.video-player-id').attr('id');
			videoType = $video.data('jsEmbed_video');
			if ('youtube' === videoType) {
				player = new YT.Player(playerID, {
					height: '100%',
					width: '100%',
					videoId: videoID,
					playerVars: { 'autoplay': 1, 'rel': 0 }
				} );
			} else if ('vimeo' === videoType) {
				player = new Vimeo.Player(playerID, {
					id: videoID,
					width: '100%',
					autoplay: true
				} );
			}
		},

		/**
		 * Register event listeners
		 * @private
		 */
		_bindEvents: function() {
			$(document).ready(this.init);
		}
	} );
	return embedVideo;
}(window.jQuery) );


jQuery(document).ready(function ($) {
	
	new FP_BB_Module.utilities.embedVideo();

});

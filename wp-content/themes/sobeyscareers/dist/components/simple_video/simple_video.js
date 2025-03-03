window.FP_BB_Module = window.FP_BB_Module || {};
window.FP_BB_Module.components = window.FP_BB_Module.components || {};
window.FP_BB_Module.components.simpleVideo = (function($) {
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
	function simpleVideo(options) {
		$el = $('[data-js-simple_video]');
		if (! $el.length) {
			return; // Return if component isn't on the page
		}

		this.options = $.extend( {}, this.defaults, options || {} );
		options = $.extend( {}, this.defaults, options || {} );
		instance = this;

		// Tie the onready to our local function.
		for (const element of $el) {
			videoType = $(element).data('jsSimple_video');
			if ('vimeo' === videoType) {
				window.onload = this.playerApiReady;
			} else if ('youtube' === videoType) {
				window.onYouTubeIframeAPIReady = this.playerApiReady;
			}
		}

		// Register event listeners
		this._bindEvents();
	}

	simpleVideo.prototype = $.extend( {

		/**
		 * Load the video API
		 */
		init: function() {
			let src = null;
			for (const element of $el) {
				videoType = $(element).data('jsSimple_video');
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
				throw Error('simple_video: Cannot load video, API is not ready.');
			}

			var $video = $(e.target).closest('.component_simple_video');
			var $placeholder = $video.find('.video-remove img');

			$video.find('.video-remove').fadeOut(1000, function() {
				var videoID = $video.find('.video-defer').data('embed');
				var playerID = $video.find('.video-player-id').attr('id');
				videoType = $video.data('jsSimple_video');
				if ('youtube' === videoType) {
					player = new YT.Player(playerID, {
						height: $placeholder.height(),
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

				$video.find('.video-remove').remove();
			} );
		},

		/**
		 * Register event listeners
		 * @private
		 */
		_bindEvents: function() {
			$(document).ready(this.init);
		}
	} );

	return simpleVideo;
}(window.jQuery) );

var simpleVideo = new FP_BB_Module.components.simpleVideo();

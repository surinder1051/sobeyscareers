(function($) {
	var $full_width_video = $('[data-js-full_width_video]');
	if (! $full_width_video.length) {
		return; // Return if component isn't on the page
	}
}(jQuery) );

jQuery('document').ready(function($) {
	$('.component_full_width_video').initialize(function() {
		if (0 < $(this).find('.youtube-player').length) {
			youtube_id = $(this).find('.youtube-player').data('id');
			node_id = $(this).parents('.fl-module').data('node');
			process_youtube_containers(youtube_id, node_id);
		}
	} );
	$('.component_full_width_video .play').on('click', function(e) {
		e.preventDefault();
		return;
	} );
	$('.component_full_width_video .play').on('mouseover', function() {
		$(this).addClass('hover');
		$(this).parents('.youtube-player').addClass('hover');
	} ).on('mouseout', function() {
		$(this).removeClass('hover');
		$(this).parents('.youtube-player').removeClass('hover');
	} );
	$('.component_full_width_video .youtube-player').on('mouseover', function() {
		$(this).addClass('hover');
		$('.play', this).addClass('hover');
	} ).on('mouseout', function() {
		$(this).removeClass('hover');
		$('.play', this).removeClass('hover');
	} );

} );

function labnolThumb(id, thumb_url, label, srcset, sizes) {
	var srcSet = ('' != srcset) ? ' srcset="' + srcset + '"' : '';
	var srcSizes = ('' != srcset) ? ' sizes="' + sizes + '"' : '';
	var thumb = '<img src="' + thumb_url + '" alt="Video Thumbnail"' + srcSet + '"' + srcSizes + '>';
	var play = '<button aria-label="Play video: ' + label + '" class="play"><span class="fas fa-play"></span></button>';
	return thumb + play;
}

function labnolIframe() {
	var iframe = document.createElement('iframe');
	var embed = 'https://www.youtube.com/embed/ID?autoplay=1&rel=0';
	iframe.setAttribute('src', embed.replace('ID', this.dataset.id) );
	iframe.setAttribute('frameborder', '0');
	iframe.setAttribute('allowfullscreen', '1');
	iframe.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; gyroscope');
	this.parentNode.replaceChild(iframe, this);
}

function process_youtube_containers(youtube_id, node_id) {
	var div, n;
	var v = document.querySelectorAll('[data-node="' + node_id + '"] .youtube-player:not(.done)[data-id="' + youtube_id + '"]');
	for (n = 0; n < v.length; n++) {
		if (v[n].classList.contains('done') ) {
			continue;
		}
		v[n].classList.add('done');
		div = document.createElement('div');
		div.setAttribute('data-id', v[n].dataset.id);
		div.innerHTML = labnolThumb(v[n].dataset.id, v[n].dataset.thumb, v[n].dataset.label, v[n].dataset.srcset, v[n].dataset.sizes);
		if (is_mobile() ) {
			div.onclick = open_youtube_on_mobile;
		} else {
			div.onclick = labnolIframe;
		}
		v[n].appendChild(div);
	}
}

function open_youtube_on_mobile() {
	url = 'https://youtu.be/' + this.dataset.id;
	window.location = url;
}

function is_mobile() {
	if (navigator.userAgent.match(/Android/i) ||
		navigator.userAgent.match(/webOS/i) ||
		navigator.userAgent.match(/iPhone/i) ||
		navigator.userAgent.match(/iPad/i) ||
		navigator.userAgent.match(/iPod/i) ||
		navigator.userAgent.match(/BlackBerry/i) ||
		navigator.userAgent.match(/Windows Phone/i) ) {
		return true;
	}
	return false;
}

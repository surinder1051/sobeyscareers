(function($) {

	var $stories = $('[data-js-stories]');
	if (! $stories.length) {
		return; // Return if component isn't on the page
	}

	$(window).on('resize', function() {
		if (768 > $(window).width() ) {
			$stories.each(function() {
				$(this).find('.story-box .text-container').css('height', 'auto');
				data = $(this);
				set_image_sizes(data);
			} );
		} else {
			$stories.each(function() {
				component = $(this);
				size_stories(component);
			} );
		}
	} );

	jQuery(document).ready(function() {

		$stories.initialize(function() {
			component = $(this);
			size_stories(component);
		} );

		jQuery('[data-load-more]').on('click', function(e) {
			if ($(this).hasClass('loading') ) {
				return;
			}
			$(this).addClass('loading');
			$(this).text('loading...');
			$(this).attr('role', 'alert');
			node = $(this).parents('.fl-module').data('node');
			current_page = $(this).attr('data-page');
			thumb_size = $(this).attr('data-thumb_size');
			e.preventDefault();
			$.ajax( {
				url: '/wp-json/fp/v1/pagination',
				data: {
					paged: current_page,
					thumb_size: thumb_size,
					q: $(e.currentTarget).data('q'),
					node: node
				},
				type: 'GET'
			} ).done(function(data) {
				$('[data-load-more]').removeClass('loading');
				if (data.node) {
					parse_more_results(data);
				}
			} );
		} );
	} );


}(jQuery) );

function size_stories(component) {
	set_image_sizes(component);
	setTimeout(function() {
		sync_textbox_heights(component);
	}, 500);
}

function parse_more_results(data) {

	if (data.posts.length) {
		for (var i = 0; i < data.posts.length; i++) {
			if (! i) {
				story_class = 'scroll';
			} else {
				story_class = '';
			}

			var excerpt = '';
			if (data.posts[i].post_excerpt) {
				excerpt = '<p>' + data.posts[i].post_excerpt + '</p>';
			}

			var label = '';

			if (data.posts[i].label) {
				label = '<div class="tag-bar-below accent-bar-short">' + data.posts[i].label + '</div>';
			}

			var story_box =
				'<div class="hide story-box ' + story_class + '">' +
				'<div class="spacer">' +
				'<div class="image-container">' +
				'<a target="_blank" href="' + data.posts[i].permalink + '" tabindex="-1" aria-disabled="true">' +
				data.posts[i].thumbnail +
				'</a>' +
				'</div>' +
				'<div class="text-container" style="height: 180px;">' + label +
				'<h2>' + data.posts[i].title + '</h2>' +
				excerpt +
				'<a target="_blank" href="' + data.posts[i].permalink + '" class="cta-link" aria-label="Read the full story about ' + data.posts[i].title + '">' +
				'Read <span>More</span>' +
				'</a>' +
				'</div>' +
				'</div>' +
				'</div>';
			jQuery('.fl-node-' + data.node + ' .story-main').append(story_box);
		}
	}
	scrolPoint = jQuery('.fl-node-' + data.node + ' .story-box.scroll').offset().top - 50;
	jQuery('html').animate( {
		scrollTop: scrolPoint
	}, 500);
	jQuery('.fl-node-' + data.node + ' .story-main .story-box').removeClass('scroll');
	component = jQuery('.fl-node-' + data.node + ' .component_stories');
	size_stories(component);

	has_feature = component.find('.story-box.featured').length;

	if (data.paged && 'hide' != data.paged) {
		jQuery('.fl-node-' + data.node + ' [data-load-more]').attr('data-page', data.paged).text('Load more').attr('role', 'button').blur();
		end = data.paged * data.posts_per_page + has_feature;
		total = data.posts_per_page + has_feature + parseInt(data.found_posts);
		text = 'Results 1-' + end + ' of ' + total;
	} else {
		temp = jQuery('.fl-node-' + data.node + ' [data-pagination] [data-js-counts]').text();
		total = temp.split(' ').pop();
		jQuery('.fl-node-' + data.node + ' [data-load-more]').remove();
		text = 'Results ' + total + ' of ' + total;
	}
	jQuery('.fl-node-' + data.node + ' [data-pagination] strong').text(text);


}

function sync_textbox_heights(data) {
	h = 0;
	data.find('.story-box .text-container').each(function() {
		jQuery(this).css('height', 'auto');
		if (! data.hasClass('-one-items') ) {
			if (h < jQuery(this).height() ) {
				h = jQuery(this).height();
			}
		}
	} );
	if (! data.hasClass('-one-items') ) {
		data.find('.story-box:not(.featured) .text-container').height(h);
	}
}

function set_image_sizes(data) {
	w = data.find('.story-box:not(".featured") .image-container:first').width();
	h = w / 1.85;
	data.find('.story-box:not(.featured) .image-container').height(h);
}

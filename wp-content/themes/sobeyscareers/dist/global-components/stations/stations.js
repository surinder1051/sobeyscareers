var selected = false;
var stationModalControls;
var stationModalNext = 1;
var stationModalPrev = 0;

(function($) {

	var $stations = $('[data-js-stations]');
	if (! $stations.length) {
		return; // Return if component isn't on the page
	}

	$('.map-module-level-2 .top-text button').click(function(e) {
		e.preventDefault();
		id = $(this).parents('.fl-module').data('node');
		$('.fl-node-' + id + ' .map-module-level-2').hide();
		$('.fl-node-' + id + ' .map-module-level-1').show();
		$('.fl-node-' + id + ' .map-module-level-2 li a').addClass('active');
		initMap(id);
	} );

	/*
		This button has been removed but keeping the js in case we need to add it back
		$('.map-module-level-3 .top-nav a:first-child').click(function(e) {
		e.preventDefault();
		id = $(this).parents('.fl-module').data('node');
		$('.fl-node-'+id+' .map-module-level-3').hide();
		$('.fl-node-'+id+' .map-module-level-1').show();
		$('.fl-node-'+id+' .map-module-level-2 li a').addClass('active').removeClass('single_active').removeClass('single_view');
		initMap( id );
	});*/

	$('.map-module-level-3 .top-nav a.type').click(function(e) {
		e.preventDefault();
		id = $(this).parents('.fl-module').data('node');
		$('.fl-node-' + id + ' .map-module-level-2 li a.single_active').removeClass('single_active');
		$('.fl-node-' + id + ' .map-module-level-2 li a.active').removeClass('single_view');
		$('.fl-node-' + id + ' .map-module-level-3').hide();
		$('.fl-node-' + id + ' .map-module-level-2').show();
		initMap(id);
	} );

	$('.info-image').click(function(e) {
		var largeimage = $(this).attr('data-large-image');
		$('.map-preview-large').attr('src', largeimage);
		$('.interactive-map-lightbox-wrap').fadeIn();
	} );

	$('.map-area').on('click', 'h4', function() {
		var station = $(this).text();
		var type = $(this).parent().find('.type').data('type');
		var component = $(this).parents('.component_stations');
		component.find('.map-module-level-1 button:contains("' + type + '")').click();
		component.find('.map-module-level-2 a:contains("' + station + '")').click();
	} );

	$('.lightbox-close').stop().click(function(e) {
		e.preventDefault();
		Close();
	} );

	$('[data-js-stations] .cta-text button').click(function(e) {
		var type = $(this).text();
		open_region(this, type);
	} );

	$('[data-js-stations] .scroll-list ul li a').click(function(e) {

		e.preventDefault();

		var id = $(this).parents('.fl-module').data('node');

		$('.fl-node-' + id + ' .map-module-level-3 tr').hide();

		type = $(this).data('facility_type');
		if ('' != type) {
			$('.fl-node-' + id + ' .map-module-level-3 td.type').text(type).parents('tr').show();
		}

		region = $(this).data('facility_region');
		if ('' != region) {
			$('.fl-node-' + id + ' .map-module-level-3 td.region').text(region).parents('tr').show();
		}

		city = $(this).data('facility_city');
		if ('' != city) {
			$('.fl-node-' + id + ' .map-module-level-3 td.city').text(city).parents('tr').show();
		}

		located_on = $(this).data('facility_located_on');
		if ('' != located_on) {
			$('.fl-node-' + id + ' .map-module-level-3 td.located_on').text(located_on).parents('tr').show();
		}

		units = $(this).data('facility_units');
		if ('' != units) {
			$('.fl-node-' + id + ' .map-module-level-3 td.units').text(units).parents('tr').show();
		}

		date = $(this).data('facility_date');
		if ('' != date) {
			$('.fl-node-' + id + ' .map-module-level-3 td.date').text(date).parents('tr').show();
		}

		capacity = $(this).data('facility_capacity');
		if ('' != capacity) {
			$('.fl-node-' + id + ' .map-module-level-3 td.capacity').text(capacity).parents('tr').show();
		}

		link_url = $(this).data('facility_link_url');
		link_title = $(this).data('facility_link_title');

		if ('' == link_title) {
			link_title = link_url;
		}

		if ('' != link_url) {
			if (-1 < link_url.indexOf('http') ) {
				link = '<a target="_blank" href="' + link_url + '">' + link_title + '</a>';
			} else {
				link = '<a href="' + link_url + '">' + link_title + '</a>';
			}
			$('.fl-node-' + id + ' .map-module-level-3 td.link').html(link).parents('tr').show();
		}

		large_image = $(this).data('facility_img_full');
		small_image = $(this).data('facility_img_thumb');

		if ('' != small_image) {
			$('.fl-node-' + id + ' .map-module-level-3 .info-image').attr('data-large-image', large_image).show();
			$('.fl-node-' + id + ' .map-module-level-3 .info-image > img').attr('src', small_image);
		} else {
			$('.fl-node-' + id + ' .map-module-level-3 .info-image').hide();
		}

		temp_span = $('.fl-node-' + id + ' .map-module-level-3 .heading-6 span').html();
		$('.fl-node-' + id + ' .map-module-level-3 .heading-6').html('<span class="icon">' + temp_span + '</span>' + $(this).text() );

		$('.fl-node-' + id + ' .map-module-level-2 li a.active').addClass('single_view');
		$(this).addClass('single_active');

		$('.fl-node-' + id + ' .map-module-level-2').hide();
		$('.fl-node-' + id + ' .map-module-level-3').show();

		initMap(id);

	} );

	$(document).keydown(function(e) {
		if (27 == e.keyCode) {
Close();
}
	} );

	$(document).ready(function(e) {
		$stations.each(function() {

			var id = $(this).parents('.fl-module').data('node');
			var mapheight = $(this).find('.map-area').innerHeight();
			$(this).find('.map-content-area').css('height', mapheight);
			$(this).find('#map').attr('id', 'map_' + id);

			initMap(id);

			if (jQuery(this).find('.filter_by_post').length) {
				var type = [];
				jQuery(this).find('.filter_by_post a').each(function() {
					type.push(jQuery(this).data('facility_type') );
				} );
				jQuery(this).find('.map-module-level-1 .cta-text button').each(function() {
					if (0 > jQuery.inArray(jQuery(this).text(), type) ) {
						jQuery(this).parents('li').remove();
					}
				} );
			}

			if (jQuery(this).hasClass('station_type') || jQuery(this).hasClass('single_station') ) {
				if (1 == jQuery(this).find('.map-module-level-1 .cta-text button').length) {
					jQuery(this).find('.map-module-level-1 .cta-text button').click();
					jQuery(this).find('.map-module-level-2 .top-text span').remove();
				}
			}

		} );
	} );
	$(document).keydown(function(e) {
		var keyCode = (e.which) ? e.which : e.KeyCode;

		if ($('.interactive-map-lightbox-wrap').is(':visible') ) {
			switch (keyCode) {
				case 9:
					e.preventDefault();
					resetStationModalTabbing();
					if (e.shiftKey) {
						stationModalBackward($('.interactive-map-lightbox-wrap') );
					} else {
						stationModalForward($('.interactive-map-lightbox-wrap') );
					}
					break;
				case 39:
					stationModalBackward($('.interactive-map-lightbox-wrap') );
					break;
				case 37:
					stationModalForward($('.interactive-map-lightbox-wrap') );
					break;
				case 27:
					Close();
					break;
				case 13:
					if ('nav-control' != e.target.className.match(/nav\-control/) ) {
						$('.interactive-map-lightbox-wrap .lightbox-close').focus();
					} else {
						$(e).focus();
					}
					break;
				default:
					break;
			}
		}
	} );

	function resetStationModalTabbing() {
		stationModalControls = new Array();

		//these need to be reset everytime in case the view switches
		$('.interactive-map-lightbox-wrap .modal-action').each(function() {
			if ($(this).is(':visible') ) {
				stationModalControls.push($(this) );
			}
		} );
		for (var i = 0; i < stationModalControls.length; i++) {
			if (1 == stationModalControls.length) {
				stationModalNext = 0;
				stationModalPrev = 0;
			} else if ($(stationModalControls[i] ).hasClass('is-tabbing') ) {
				stationModalNext =  ( (1 + i) == stationModalControls.length) ? 0 : (1 + i);
				stationModalPrev =  (0 > (-1 + i) ) ? stationModalControls.length - 1 : (-1 + i);
			}
		}
	}

	function stationModalBackward(container) {
		var prev = stationModalPrev - 1;
		$('.modal-action', container).removeClass('is-tabbing');
		$(stationModalControls[stationModalPrev],  container).focus().addClass('is-tabbing').attr('tabindex', '0');
		if (0 == stationModalPrev) {
			next = (stationModalControls.length - 1);
		}
		stationModalPrev = prev;
	}
	function stationModalForward(container) {
		var next = 1 + stationModalNext;
		$('.modal-action', container).removeClass('is-tabbing');
		$(stationModalControls[stationModalNext], container).focus().addClass('is-tabbing').attr('tabindex', '0');
		if (next == stationModalControls.length) {
			next = 0;
		}
		stationModalNext = next;
	}

	function Close() {
		$('.interactive-map-lightbox-wrap').fadeOut();
		$('.interactive-map-lightbox-wrap .modal-action').each(function() {
			$(this).removeClass('is-tabbing').attr('tabindex', '');
		} );
	}

	function open_region(data, type) {

		id = $(data).parents('.fl-module').data('node');

		if ($('.fl-node-' + id + ' [data-js-stations]').hasClass('station_type') ) {
			$('.fl-node-' + id + ' .map-module-level-2 .top-text button').text('All ' + type + '');
			$('.fl-node-' + id + ' .map-module-level-3 .top-nav a').text('All ' + type + '');
		}

		$('.fl-node-' + id + ' .map-module-level-2 h3').text(type);
		$('.fl-node-' + id + ' .map-module-level-3 a.type').text('All ' + type).attr('disabled', false).attr('aria-disabled', false).attr('aria-label', 'Back to ' + type);
		$('.fl-node-' + id + ' .map-module-level-2 ul').each(function() {
			$('li', this).each(function() {
				$(this).find('a').removeClass('active');
				station_type = $(this).data('type');
				if (-1 < station_type.indexOf(type) ) {
					$(this).show().find('a').addClass('active');
				} else {
					$(this).hide();
				}
			} );
			if (1 > $('.active', this).length) {
				$(this).hide().prev('.heading-6').hide();
			} else {
				$(this).show().prev('.heading-6').show();
			}
		} );

		$('.fl-node-' + id + ' .map-module-level-3 .heading-6').html('<span class="icon">' + $(data).parents('li').find('.icon').html() + '</span>');
		$('.fl-node-' + id + ' .map-module-level-1').hide();
		$('.fl-node-' + id + ' .map-module-level-2').show();

		if ($('.fl-node-' + id + ' .component_stations').hasClass('single_station') && ! window.selected) {
			$('.fl-node-' + id).find('a.active').click();
			$('.fl-node-' + id).find('.map-module-level-3').show();
			window.selected = true;
		}

		initMap(id);
	}

	function initMap(id) {

		// if ( 768 > $(document).width() ) return;

		// Map Parameter
		var lat = '44.79203208187161';
		var long = '-80.29557396136576';
		var zoom = 8;
		var center = new google.maps.LatLng(lat, long);
		var mapOptions = {
			center: center,
			zoom: zoom,
			maxZoom: 12,
			mapTypeId: google.maps.MapTypeId.roadmap
		};

		// Map Theme
		var mapStyle = [ {
			'featureType': 'landscape',
			'stylers': [ {
				'color': '#c2e6b0'
			}, {
				'visibility': 'on'
			} ]
		}, {
			'featureType': 'poi',
			'stylers': [ {
				'visibility': 'off'
			} ]
		}, {
			'featureType': 'road',
			'stylers': [ {
				'visibility': 'off'
			} ]
		}, {
			'featureType': 'transit',
			'stylers': [ {
				'visibility': 'off'
			} ]
		}, {
			'featureType': 'water',
			'stylers': [ {
				'color': '#74d6ff'
			} ]
		} ];


		var map = new google.maps.Map(document.getElementById('map_' + id), mapOptions);

		//set style
		map.set('styles', mapStyle);

		// Map Info Windows
		var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var locations = [];
		var bounds = new google.maps.LatLngBounds();

		if (0 < $('.fl-node-' + id + ' .map-module-level-2 .single_active').length) {


			lat 		= $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_latitude');
			long 		= $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_longitude');
			description = $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_description');
			icon		= $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_icon');
			heading 	= $.trim($('.fl-node-' + id + ' .map-module-level-2 li a.single_active').text() );

			if ('' == description) {
				type = '<div class="hide_phone"><strong>Generation type:</strong> ' + $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_type');
				city = '<strong>Nearest city/town:</strong> ' + $('.fl-node-' + id + ' .map-module-level-2 li a.single_active').data('facility_city') + '</div>';
				description = type + '<br/>' + city;
			}

			var temp = {
				lat: lat,
				long: long,
				heading: heading,
				description: description,
				icon: icon
			};

			locations.push(temp);

		} else {

			$('.fl-node-' + id + ' .map-module-level-2 li a.active').each(function() {

				lat 		= $(this).data('facility_latitude');
				long 		= $(this).data('facility_longitude');
				description = $(this).data('facility_description');

				if ('' == description) {
					type = '<div class="hide_phone"><strong class="type" data-type="' + $(this).data('facility_type') + '">Generation type:</strong> ' + $(this).data('facility_type');
					city = '<strong>Nearest city/town:</strong> ' + $(this).data('facility_city') + '</div>';
					description = type + '<br/>' + city;
				}

				icon		= $(this).data('facility_icon');
				heading 	= $.trim($(this).text() );

				if (lat && long) {
					var temp = {
						lat: lat,
						long: long,
						heading: heading,
						description: description,
						icon: icon
					};
					locations.push(temp);
				}
			} );

		}

		for (i = 0; i < locations.length; i++) {

			var icon = {
				url: locations[i].icon,
				scaledSize: new google.maps.Size(32, 32), // scaled size
				origin: new google.maps.Point(0, 0), // origin
				anchor: new google.maps.Point(16, 32) // anchor
			};

			var marker = new google.maps.Marker( {
				position: new google.maps.LatLng(locations[i].lat, locations[i].long),
				map: map,
				icon: icon,
				title: ''
			} );

			bounds.extend(marker.position);

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				var Html = '<div class=\'location-info\'>';
				Html += '<h4 style=\'color: #06c;text-decoration: underline;font-weight: 700;\' role=\'button\'>' + locations[i].heading + '</h4>';
				Html += '<p class=\'location-description\'>' + locations[i].description + '</p>';
				Html += '</div>';

				infowindow.setContent(Html);
				infowindow.open(map, marker);
			};
			} (marker, i) ) );
		}

		map.fitBounds(bounds);

	}

}(jQuery) );

function _toConsumableArray(arr) {
 if (Array.isArray(arr) ) {
 for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
 arr2[i] = arr[i];
} return arr2;
} else {
 return Array.from(arr);
}
}

document.addEventListener('DOMContentLoaded', function(event) {
	(function() {
		var wakeyFixRequired = 'CSS' in window && 'function' === typeof CSS.supports && ! CSS.supports('-webkit-appearance', 'none');
		var ROOT_CLASS = 'scrollscreen';
		var createElement = function createElement(tag, name) {
			var el = document.createElement(tag);
			el.className = ROOT_CLASS + '-' + name;
			return el;
		};
		var createScrollscreen = function createScrollscreen(root) {
			var fragment = document.createDocumentFragment();
			[].concat(_toConsumableArray(root.childNodes) ).forEach(function(child) {
				fragment.appendChild(child);
			} );

			var content = createElement('div', 'content');
			content.appendChild(fragment);
			root.appendChild(content);

			var track = createElement('div', 'track');
			root.appendChild(track);

			var slider = createElement('div', 'slider');
			track.appendChild(slider);

			var pendingFrame = null;

			var redraw = function redraw() {

				cancelAnimationFrame(pendingFrame);

				pendingFrame = requestAnimationFrame(function() {

					var contentHeight = content.scrollHeight;
					var containerHeight = root.offsetHeight;
					var percentageVisible = containerHeight / contentHeight;
					var sliderHeight = percentageVisible * containerHeight;
					var percentageOffset = content.scrollTop / (contentHeight - containerHeight);
					var sliderOffset = percentageOffset * (containerHeight - sliderHeight);

					track.style.opacity = 1 === percentageVisible ? 0 : 1;

					slider.style.cssText = '\n                    height: ' + sliderHeight + 'px;\n                    transform: translateY(' + sliderOffset + 'px);\n                ';
				} );
			};

			// need to update if window is resized or if container is scrolled
			content.addEventListener('scroll', redraw);
			window.addEventListener('resize', redraw);

			// first redraw
			redraw();
			if (! wakeyFixRequired) {
				return;
			}
			var wakey = function wakey() {
				requestAnimationFrame(function() {
					var offset = content.scrollTop;
					content.scrollTop = offset + 1;
					content.scrollTop = offset;
				} );
			};

			// wake up scrollbars on MacOS Firefox
			root.addEventListener('mouseenter', wakey);

			// trigger so it's drawn correctly for the first time
			wakey();
		};

		// create scrollies
		[].concat(_toConsumableArray(document.querySelectorAll('[data-js-stations] .' + ROOT_CLASS) ) ).forEach(createScrollscreen);
	} () );
} );



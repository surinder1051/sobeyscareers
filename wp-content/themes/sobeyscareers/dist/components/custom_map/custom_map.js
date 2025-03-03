var map_content_preview = function(e) {
	var popup = decodeURIComponent(jQuery(e).attr('data-content') );
	jQuery('.map-preview-large').html(popup);
	jQuery('.interactive-map-lightbox-wrap').fadeIn();
};

(function($) {

	var $customMaps = $('[data-js-custom_map]');
	if (! $customMaps.length) {
		return; // Return if component isn't on the page
	}

	$('.map-area').on('click', 'h4', function() {
		var title = $(this).text();
		var type = $(this).parent().find('.type').data('type');
		var component = $(this).parents('.component_custom_map');
	} );

	$('.lightbox-close').on('click', function(e) {
		e.preventDefault();
		Close();
	} );

	$(document).keydown(function(e) {
		var keycode = (e.which) ? e.which : e.keyCode;
		if (27 == keycode) {
			Close();
		}
	} );

	$(document).ready(function(e) {
		$customMaps.each(function() {

			var id = $(this).parents('.fl-module').data('node');
			var mapheight = $(this).find('.map-area').innerHeight();
			$(this).find('.map-content-area').css('height', mapheight);
			$(this).find('#map').attr('id', 'map_' + id);
			if ($('.cta-list li', this).length && 'object' === typeof google) {
				initMap(id);
			}

		} );
	} );
	$(document).keydown(function(e) {
		var keyCode = (e.which) ? e.which : e.KeyCode;

		if ($('.interactive-map-lightbox-wrap').is(':visible') ) {
			switch (keyCode) {
				case 27:
					Close();
					break;
				default:
					break;
			}
		}
	} );


	function Close() {
		$('.interactive-map-lightbox-wrap').fadeOut('slow', function() {
			$('.map-preview-large', this).attr('html', '');
		} );
		$('.interactive-map-lightbox-wrap .modal-action').each(function() {
			$(this).removeClass('is-tabbing').attr('tabindex', '');
		} );
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
				'color': '#f4f4f4'
			}, {
				'visibility': 'on'
			} ]
		}, {
			'featureType': 'poi.park',
			'stylers': [ {
				'color': '#f0f0f0'
			}, {
				'visibility': 'on'
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
				'color': '#e8e8e8'
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

		$('.fl-node-' + id + ' .cta-list li').each(function() {

			lat 		= $(this).data('latitude');
			long 		= $(this).data('longitude');
			description = encodeURIComponent($(this).data('content') );
			buttonText	 = $(this).data('button');
			buttonTheme = $(this).data('theme');
			icon		= $(this).data('icon');
			heading 	= $(this).data('title');

			if (lat && long) {
				var temp = {
					lat: lat,
					long: long,
					heading: heading,
					description: description,
					icon: icon,
					button: buttonText,
					theme: buttonTheme
				};
				locations.push(temp);
			}
		} );

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
				var Html = '<div class="location-info" data-content="' + locations[i].description + '" onclick="javascript:map_content_preview(this)">';
				Html += '<h4>' + locations[i].heading + '</h4>';
				Html += '<button class="location-description">' + locations[i].button + '</button>';
				Html += '</div>';

				infowindow.setContent(Html);
				infowindow.open(map, marker);

			};
			} (marker, i) ) );
		}

		map.fitBounds(bounds);

	}

}(jQuery) );

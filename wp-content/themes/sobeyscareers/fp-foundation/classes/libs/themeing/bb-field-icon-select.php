<?php
/**
 * BB Field Icon Select
 * Creates the custom svg icon selector
 *
 * @package fp-foundation
 */

?>
<#
var theStyle;
var customID = 'fpCustomIcon' + _.random(1, 100);
var clearID = 'fpCustomIconClear' + _.random(1, 100);
var iconsset = [];
var bbOpt = '';
var clearStyle = '';

var bbCustomSelectField = function (element, parentEl, clearID) {
	var container = jQuery('#' + parentEl );
	var allLabels = jQuery('label', container );
	var parLabel = jQuery(element).closest('label');

	var clearButton = jQuery('#' + clearID + ' button:eq(0)' );

	var clearLeft = 23;
	var clearTop = -5;

	if ( 0 < jQuery('label', container ).length ) {
		var i = 0;
		jQuery('label', container ).each(function() {
			jQuery(this).removeClass('is-checked');
			if (jQuery('input:eq(0)', this).val() == element.value ) {
				if ( 7 <= i ) {
					clearLeft = 23 + ((i - 7) * 40);
					clearTop = 30 * (Math.floor(i/7));
				} else {
					clearLeft = 23 + (i * 40);
				}
			} else {
				jQuery('input:eq(0)', this).attr('checked', false).removeAttr('checked');
			}
			i++;
		})
	}

	jQuery(parLabel).addClass('is-checked');
	jQuery(clearButton).hide().css({'left' : clearLeft + 'px', 'top' : clearTop + 'px' }).show();
	jQuery('#' + clearID).show();
}


theStyle = 'background-color: #ffffff';
#>
<div class="bb-select-icon-wrapper" id="{{customID}}">
	<span class='bb-select-item'>
		<label for="bb-icon-saved-{{customID}}" title='{{{data.name}}}'>
			<input type='radio' value='{{data.value}}' id='bb-icon-saved-{{customID}}' name='{{data.name}}' />
			<span class='select-overlay bg-check' style='{{theStyle}}'></span>
		</label>
	</span>
</div>
<div class="bb-clear-icon-wrapper" id="{{clearID}}">
	<label for="bb-select-transparent" style="display:none">
		<input type='radio' value='transparent' id='bb-select-transparent' name='{{data.name}}' />
	</label>
	<button onclick='bbCustomSelectClear(this, "{{customID}}", "{{clearID}}" )' ><span class="dashicons dashicons-no" title="<?php esc_attr_e( 'Clear Selection', 'fp' ); ?>" aria-label="<?php esc_attr_e( 'Clear Selection', 'fp' ); ?>"></span></button>
</div>
<#
jQuery.getJSON(wpApiSettings.root + 'wp/v1/fp-component-icon-select/', function (response) {
	var defaultItem = jQuery('#' + customID + ' .bb-select-item:eq(0)' );
	if ( response.length == 0) {
		console.log( 'no elements' );
		// if (response.length === 0) {
		// 	var noIcons = document.createElement('span');
		// 	noIcons.innerHTML = 'No icons in library.';
		// 	document.getElementById(customID).appendChild(noIcons);
		// 	debugger;
		// 	jQuery('.bb-select-icon-wrapper', customID).hide();
		// 	return;
		// }

		// debugger;
		// jQuery('.bb-select-icon-wrapper', customID).show();
	} else {

		jQuery.each(response, function (key, iconImg) {
			var defaultLabel = jQuery('#' + customID + ' label:eq(0)' );
			if (data.value === iconImg.src) {
				jQuery(defaultLabel).attr('title', iconImg.title );
				jQuery(defaultLabel).attr('for', 'bb-icon-' + iconImg.index + '-' + customID );
				jQuery(defaultLabel).addClass('is-checked');
				jQuery('input:eq(0)', defaultLabel).attr('checked', true);

				jQuery('span:eq(0)', defaultLabel ).css({'background-image': 'url(' + iconImg.src.replace('+', '%20').replace(' ', '%20') + ')' });
				jQuery('#' + clearID).show();

			} else {
				theStyle = 'background-image: url(' + iconImg.src + ')';
				var bbSelect = document.createElement('span');
				var bbLabel = document.createElement('label');
				var bbRadio = document.createElement('input');
				var bbOverlay = document.createElement('span');
				var bbText = document.createElement('span');

				bbOverlay.style.backgroundImage = 'url(' + iconImg.src.replace('+', '%20').replace(' ', '%20') + ')';
				bbOverlay.className = 'select-overlay';
				bbOverlay.title = iconImg.title;

				bbText.className = 'select-text';

				bbRadio.type = 'radio';
				bbRadio.name = data.name;
				bbRadio.value = iconImg.src;
				bbRadio.id = 'bb-icon-' + iconImg.index + '-' + customID;

				bbLabel.for = 'bb-icon-' + iconImg.index + '-' + customID;

				bbLabel.appendChild(bbRadio);
				bbLabel.appendChild(bbOverlay);
				bbLabel.appendChild(bbText);

				bbSelect.className = 'bb-select-item';

				bbSelect.appendChild(bbLabel);
				jQuery('#' + customID ).append(bbSelect);
			}
		});
		jQuery( '#' + customID + ' input' ).each( function() {
			jQuery(this).on('change', function() {
				bbCustomSelectField(this, customID, clearID);
			} );
		});
		// The default is sticky when you switch off and switch back
		jQuery( '#' + customID + ' label:eq(0)' ).on( 'click', function() {
			jQuery('input', this).trigger('change' );
		});

		if (! jQuery('input', defaultItem).is(':checked') ) {
			jQuery('#' + customID + ' .bb-select-item:eq(0)' ).hide().empty();
			jQuery('#' + clearID).hide();
		}
	}
});

#>

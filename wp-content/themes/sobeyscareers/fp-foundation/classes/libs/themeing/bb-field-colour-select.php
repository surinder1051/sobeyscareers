<?php
/**
 * BB Field Colour Select
 * Creates the custom colour theme options for BB
 *
 * @package fp-foundation
 */

?>
<#
var theStyle;
var customID = 'fp-custom-' + data.field.element + '-colour' + _.random(1, 100);
var clearID = 'fpCustomColourClear' + _.random(1, 100);
var colourrs = [];
var bbOpt = '';

function bbCustomThemeShow(parentEl) {
	jQuery('#' + parentEl + ' ul:eq(0)').removeClass('hidden');
}

function bbCustomColourSelect(element, parentEl) {

	var svgEl = jQuery('#' + parentEl + ' button:eq(0) g:eq(0)');
	jQuery('#bb-colour-saved-' + parentEl).val(jQuery(element).attr('data-value'));
	jQuery('#' + parentEl + ' button:eq(0)').css({'background' : jQuery(element).attr('data-style')});
	jQuery(svgEl).attr('fill', jQuery(element).attr('data-textfill'));
	jQuery('#' + parentEl + ' ul:eq(0)').addClass('hidden');

}

function bbCustomColourClear(element, parentEl) {
	jQuery('#bb-colour-saved-' + parentEl).val('');
	jQuery('#' + parentEl + ' button:eq(0)').css({'background' : 'transparent'});
	jQuery('#' + parentEl + ' ul:eq(0)').addClass('hidden');
}

#>
<div class="bb-select-colour-wrapper" id="{{customID}}">
	<ul class="bb-select-colour-list hidden" id="ul-{{customID}}">
		<li><span style="background:#fff;color: #757575" data-value="" data-textfill="#757575" data-style="transparent">None</span></li>
	</ul>
	<button data-target="ul-{{customID}}" class="fp-colour-picker-button" style="background: transparent" onclick='bbCustomThemeShow("{{customID}}")' aria-label="<?php esc_attr_e( 'Select Theme', 'fp' ); ?>">
		<svg width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g fill-rule="evenodd" fill="#757575">
				<path d="M17.7037706,2.62786498 L15.3689327,0.292540631 C14.9789598,-0.0975135435 14.3440039,-0.0975135435 13.954031,0.292540631 L10.829248,3.41797472 L8.91438095,1.49770802 L7.4994792,2.91290457 L8.9193806,4.33310182 L0,13.2493402 L0,18 L4.74967016,18 L13.6690508,9.07876094 L15.0839525,10.4989582 L16.4988542,9.08376163 L14.5789876,7.16349493 L17.7037706,4.03806084 C18.0987431,3.64800667 18.0987431,3.01791916 17.7037706,2.62786498 Z M3.92288433,16 L2,14.0771157 L10.0771157,6 L12,7.92288433 L3.92288433,16 Z"></path>
			</g>
		</svg>
	</button>
	<button data-target="ul-{{customID}}" class="fp-colour-picker-clear" onclick='bbCustomColourClear(this, "{{customID}}")' aria-label="<?php esc_attr_e( 'Clear Selection', 'fp' ); ?>">
		<svg width="13px" height="13px" viewBox="0 0 13 13" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g transform="translate(-321.000000, -188.000000)">
				<path d="M326.313708,193.313708 L326.313708,186.313708 L328.313708,186.313708 L328.313708,193.313708 L335.313708,193.313708 L335.313708,195.313708 L328.313708,195.313708 L328.313708,202.313708 L326.313708,202.313708 L326.313708,195.313708 L319.313708,195.313708 L319.313708,193.313708 L326.313708,193.313708 Z" transform="translate(327.313708, 194.313708) rotate(-45.000000) translate(-327.313708, -194.313708) "></path>
			</g>
		</svg>
	</button>
	<input type='text' value='{{data.value}}' id='bb-colour-saved-{{customID}}' name='{{data.name}}' style="display:none" />
</div>
<#
jQuery.getJSON(wpApiSettings.root + 'wp/v1/fp-component-colour-select/?bb_element=' + data.field.element, function (response) {
	var defaultItem = jQuery('#' + customID + ' .bb-select_item:eq(0)' );
	jQuery.each(response, function (key, colour) {

		if ((data.value == (colour.name + ' ' + colour.name + '_' + data.field.element)) || data.value == colour.name) { // data.value == colour.name added to support backwards compatibility for IE
			jQuery('#' + customID + ' button:eq(0)' ).css({'background' : colour.default_hex });
			jQuery('#' + customID + ' button:eq(0) g:eq(0)' ).attr({'fill' : colour.text_hex });

		}

		var bbSelectLi = document.createElement('li');
		var bbSelectSpan = document.createElement('span');
		bbSelectSpan.innerText = colour.text;
		bbSelectSpan.style.background = colour.default_hex;
		bbSelectSpan.style.color = colour.text_hex;
		bbSelectSpan.setAttribute('data-value', colour.name + ' ' + colour.name + '_' + data.field.element);
		bbSelectSpan.setAttribute('data-style', colour.default_hex);
		bbSelectSpan.setAttribute('data-textfill', colour.text_hex);

		bbSelectSpan.addEventListener('mouseover', function(e) {
			e.target.style.background = colour.hover_hex;
			e.target.style.color = colour.text_hover_hex;
		} );

		bbSelectSpan.addEventListener('mouseout', function(e) {
			e.target.style.background = colour.default_hex;
			e.target.style.color = colour.text_hex;
		} );

		bbSelectSpan.addEventListener('click', function(e) {
			bbCustomColourSelect(this, customID)
		});

		bbSelectLi.appendChild(bbSelectSpan);

		jQuery('#' + customID + ' ul:eq(0)').append(bbSelectLi);

	});

	jQuery('#' + customID + ' li:eq(0) span:eq(0)').on('click', function() {
		bbCustomColourSelect(this, customID);
	});

});

#>

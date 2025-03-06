<?php //phpcs:ignore
/**
 * BB Field Taxonomies Select Dropdown
 *
 * @package fp-foundation
 */

namespace fp;

require 'class-setup-select-taxonomy-rest-response.php';

/**
 * Custom BB post select dropdown for the saved posts.
 *
 * @param string $name is the settings name.
 * @param string $value is the saved value.
 * @param array  $field are the field attributes.
 *
 * @return void
 */
function bb_field_taxonomies_select_dropdown( $name, $value, $field ) {

	$multiple = ( isset( $field['multi-select'] ) && $field['multi-select'] ) ? ' multiple ' : '';

	echo "<select data-ajax-bb-field-taxonomies data-value='" . json_encode( $value ) . "' "; //phpcs:ignore

	if ( ! empty( $multiple ) ) {
		echo "name='{$name}[]' $multiple>"; //phpcs:ignore
	} else {
		echo "name='$name'>";  //phpcs:ignore
	}

	echo '<option value="">' . esc_attr__( 'Select', 'fp' ) . '</option>';

	echo '</select>';
}

add_action( 'fl_builder_control_fp-taxonomies-select-dropdown', 'fp\bb_field_taxonomies_select_dropdown', 1, 3 );

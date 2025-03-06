<?php //phpcs:ignore
/**
 * BB Field Checkbox
 *
 * @package fp-foundation
 */

namespace fp;

require 'class-setup-select-posts-rest-response.php';

/**
 * Custom BB post select dropdown for the query override.
 *
 * @param string $name is the settings name.
 * @param string $value is the saved value.
 * @param array  $field are the field attributes.
 *
 * @return void
 */
function bb_field_post_select_dropdown( $name, $value, $field ) {

	$defaults = array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'orderby'        => 'name',
		'order'          => 'asc',
	);

	$args = wp_parse_args( $field, $defaults );

	$multiple = ( isset( $field['multi-select'] ) && $field['multi-select'] ) ? ' multiple ' : '';

	echo "<select data-ajax-bb-field-posts='" . json_encode( $args ) . "' data-value='" . esc_attr( $value ) . "' "; //phpcs:ignore

	if ( ! empty( $multiple ) ) {
		echo "name='{$name}[]' {$multiple}>"; //phpcs:ignore
	} else {
		echo "name='{$name}'>"; //phpcs:ignore
	}

	echo "<option value=''>" . esc_attr__( 'Select', 'fp' ) . '</option>';

	if ( isset( $field['create'] ) && $field['create'] ) {
		echo "<option value='add'>" . esc_attr__( 'Add New', 'fp' ) . '</option>';
	}

	echo '</select>';
}

add_action( 'fl_builder_control_fp-post-select-dropdown', 'fp\bb_field_post_select_dropdown', 1, 3 );

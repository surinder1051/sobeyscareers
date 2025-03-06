<?php
/**
 * BB Field Checkbox
 *
 * @package fp-foundation
 */

namespace fp;

/**
 * Custom BB checkbox field for BB settings.
 *
 * @param string $name is the settings name.
 * @param string $value is the saved value.
 * @param array  $field are the field attributes.
 *
 * @return void
 */
function bb_field_checkbox( $name, $value, $field ) {

	if ( ! empty( $field['options'] ) ) {
		foreach ( $field['options'] as $key => $option ) {
			echo '<div class="bb_fp_checkbox">';
			$checked = ( ( is_array( $value ) && in_array( $key, $value ) )|| $key == $value ) ? ' checked ' : ''; //phpcs:ignore
			echo '<input type="checkbox" ' . esc_attr( $checked ) . ' name="' . esc_attr( $name ) . '[]" value="' . esc_attr( $key ) . '">';
			echo '<label for="' . esc_attr( $name ) . '">' . esc_attr( $option ) . '</label>';
			echo '</div>';
		}
	}

}

add_action( 'fl_builder_control_fp-checkbox', 'fp\bb_field_checkbox', 1, 3 );

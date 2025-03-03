<?php
/**
 * Accordion Slider shortcode class
 *
 * @package fp-slider
 */

namespace FpSlider;

/**
 * Accordion_Slider
 */
class Accordion_Slider {

	/**
	 * Enqueues scripts/styles when shortcode is used
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'fp-accordion-slider-style', get_template_directory_uri() . '/dist/plugin/fp-slider/modules/accordion-slider/accordion-slider.css', array(), FPSLIDER_PLUGIN_VERSION );
		wp_enqueue_script( 'fp-accordion-slider-script', plugin_dir_url( __FILE__ ) . 'accordion-slider.js', array( 'jquery' ), FPSLIDER_PLUGIN_VERSION, false );
	}

	/**
	 * Prepare shortcode output for display
	 *
	 * @param  mixed $atts Shortcode attributes.
	 * @param  mixed $content Shortcode content.
	 * @return string
	 */
	public function shortcode( $atts, $content = null ) {
		$this->enqueue();

		// get shortcode atts.
		$args = shortcode_atts(
			array(
				'id'               => '',
				'class'            => 'bbmodule-accordion-slider',
				'slides'           => '',
				'slide_icon'       => '',
				'slide_breakpoint' => '',
			),
			$atts
		);

		$id               = $args['id'];
		$class            = $args['class'];
		$slides           = $args['slides'];
		$slide_icon       = $args['slide_icon'];
		$slide_breakpoint = $args['slide_breakpoint'];

		$classes = array();
		$styles = array();

		if ( is_numeric( $slides ) ) {
			$slides = array( $slides );
		} elseif ( ! empty( $slides ) && is_string( $slides ) ) {
			$slides = explode( ',', $slides );
		}

		if ( is_array( $slides ) ) {
			foreach ( $slides as $key => $slide_id ) {
				$meta = get_fields( $slide_id );

				$can_show_regionalized_content = apply_filters( 'can_show_regionalized_content', $slide_id );
				if ( ! $can_show_regionalized_content || empty( $meta['content']['accordion_slide'] ) ) {
					unset( $slides[ $key ] );
				} else {
					$slide_styles = array(
						'background'           => isset( $meta['styling']['slide_color'] ) && ! empty( $meta['styling']['slide_color'] ) ? $meta['styling']['slide_color'] : '',
						'heading_colour'       => isset( $meta['styling']['heading_color'] ) && ! empty( $meta['styling']['heading_color'] ) ? $meta['styling']['heading_color'] : '',
						'button_text'          => isset( $meta['content']['accordion_slide_header'] ) && ! empty( $meta['content']['accordion_slide_header'] ) ? $meta['content']['accordion_slide_header'] : 'Expand Slide',
						'button_active_colour' => isset( $meta['styling']['active_icon_color'] ) && ! empty( $meta['styling']['active_icon_color'] ) ? $meta['styling']['active_icon_color'] : ( ! empty( $meta['styling']['slide_color'] ) ? \FLBuilderColor::adjust_brightness( str_replace( '#', '', $meta['styling']['slide_color'] ), '20', 'darken' ) : '' ),
						'slide-layout'         => $meta['display_options'] ? 'layout-' . str_replace( '_', '-', $meta['display_options'] ) : 'layout-image',
					);

					$styles[ $slide_id ] = $slide_styles;
				}
			}

			$slides = array_values( $slides );
		} else {
			echo 'Missing slides';
		}

		$class .= implode( ' ', array_unique( $classes ) );

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'accordion-slider.tpl.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Load shortcode class
	 *
	 * @return void
	 */
	public function run() {
		add_shortcode( 'fp_accordion_slider', array( $this, 'shortcode' ) );
	}
}

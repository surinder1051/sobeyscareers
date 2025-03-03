<?php

namespace fp\components;

use fp;

class z_pattern_v2 extends fp\Component
{

	public $component             = 'z_pattern_v2';                                                                                                                                                                    // Component slug should be same as this file base name
	public $component_name        = 'Z-Pattern V2';
	public $version               = '1.1.3';                                                                                                                                                                       // Shown in BB sidebar.
	public $component_description = 'Z-Pattern with Link or Button - v2';
	public $component_category    = 'FP Global';
	public $enable_css            = true;
	public $enable_js             = false;
	public $deps_css              = array();                                                                                                                                                                               // WordPress Registered CSS Dependencies
	public $deps_js               = array('jquery');                                                                                                                                                                       // WordPress Registered JS Dependencies
	public $fields                = array();                                                                                                                                                                               // Placeholder for fields used in BB Module & Shortcode
	public $bbconfig              = array();                                                                                                                                                                               // Placeholder for BB Module Registration
	public $base_dir              = __DIR__;
	public $variants              = array('-left-to-right', '-right-to-left', '-background-left', '-background-right', '-white-bg', '-grey-bg', '-link-style-button', '-link-style-text', '-square-edge', '-angle-edge');  // Component CSS Variants as per -> http://rscss.io/variants.html
	public $schema_version        = 3;                                                                                                                                                                                     // This needs to be updated manuall when we make changes to the this template so we can find out of date components
	public $load_in_header        = false;

	public function init_fields()
	{

		// Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

		/*

		Field Types:

		https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

		Code
		Color
		Editor
		Font
		Icon
		Link
		Loop
		Form
		Multiple Audios
		Multiple Photos
		Photo
		Photo Sizes
		Post Type
		Select
		Service
		Suggest
		Textarea
		Time
		Timezone
		Video

		Repeater Fields
		'multiple'      => true,
		Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.


		*/

		$this->forms = array(
			array(
				'links',
				array(
					'title' => __('Link Attributes', FP_TD),
					'tabs'  => array(
						'general'      => array(
							'title'         => __('General', FP_TD),
							'sections'      => array(
								'general'       => array(
									'title'         => '',
									'fields'        => array(
										'title' => array(
											'type' => 'text',
											'label'    	=> __('Title', FP_TD),
											'test_content' => array(
												'min' => $this->get_sample_text(1, 1131),
												'max' => $this->get_sample_text(8, 521),
											),
											'maxlength' => 30,
										),
										'link' => array(
											'type' => 'link',
											'label'    	=> __('Link', FP_TD),
											'test_content' => array(
												'min' => $this->get_sample_text(1, 1132),
												'max' => $this->get_sample_text(10, 526),
											),
										),
										'link_aria_label' => array(
											'type'     => 'text',
											'label'    => __('Aria Label', FP_TD),
										),
										'target' => array(
											'type'        => 'select',
											'label'       => __('Target Window', FP_TD),
											'default'     => 'current',
											'options'     => array(
												'current'     => 'Current Window',
												'new'	     => 'New Window',
											),
										),
									),
								),
							),
						),
					),
				),
			),
			array(
				'icon_links',
				array(
					'title' => __('Link Attributes', FP_TD),
					'tabs'  => array(
						'general'      => array(
							'title'         => __('General', FP_TD),
							'sections'      => array(
								'general'       => array(
									'title'         => '',
									'fields'        => array(
										'title' => array(
											'type' => 'text',
											'label'    	=> __('Title', FP_TD),
											'test_content' => array(
												'min' => $this->get_sample_text(1, 1131),
												'max' => $this->get_sample_text(8, 521),
											),
											'maxlength' => 30,
										),
										'link_share_icon'  => array(
											'type' => 'icon',
											'label' => __('Choose the icon', FP_TD),
											'default'   => 'fas fa-share-square'
										),
										'link' => array(
											'type' => 'link',
											'label'    	=> __('Link', FP_TD),
											'test_content' => array(
												'min' => $this->get_sample_text(1, 1132),
												'max' => $this->get_sample_text(10, 526),
											),
										),
										'link_aria_label' => array(
											'type'     => 'text',
											'label'    => __('Aria Label', FP_TD),
										),
										'target' => array(
											'type'        => 'select',
											'label'       => __('Target Window', FP_TD),
											'default'     => 'current',
											'options'     => array(
												'current'     => 'Current Window',
												'new'	     => 'New Window',
											),
										),
									),
								),
							),
						),
					),
				),
			),
		);

		$this->fields = array(
			'fp-z_pattern_v2-tab-1' => array(
				'title'         => __('Settings', FP_TD),
				'sections'      => array(
					'general' => array(
						'title'     => __('General', FP_TD),
						'fields' => array(
							'alignment' => array(
								'type'        => 'select',
								'label'       => __('Alignment', FP_TD),
								'default'     => 'right',
								'options'     => array(
									'-left-to-right'    => __('Left', FP_TD),
									'-right-to-left'   	=> __('Right', FP_TD),
									'-background-left'	=> __('Background + Text Left', FP_TD),
									'-background-right'	=> __('Background + Text Right', FP_TD)
								),
							),
						)
					),
					'data_attributes' => array(
						'title'     => __('Content', FP_TD),
						'fields'    => array(
							'heading' => array(
								'type'     => 'text',
								'label'    => __('Heading', FP_TD),
								'default'  => __('Sample Heading', FP_TD),
								'connections'   => array('string'),
							),
							'heading_type' => array(
								'type'     => 'select',
								'label'    => __('Choose the Heading Type', FP_TD),
								'default'  => __('h3', FP_TD),
								'options'   => array(
									'h1' => __('Heading 1', FP_TD),
									'h2' => __('Heading 2', FP_TD),
									'h3' => __('Heading 3', FP_TD),
									'h4' => __('Heading 4', FP_TD),
									'h5' => __('Heading 5', FP_TD),
									'h6' => __('Heading 6', FP_TD),
								),
							),
							'heading_typography' => array(
								'type'       => 'typography',
								'label'      => __('Heading Typography', FP_TD),
								'responsive' => true,
								'preview'    => array(
									'type'        => 'css',
									'selector'  => '.component_z_pattern_v2 .safety-container .text-container .heading',
								),
								'default'       => array(
									'family'        => 'Helvetica',
									'weight'        => 300
								)
							),
							'content' => array(
								'rows'          => 2,
								'type'     => 'editor',
								'label'    => __('Content', FP_TD),
								'maxlength' => 250,
								'connections'   => array('string'),
							),
							'content_typography' => array(
								'type'       => 'typography',
								'label'      => __('Content Typography', FP_TD),
								'responsive' => true,
								'preview'    => array(
									'type'        => 'css',
									'selector'  => '.safety-container .text-container p',
								),
								'default'       => array(
									'family'        => 'Helvetica',
									'weight'        => 300
								)
							),
						),
					),
					'cta_attributes' => array(
						'title'     => __('CTA', FP_TD),
						'fields'    => array(
							'link_type' => array(
								'type'        => 'select',
								'label'       => __('Link Type  ( optional )', FP_TD),
								'default'     => '-link-style-button',
								'options'     => array(
									'-link-style-text'      => __('Text', FP_TD),
									'-link-style-button'   	 => __('Button', FP_TD),
									'-link-style-links'   	 => __('Icon Links', FP_TD),
								),
								'toggle' => array(
									'-link-style-button' => array(
										'fields'   => array('link_title', 'link_url', 'target', 'button_color', 'link_aria_button'),
									),
									'-link-style-text' => array(
										'fields'   => array('text_links'),
									),
									'-link-style-links' => array(
										'fields'   => array('icons_links','link_color','link_padding'),
									)
								),
							),
							'link_title' => array(
								'type'     => 'text',
								'label'    => __('Title', FP_TD),
								'default'  => __('', FP_TD),
								'test' => true,
								'test_content' => array(
									'min' => $this->get_sample_text(5, 214),
									'max' => $this->get_sample_text(24, 158),
								),
								'maxlength' => 30
							),
							'link_aria_button' => array(
								'type'     => 'text',
								'label'    => __('Aria Label', FP_TD),
							),
							'link_url' => array(
								'type'     => 'link',
								'label'    => __('Link', FP_TD),
								'default' 		=> '',
							),
							'button_color' => array(
								'type'			=> 'fp-colour-picker',
								'label'			=> __('Choose the button color', FP_TD),
								'element'		=> 'button',
							),
							'target' => array(
								'type'        => 'select',
								'label'       => __('Target Window', FP_TD),
								'default'     => 'current',
								'options'     => array(
									'current'     => 'Current Window',
									'new'	     => 'New Window',
								),
							),
							'text_links' => array(
								'type'     => 'form',
								'form'		=> 'links',
								'label'    => __('Links', FP_TD),
								'multiple'	=> true,
							),
							'icons_links' => array(
								'type'     => 'form',
								'form'		=> 'icon_links',
								'label'    => __('Icon Links', FP_TD),
								'multiple'	=> true,
								'max'          => 2,
								'min'          => 1,
							),
							'link_color' => array(
								'type'       => 'color',
								'label'      => __('Link Color', FP_TD),
								'show_reset' => true,
								'default'       => 'FFFFFF',
								'preview'    => array(
									'type'      => 'css',
									'selector'  => '.component_z_pattern_v2 .safety-container .text-container .icon-link',
									'property'  => 'color',
									'important' => true,
						),
					),
							'link_padding' => array(
								'type' => 'dimension',
								'label' => __('Link Padding', FP_TD),
								'responsive' => true,
								'slider' => true,
								'units' => array('px'),
								'preview' => array(
									'type' => 'css',
									'selector' => '.component_z_pattern_v2 .safety-container .text-container .icon-link',
									'property' => 'padding',
								),
							),
						),
					),
					'style_attributes' => array(
						'title'     => __('Style', FP_TD),
						'fields'    => array(
							'theme' => array(
								'label'	=> __('Theme', FP_TD),
								'type'			=> 'fp-colour-picker',
								'element'		=> array('background'),
							),
							'heading_color' => array(
								'label'	=> __('Heading Color', FP_TD),
								'type'			=> 'color',
							),
							'text_padding' => array(
								'type' => 'dimension',
								'label' => __('Text Padding', FP_TD),
								'responsive' => true,
								'slider' => true,
								'units' => array('px'),
								'preview' => array(
									'type' => 'css',
									'selector' => '.component_z_pattern_v2 .safety-container .text-container',
									'property' => 'padding',
								),
							),
							'image_height' => array(
								'type'         => 'unit',
								'label'        => 'Image Height',
								'responsive' 	=> true,
								'units'          => array( 'px' ),
								'default'     => 'auto',
								'default_unit' => 'px', // Optional
								'preview'    => array(
								  'type'          => 'css',
								  'selector'      => '.component_z_pattern_v2 .safety-container .image-container img',
								  'property'      => 'height',
								),
							  ),
							'background_image' => array(
								'type'    => 'photo',
								'label'   => __('Background Image', FP_TD),
								'connections' => array('photo'), 
								'responsive' => true,
								// BB bug doens't show these fields when editing, only when adding.
								//'show' => array(
								//	'fields' => array('background_image_size', 'image_edge')
								//	)
							),
							'background_image_size_large' => array(
								'type'        => 'select',
								'label'       => __('Background Image Size (Desktop)', FP_TD),
								'options'     => get_registered_thumbnails(),
								'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size'] : 'large',
							),
							'background_image_size_medium' => array(
								'type'        => 'select',
								'label'       => __('Background Image Size (Medium)', FP_TD),
								'options'     => get_registered_thumbnails(),
								'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'] : 'medium',
							),
							'background_image_size_responsive' => array(
								'type'        => 'select',
								'label'       => __('Background Image Size (Responsive)', FP_TD),
								'options'     => get_registered_thumbnails(),
								'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'] : 'thumbnail',
							),
							'background_width' => array(
								'type'         => 'unit',
								'label'        => 'Text / Background Split',
								'description'  => 'If set to 100, text and image will stack.',
								'default' 		=> 35,
								'responsive' 	=> true,
								'units'	       => array('%'),
								'default_unit' => '%', // Optional
								'preview'	   => array(
									'type'          => 'css',
									'selector'      => '.component_z_pattern_v2 .safety-container .text-container',
									'property'      => 'width',
								),
							),
							'image_edge' => array(
								'type'        => 'select',
								'label'       => __('Image Edging', FP_TD),
								'default'     => '-square-edge',
								'options'     => array(
									'-square-edge'  => __('Square', FP_TD),
									'-angle-edge'   => __('Diagonal', FP_TD),
								),
							),
						),
					),
				),
			),
		);
	}

	// Sample as to how to pre-process data before it gets sent to the template

	public function pre_process_data($atts, $module)
	{
		$classes[] = $atts['alignment'];
		$classes[] = $atts['link_type'];
		$classes[] = $atts['image_edge'];

		if (isset($atts['theme']) && !empty($atts['theme'])) {
			$classes[] = $atts['theme'];
		}

		$atts['classes'] = implode(' ', $classes);

		$atts['background_image_size'] = $atts['background_image_size_large'];

		return $atts;
	}

	/**
	 * Overrides the utilities.php similar function as that doesn't allow you to chose an image size.
	 *
	 * @param int $id
	 * @param string $key
	 * @param array $atts
	 * @param object $module
	 * @param string $lazyload
	 * @param boolean $force_alt
	 * @return void
	 */
	public function draw_responsive_image($id, $key, $atts, $module, $lazyload = '', $force_alt = false)
	{
		$thumbnail_info = get_thumbnail_info($atts[$key . '_size']);
		$src = '';
		$src_medium = '';
		$src_responsive = '';

		if (empty($atts['background_image'])) {
			$src = "https://dummyimage.com/{$thumbnail_info['width']}x{$thumbnail_info['height']}/597544/fff";
		} else {
			$src = wp_get_attachment_image_src($id, $atts[$key . '_size'])[0];
		}

		if (!empty($module->settings->background_image_medium)) {
			$size = !empty( $atts[ $key . '_size_medium' ] ) ? $atts[ $key . '_size_medium' ] : $atts[ $key . '_size' ];
			$src_medium = wp_get_attachment_image_src(intval($module->settings->background_image_medium), $size);
			$src_medium = !empty($src_medium[0]) ? $src_medium[0] : '';
		}
		else if ( !empty( $atts['background_image_size_medium'] ) ) {
			$src_medium = wp_get_attachment_image_src($id, $atts['background_image_size_medium'] );
			$src_medium = !empty($src_medium[0]) ? $src_medium[0] : '';
		}

		if (!empty($module->settings->background_image_responsive)) {
			$size = !empty( $atts[ $key . '_size_responsive' ] ) ? $atts[ $key . '_size_responsive' ] : $atts[ $key . '_size' ];
			$src_responsive = wp_get_attachment_image_src(intval($module->settings->background_image_responsive), $size);
			$src_responsive = !empty($src_responsive[0]) ? $src_responsive[0] : '';
		}
		else if ( !empty( $atts['background_image_size_responsive'] ) ) {
			$src_responsive = wp_get_attachment_image_src($id, $atts['background_image_size_responsive'] );
			$src_responsive = !empty($src_responsive[0]) ? $src_responsive[0] : '';
		}

		if (isset($force_alt) && !empty($force_alt)) {
			$alt = $force_alt;
		} else {
			$alt = trim(strip_tags(get_post_meta($module->settings->background_image, '_wp_attachment_image_alt', true)));
		}

		ob_start();

		?>

		<div class="image-container">

			<?php if (!empty($src_medium) || !empty($src_responsive)) : ?>
				<picture>
					<?php if (!empty($src_responsive)) : ?>
						<source media="(max-width: 767px)" <?php echo (!empty($lazyload)) ? 'data-' : '' ?>srcset="<?php echo $src_responsive ?>">
					<?php endif; ?>
					<?php if (!empty($src_medium)) : ?>
						<source media="(max-width: 992px)" <?php echo (!empty($lazyload)) ? 'data-' : '' ?>srcset="<?php echo $src_medium ?>">
					<?php endif; ?>

					<img class='desk-img<?php echo (!empty($lazyload)) ? ' lazyload' : '' ?>' style='max-height: <?php echo $thumbnail_info['height'] ?>px' <?php echo (!empty($lazyload)) ? 'data-' : '' ?>src='<?php echo $src ?>' alt='<?php echo $alt ?>' />
				</picture>
			<?php else : ?>
				<img class='desk-img<?php echo (!empty($lazyload)) ? ' lazyload' : '' ?>' style='max-height: <?php echo $thumbnail_info['height'] ?>px' <?php echo (!empty($lazyload)) ?  'data-' : ''  ?>src='<?php echo $src ?>' alt='<?php echo $alt ?>' />
			<?php endif; ?>

		</div>

		<?php
		ob_end_flush();
		return ob_get_contents();
	}

	public static function fp_responsive_width_rule( $args = [] ) {
		$default_args      = array(
			'settings'          => null,
			'setting_name'      => '',
			'selector'          => '',
			'prop'              => '',
			'props'             => array(),
			'unit'              => '',
			'enabled'           => true,
			'container'         => '',
			'ignore'            => array(),
		);
		$args              = wp_parse_args( $args, $default_args );
		$settings          = $args['settings'];
		$setting_name      = $args['setting_name'];
		$selectors         = $args['selector'];
		$container         = $args['container'];
		$prop              = $args['prop'];
		$default_unit      = $args['unit'];
		$enabled           = $args['enabled'];
		$breakpoints       = array( '', 'medium', 'responsive' );

		$css = "\n\n";

		
		
		foreach($breakpoints as $breakpoint) {

			$suffix    = empty( $breakpoint ) ? '' : "_{$breakpoint}";
			$name      = $setting_name . $suffix;
			$setting   = isset( $settings->{$name} ) ? $settings->{$name} : null;
			$media     = \FLBuilderCSS::media_value( $breakpoint );
			$break_flex = false;

			if ( null === $setting ) {
				continue;
			}

			if ( ! empty( $media ) && ! empty( $selectors ) ) {
				$css .= "@media($media) {\n";
				$tab  = "\t";
			} else {
				$tab = '';
			}

			foreach ( $selectors as $index => $selector ) {
				$css .= "$tab$selector {\n";
				if ( intval( $setting ) === 100 ) {
					$css .= "\twidth: 100%;\n";
					$css .= "\tdisplay: block;\n";
					$break_flex = true;
				} else {
					if ($index === 0) {
						$css .= "\twidth: {$setting}%;\n";
					} else {
						$inverse = 100 - intval($setting);
						$css .= "\twidth: {$inverse}%;\n";
					}
					
				}
				$css .= "$tab}\n";
			}

			if ($break_flex && !empty($container)) {
				$css .= "{$tab}{$container}{";
				$css .= "\n{$tab}display: block;\n";
				$css .= "{$tab}}\n";
			}

			if ( ! empty( $media ) && ! empty( $selectors ) ) {
				$css .= "}\n";
			}
		}

		echo $css;
	}
}

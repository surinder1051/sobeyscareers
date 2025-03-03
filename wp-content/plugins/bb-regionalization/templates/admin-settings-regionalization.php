<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$settings = FP_BB_Module_Regionalization::get_bb_settings();

?>
<div id="fl-fp-bb-region-form" class="fl-settings-form">

	<h3 class="fl-settings-form-header"><?php _e( 'Regionalization Settings', 'fl-builder' ); ?></h3>

	<form id="fp-bb-region-form" action="<?php FLBuilderAdminSettings::render_form_action( FP_BB_Module_Regionalization::SETTINGS_KEY ); ?>" method="post">

		<div class="fl-settings-form-content">

			<div class="fl-fp-bb-region-settings">

                <h4><?php _e( 'Selection Settings', 'fl-builder' ); ?></h4>
                <p>
				    <label>
					    <input type="checkbox" name="fl-fp-bb-region-child-enabled" value="1" <?php checked( $settings['include_children'], 1 ); ?> />
                        <span><?php _e( 'Enable Child Pre-Selection', 'fl-builder' ); ?></span>
                        <p class="description"><?php _e( 'When selecting a top-level region, force include all child regions.', 'fl-builder' ); ?></p>
				    </label>
			    </p>
			</div>

		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Regionalization Settings', 'fl-builder' ); ?>" />
			<?php wp_nonce_field( FP_BB_Module_Regionalization::SETTINGS_KEY, 'fl-fp-bb-region-nonce' ); ?>
		</p>
	</form>
</div>

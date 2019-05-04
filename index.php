<?php

/**
 * Plugin Name: Design Experiments
 * Plugin URI: https://github.com/wordpress/design-experiments/
 * Description: WP-Admin design experiments from the WordPress.org Design Team
 * Version: 0.1
 * Author: The WordPress.org Design Team
 */


/**
 * Set up a WP-Admin page for managing turning on and off plugin features.
 */
function design_experiments_add_settings_page() {
	add_options_page('Design Experiments', 'Design Experiments', 'manage_options', 'design-experiments', 'design_experiments_settings_page');

	// Call register settings function
	add_action( 'admin_init', 'design_experiments_settings' );
}
add_action('admin_menu', 'design_experiments_add_settings_page');


/**
 * Register settings for the WP-Admin page.
 */
function design_experiments_settings() {
	register_setting( 'design-experiments-settings', 'default_stylesheet' );
}


/**
 * Build the WP-Admin settings page.
 */
function design_experiments_settings_page() { ?>
	<div class="wrap">
	<h1><?php _e('Design Experiments'); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'design-experiments-settings' ); ?>
		<?php do_settings_sections( 'design-experiments-settings' ); ?>

		<table class="form-table">
			<tr valign="top">
			<td>
				<label for="default_stylesheet">
					<input name="default_stylesheet" type="checkbox" value="1" <?php checked( '1', get_option( 'default_stylesheet' ) ); ?> />
					<?php _e('Default Plugin Stylesheet'); ?>
				</label>
			</td>
			</tr>
		</table>
	    
	    <?php submit_button(); ?>
	</form>
	</div>
<?php }


/**
 * Enqueue Stylesheets.
 */
function design_experiments_enqueue_stylesheets() {
	
	if ( get_option('default_stylesheet') == 1 ) {
		wp_register_style( 'design_experiments_css', plugins_url( 'style.css', __FILE__ ), false, '1.0.0' );
		wp_enqueue_style( 'design_experiments_css' );
	} else {
		return;
	}

}
add_action( 'admin_enqueue_scripts', 'design_experiments_enqueue_stylesheets' );
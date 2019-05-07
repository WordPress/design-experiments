<?php

/**
 * Plugin Name: Design Experiments
 * Plugin URI: https://github.com/wordpress/design-experiments/
 * Description: WP-Admin design experiments from the WordPress.org Design Team
 * Version: 0.1
 * Author: The WordPress.org Design Team
 */

class DesignExperiments {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'design_experiments_add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'design_experiments_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'design_experiments_enqueue_stylesheets' ) );
	}


	/**
	 * Register all the experiments.
	 */
	private $design_experiments = array(
		array( 'default_stylesheet', 'style.css', 'Default Plugin Stylesheet', 'https://github.com/WordPress/design-experiments' ),
		array( 'test_stylesheet', 'test.css', 'Test Stylesheet', 'https://github.com/WordPress/design-experiments' )
	);


	/**
	 * Set up a WP-Admin page for managing turning on and off plugin features.
	 */
	function design_experiments_add_settings_page() {
		add_options_page('Design Experiments', 'Design Experiments', 'manage_options', 'design-experiments', array( $this, 'design_experiments_settings_page' ) );
	}


	/**
	 * Register settings for the WP-Admin page.
	 */
	function design_experiments_settings() {
		$design_setting_args = array(
			'type' => 'string', 
			'default' => 'default_stylesheet',
		);
		register_setting( 'design-experiments-settings', 'design-experiments-setting', $design_setting_args );
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
					<?php foreach ( $this->design_experiments as $design_experiment ) { ?>
						<tr valign="top">
							<td>
								<label for="design-experiments-setting">
									<input name="design-experiments-setting" type="radio" value="<?php echo esc_attr( $design_experiment[0] ); ?>" <?php checked( $design_experiment[0], get_option( 'design-experiments-setting' ) ); ?> />
									<?php echo esc_html( $design_experiment[2] ); ?>
									<?php if ( $design_experiment[3] ) { ?>
										(<a href="<?php echo esc_url( $design_experiment[3] ); ?>"><?php _e( 'Learn more' ); ?></a>)
									<?php } ?>
								</label>
							</td>
						</tr>
					<?php } ?>
				</table>

			<?php submit_button(); ?>
		</form>
		</div>
	<?php }


	/**
	 * Enqueue Stylesheets.
	 */
	function design_experiments_enqueue_stylesheets() {

		foreach ( $this->design_experiments as $design_experiment ) {

			if ( get_option( 'design-experiments-setting' ) == $design_experiment[0] ) {
				wp_register_style( $design_experiment[0], plugins_url( 'css/' . $design_experiment[1], __FILE__ ), false, '1.0.0' );
				wp_enqueue_style( $design_experiment[0] );
			}

		}

	}

}

new DesignExperiments;
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
		
		// Generate a list of all CSS files
		$this->design_experiment_css_files = glob( plugin_dir_path( __FILE__ ) . 'css/*.css' );

		// Add admin actions
		add_action( 'admin_menu', array( $this, 'design_experiments_add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'design_experiments_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'design_experiments_enqueue_stylesheets' ) );
	}


	/**
	 * Define a list of all CSS files
	 */
	private $design_experiment_css_files;


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
			'default' => 'default',
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
					<?php foreach ( $this->design_experiment_css_files as $css_file ) {
						$experiment_name = basename( $css_file, '.css' ); 
						$experiment_title = ucfirst( str_replace( '-', ' ', $experiment_name ) ); ?>
						<tr valign="top">
							<td>
								<label for="design-experiments-setting">
									<input name="design-experiments-setting" type="radio" value="<?php echo esc_attr( $experiment_name ); ?>" <?php checked( $experiment_name, get_option( 'design-experiments-setting' ) ); ?> />
									<?php echo esc_html( $experiment_title ); ?>
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

		foreach ( $this->design_experiment_css_files as $css_file ) {
			$experiment_name = basename( $css_file, '.css' );
			$experiment_url = plugins_url( 'css/' . basename( $css_file ), __FILE__ );

			if ( get_option( 'design-experiments-setting' ) == $experiment_name ) {
				wp_register_style( $experiment_name , $experiment_url, false, '1.0.0' );
				wp_enqueue_style( $experiment_name );
			}
		}

	}

}

new DesignExperiments;
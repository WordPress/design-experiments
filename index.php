<?php

/**
 * Plugin Name: Design Experiments
 * Plugin URI: https://github.com/wordpress/design-experiments/
 * Description: WP-Admin design experiments from the WordPress.org Design Team
 * Version: 1.3
 * Author: The WordPress.org Design Team
 * Text Domain: design-experiments
 */

class DesignExperiments {

	/**
	 * List of all CSS files
	 */
	private $design_experiment_css_files;

	private $meta_data = array();

	function __construct() {

		// Generate a list of all CSS files
		$this->design_experiment_css_files = glob( plugin_dir_path( __FILE__ ) . 'css/*.css' );

		$this->get_design_experiment_meta_data();

		// Add admin actions
		add_action( 'admin_menu', array( $this, 'design_experiments_add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'design_experiments_settings' ) );
		add_action( 'admin_notices', array( $this, 'design_experiments_admin_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'design_experiments_enqueue_stylesheets' ), 100 );

		// Filters
		add_filter( 'plugin_action_links_design-experiments/index.php', array( $this, 'design_experiments_add_settings_link' ) );
	}

	/**
	* Gets the meta data from each design experiment.
	* return array $meta_data each experiment with its meta data.
	*/
	private function get_design_experiment_meta_data() {
		$file_headers = array(
			'title'   		=> 'Title',
			'description' 	=> 'Description',
			'pr'     		=> 'PR',
		);
		
		foreach ( $this->design_experiment_css_files as $file ) {
			$name = basename( $file, '.css' );
			$this->meta_data[ $name ] = get_file_data( $file, $file_headers );
		}
	}

	/**
	 * Set up a WP-Admin page for managing turning on and off plugin features.
	 */
	function design_experiments_add_settings_page() {
		add_options_page( 'Design Experiments', 'Design Experiments', 'manage_options', 'design-experiments', array( $this, 'design_experiments_settings_page' ) );
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
	 * Fetch experiment title from the CSS file.
	 */
	private function get_title( $experiment_name ) {

		if ( array_key_exists( $experiment_name, $this->meta_data ) && ! empty( $this->meta_data[ $experiment_name ]['title'] ) ) {
			$title = $this->meta_data[ $experiment_name ]['title'];
		} else {
			$title = ucfirst( str_replace( '-', ' ', $experiment_name ) );
		}

		return esc_html( $title );
	}


	/**
	 * Fetch experiment metadata from the CSS file.
	 */
	private function output_meta_data ( $experiment_name ) {

		if ( ! array_key_exists( $experiment_name, $this->meta_data ) ) {
			return false;
		}

		$experiment_meta = $this->meta_data[ $experiment_name ];

		if ( ! empty( $experiment_meta['description'] ) ) {
			?>
			<p><?php echo esc_html( $experiment_meta['description'] ); ?></p>
			<?php
		}

		if ( ! empty( $experiment_meta['pr'] ) ) {
			?>
			<p>
				<a href="<?php echo esc_url( $experiment_meta['pr'] ); ?>"><?php _e( 'Details', 'design-experiments' ); ?></a>
			</p>
			<?php
		}
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

			<table class="form-table" style="width: auto;">
			<?php

			foreach ( $this->design_experiment_css_files as $key => $css_file ) {
				$experiment_name = basename( $css_file, '.css' );
				$experiment_title = $this->get_title( $experiment_name );
				$id = esc_attr( "design-experiments-setting-{$key}" );

				?>
				<tr>
					<td style="vertical-align: top;">
						<input
							type="radio"
							id="<?php echo $id; ?>"
							name="design-experiments-setting"
							value="<?php echo esc_attr( $experiment_name ); ?>"
							<?php checked( $experiment_name, get_option( 'design-experiments-setting' ) ); ?>
						/>
					</td>
					<td>
						<label for="<?php echo $id; ?>" style="font-weight: bold">
							<?php echo esc_html( $experiment_title ); ?>
						</label>
						<?php $this->output_meta_data( $experiment_name ); ?>
					</td>
				</tr>
				<?php
			}

			?>
			</table>

			<?php submit_button(); ?>
		</form>
		</div>
	<?php }


	/**
	 * Enqueue Stylesheets.
	 */
	function design_experiments_enqueue_stylesheets() {

		$option = get_option( 'design-experiments-setting' );

		foreach ( $this->design_experiment_css_files as $css_file ) {
			$experiment_name = basename( $css_file, '.css' );

			if ( $option === $experiment_name ) {
				$experiment_url = plugins_url( 'css/' . basename( $css_file ), __FILE__ );

				// Auto-bust stylesheet cache.
				$mtime = @filemtime( $css_file );
				$version = $mtime ? $mtime : time();

				wp_register_style( $experiment_name , $experiment_url, false, $version );
				wp_enqueue_style( $experiment_name );
				break;
			}
		}

		wp_deregister_style( 'admin-menu' );
		wp_register_style( 'admin-menu', plugins_url( 'css/admin-menu.css', __FILE__ ) );
		wp_register_script( 'wp-admin-menu', plugins_url( 'js/admin-menu.js', __FILE__ ), [ 'jquery' ] );
		wp_enqueue_script( 'wp-admin-menu' );
	}

	/**
	 * Display a warning on the plugin page.
	 */
	function design_experiments_admin_notice() {
		$screen = get_current_screen();

		if ( $screen->id === 'settings_page_design-experiments' ) { 
			?>
			<div class="notice notice-warning">
				<p>
					<strong><?php _e( 'Warning:', 'design-experiments' ); ?> </strong>
					<?php _e( 'These experiments may hide or visually break functionality in the admin area. This plugin is for testing concepts, and is not intended for use on a production site.', 'design-experiments' ) ?>
				</p>
			</div>
			<?php 
		}
	}

	/**
	 * Include a link to the plugin settings on the main plugins page.
	 */
	function design_experiments_add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=design-experiments">' . __( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

}

new DesignExperiments;

<?php

/**
 * Plugin Name: Design Experiments
 * Plugin URI: https://github.com/wordpress/design-experiments/
 * Description: WP-Admin design experiments from the WordPress.org Design Team
 * Version: 0.1
 * Author: The WordPress.org Design Team
 */

/**
 * Enqueue Stylesheet
 */
function design_experiments_enqueue_stylesheet() {
	wp_register_style( 'design_experiments_css', plugins_url( 'style.css', __FILE__ ), false, '1.0.0' );
	wp_enqueue_style( 'design_experiments_css' );
}
add_action( 'admin_enqueue_scripts', 'design_experiments_enqueue_stylesheet' );
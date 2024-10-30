<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.icoconsulting.asia
 * @since             1.0.2
 * @package           Ico_Progress_Viewer
 *
 * @wordpress-plugin
 * Plugin Name:       ICO Progress Viewer
 * Plugin URI:        https://www.icoconsulting.asia/?utm_source=wordpress&utm_medium=plugin_uri
 * Description:       Display a funds raised progress bar and other stats for your Initial Coin Offering (ICO).
 * Version:           1.0.2
 * Author:            ICO Consulting Asia
 * Author URI:        https://www.icoconsulting.asia/?utm_source=wordpress&utm_medium=author_uri
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ico-progress-viewer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ico-progress-viewer-activator.php
 */
function activate_ico_progress_viewer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ico-progress-viewer-activator.php';
	Ico_Progress_Viewer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ico-progress-viewer-deactivator.php
 */
function deactivate_ico_progress_viewer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ico-progress-viewer-deactivator.php';
	Ico_Progress_Viewer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ico_progress_viewer' );
register_deactivation_hook( __FILE__, 'deactivate_ico_progress_viewer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ico-progress-viewer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ico_progress_viewer() {

	$plugin = new Ico_Progress_Viewer();
	$plugin->run();

}
run_ico_progress_viewer();

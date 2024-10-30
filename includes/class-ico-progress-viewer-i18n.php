<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.icoconsulting.asia
 * @since      1.0.0
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/includes
 * @author     ICO Consulting <sales@icoconsulting.asia>
 */
class Ico_Progress_Viewer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ico-progress-viewer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

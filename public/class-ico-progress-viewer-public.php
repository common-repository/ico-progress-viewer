<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.icoconsulting.asia
 * @since      1.0.0
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/public
 * @author     ICO Consulting <sales@icoconsulting.asia>
 */
class Ico_Progress_Viewer_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->ico_progress_viewer_options = get_option($this->plugin_name);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ico_Progress_Viewer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ico_Progress_Viewer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ico-progress-viewer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ico_Progress_Viewer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ico_Progress_Viewer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'-moment', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', null, null, false );
		wp_enqueue_script( $this->plugin_name.'-web3', plugin_dir_url( __FILE__ ) . 'js/web3.min.js', null, null, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ico-progress-viewer-public.js', array( 'jquery' ), $this->version, false );
		//wp_enqueue_script( $this->plugin_name."-web3", "https://cdn.jsdelivr.net/npm/web3@1.0.0-beta.24/src/index.min.js", null, null, false );
	}

	public function get_widget() {
		//include_once( 'partials/ico-progress-viewer-public-display.php' );
		// require dirname( __FILE__ ) ) . 'public/partials/ico-progress-viewer-public-display.php';
		//return sprintf("HERE %s\n", print_r($this->ico_progress_viewer_options, true));

		// Read the settings
		$widget_data_attributes = array(

			// General settings
			'date_format' => $this->ico_progress_viewer_options['date_format'],

			// Smart contract basic details
			'smart_contract_address' => $this->ico_progress_viewer_options['smart_contract_address'],
			'gateway_url' => $this->ico_progress_viewer_options['gateway_url'],
			'abi' => $this->ico_progress_viewer_options['abi'],

			// Advanced contract method name mappings
			'total_raised' => $this->ico_progress_viewer_options['total_raised'],
			'start_time' => $this->ico_progress_viewer_options['start_time'],
			'end_time' => $this->ico_progress_viewer_options['end_time'],
			'min_cap' => $this->ico_progress_viewer_options['min_cap'],
			'max_cap' => $this->ico_progress_viewer_options['max_cap'],
		);

		// Map the html attributes to prepare for injection
		$widget_data_str = implode(' ', array_map(
			function ($k, $v) { return "data-". preg_replace('/_/', "-", $k) .'="'. htmlspecialchars($v) .'"'; },
			array_keys($widget_data_attributes), $widget_data_attributes
		));

		// Read the html file
		$widget_contents = file_get_contents( dirname( __FILE__ ) . '/html/widget.html' );

		// Inject the data and return
		return str_replace("%%WIDGET_DATA%%", $widget_data_str, $widget_contents);
	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.icoconsulting.asia
 * @since      1.0.0
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ico_Progress_Viewer
 * @subpackage Ico_Progress_Viewer/admin
 * @author     ICO Consulting <sales@icoconsulting.asia>
 */
class Ico_Progress_Viewer_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ico-progress-viewer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ico-progress-viewer-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_plugin_admin_menu() {
		/*
			* Add a settings page for this plugin to the Settings menu.
			*
			* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
			*
			*        Administration Menus: http://codex.wordpress.org/Administration_Menus
			*
			*/
		add_options_page( 'ICO Progress Viewer Setup', 'ICO Viewer', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
		include_once( 'partials/ico-progress-viewer-admin-display.php' );
	}

	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	public function validate($input) {
		$valid = array();

		if (!$this->isAddress($input['smart_contract_address'])) {
			add_settings_error('smart_contract_address_validity', 'smart_contract_address_invalid', "Smart Contract Address invalid");
		} else {
			$valid['smart_contract_address'] = sanitize_text_field(trim($input['smart_contract_address']));
		}

		$valid['gateway_url'] = esc_url(trim($input['gateway_url']));
		$valid['abi'] = (trim($input['abi']));
		$valid['shortcode'] = (trim($input['shortcode']));
		$valid['date_format'] = (trim($input['date_format']));

		// contract method name mappings
		$valid['total_raised'] = (trim($input['total_raised']));
		$valid['start_time'] = (trim($input['start_time']));
		$valid['end_time'] = (trim($input['end_time']));
		$valid['min_cap'] = (trim($input['min_cap']));
		$valid['max_cap'] = (trim($input['max_cap']));

		return $valid;
	 }

	/**
	 * Checks if the given string is an address
	 *
	 * @method isAddress
	 * @param {String} $address the given HEX adress
	 * @return {Boolean}
	*/
	private function isAddress($address) {
		if (!preg_match('/^(0x)?[0-9a-f]{40}$/i',$address)) {
			// check if it has the basic requirements of an address
			return false;
		} elseif (!preg_match('/^(0x)?[0-9a-f]{40}$/',$address) || preg_match('/^(0x)?[0-9A-F]{40}$/',$address)) {
			// If it's all small caps or all all caps, return true
			return true;
		} else {
			// Otherwise check each case
			return $this->isChecksumAddress($address);
		}
	}

	/**
	 * Checks if the given string is a checksummed address
	 *
	 * @method isChecksumAddress
	 * @param {String} $address the given HEX adress
	 * @return {Boolean}
	*/
	private function isChecksumAddress($address) {
		// Check each case
		$address = str_replace('0x','',$address);
		$addressHash = hash('sha3',strtolower($address));
		$addressArray=str_split($address);
		$addressHashArray=str_split($addressHash);

		for($i = 0; $i < 40; $i++ ) {
			// the nth letter should be uppercase if the nth digit of casemap is 1
			if ((intval($addressHashArray[$i], 16) > 7 && strtoupper($addressArray[$i]) !== $addressArray[$i]) || (intval($addressHashArray[$i], 16) <= 7 && strtolower($addressArray[$i]) !== $addressArray[$i])) {
				return false;
			}
		}
		return true;
	}



}

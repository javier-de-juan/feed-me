<?php

/**
 * The plugin settings registration
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 */

namespace FeedMe\admin\settings;

/**
 * The plugin settings registration
 *
 * Defines the plugin name, registers the menu option and show the settings view
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class SettingsViewController {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The ID of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * Adds the action for add the menu option.
	 *
	 * @since    1.0.0
	 */
	public function load(): void {
		add_action( 'admin_menu', array( $this, 'show_menu' ) );
	}

	/**
	 * Adds the plugin page to the setting's menu and sets the method to load the view.
	 *
	 * @since    1.0.0
	 */
	public function show_menu(): void {
		add_options_page( $this->plugin_name . ' Settings', ucfirst( $this->plugin_name ), 'manage_options', $this->plugin_name, array( &$this, 'setting_page' ) );
	}

	/**
	 * Loads the plugin setting's view.
	 *
	 * @since    1.0.0
	 */
	public function setting_page(): void {}
}
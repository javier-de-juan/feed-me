<?php

/**
 * The plugin settings registration
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
 */

namespace FeedMe\admin\settings;

/**
 * The plugin settings registration
 *
 * Defines the plugin name, settings, loads the view
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class SettingsController {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The prefix used for meta values.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $setting_prefix The prefix used for meta values.
	 */
	private $setting_prefix;

	/**
	 * The settings name.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_name The settings name.
	 */
	private $settings_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The ID of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name    = $plugin_name;
		$this->setting_prefix = $plugin_name . '-';
		$this->settings_name  = $this->setting_prefix . 'settings';
	}

	/**
	 * Loads the view and adds action for initialize the plugin settings.
	 *
	 * @since    1.0.0
	 *
	 */
	public function init(): void {
		$viewController = new SettingsViewController( $this->plugin_name );
		$viewController->load();

		add_action( 'admin_init', [ &$this, 'initialize_settings' ] );
	}

	/**
	 * Registers all settings with its prefix for being able to save.
	 *
	 * @since    1.0.0
	 *
	 */
	public function initialize_settings(): void {
		$settings = $this->get_settings();

		foreach ( $settings as $name => $config ) {
			register_setting( $this->settings_name, $this->setting_prefix . $name, $config );
		}
	}

	/**
	 * Returns all settings needed by the plugin.
	 *
	 * @since    1.0.0
	 * @return  array Array with all needed settings.
	 */
	private function get_settings(): array {
		return array(
			'key'   => array(
				'type'     => 'string',
				'sanitize' => 'sanitize_text_field',
				'default'  => '',
			),
			'token' => array(
				'type'     => 'string',
				'sanitize' => 'sanitize_text_field',
				'default'  => '',
			),
			'store' => array(
				'type'     => 'string',
				'sanitize' => 'sanitize_text_field',
				'default'  => '',
			)
		);
	}

	/**
	 * Returns the option value.
	 *
	 * @param string $option_name Option name to get.
	 * @param string $default Default value if there's no value.
	 *
	 * @return mixed|void
	 */
	public function get( string $option_name, $default = '' ) {
		return get_option( $this->setting_prefix . $option_name, $default );
	}
}

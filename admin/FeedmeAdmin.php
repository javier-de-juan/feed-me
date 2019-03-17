<?php

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
 */

namespace FeedMe\admin;

use FeedMe\admin\settings\SettingsController;
use FeedMe\admin\widget\WidgetController;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class FeedmeAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->add_feedme_settings();
		$this->add_feedme_widget();
	}

	/**
	 * Add the plugin settings to configure Trello info.
	 *
	 * @since    1.0.0
	 *
	 */
	private function add_feedme_settings(): void {
		$settings_page = new SettingsController( $this->plugin_name );
		$settings_page->init();
	}

	/**
	 * Add the plugin settings to configure Trello info.
	 *
	 * @since    1.0.0
	 *
	 */
	private function add_feedme_widget(): void {
		$widget_page = new WidgetController( $this->plugin_name );
		$widget_page->init();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/feed-me-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/feed-me-admin.js', array( 'jquery' ), $this->version, false );
	}

}

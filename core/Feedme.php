<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that core attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/core
 */

namespace FeedMe\core;

use FeedMe\settings\SettingsController;
use FeedMe\widget\WidgetController;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Feed_me
 * @subpackage Feed_me/core
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class Feedme {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	public const PLUGIN_NAME = 'feed-me';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Feed_me_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->add_feedme_settings();
		$this->add_feedme_widget();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Feed_me_Loader. Orchestrates the hooks of the plugin.
	 * - Feed_me_i18n. Defines internationalization functionality.
	 * - Feed_me_Admin. Defines all hooks for the admin area.
	 * - Feed_me_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies(): void {
		$this->loader = new FeedmeLoader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Feed_me_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale(): void {
		$plugin_i18n = new FeedmeI18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks(): void {
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run(): void {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    FeedmeLoader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): FeedmeLoader {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * Add the plugin settings to configure Trello info.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	private function add_feedme_settings(): void {
		$settings_page = new SettingsController();
		$settings_page->init();
	}

	/**
	 * Add the plugin settings to configure Trello info.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	private function add_feedme_widget(): void {
		$widget_page = new WidgetController();
		$widget_page->init();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style( Feedme::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . '../assets/css/feed-me-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script( Feedme::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . '../assets/js/feed-me-admin.js', array( 'jquery' ), $this->version, false );
	}

}

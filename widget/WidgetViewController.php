<?php

/**
 * The plugin widget controller
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 */

namespace FeedMe\widget;

use FeedMe\core\Feedme;
use FeedMe\core\views\ViewParser;

/**
 * The plugin widget controller
 *
 * Loads the assets and print the view.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class WidgetViewController {

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
	 */
	public function __construct() {
		$this->plugin_name = Feedme::PLUGIN_NAME;
	}

	/**
	 * Loads the needed assets and code for the view.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function load(): void {
		$this->add_assets();
		$this->add_code();
	}

	/**
	 * Adds the assets in the WP admin and WP frontend.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_assets(): void {
		add_action( 'admin_enqueue_scripts', [ &$this, 'add_css' ] );
		add_action( 'wp_enqueue_scripts', [ &$this, 'add_css' ] );
		add_action( 'admin_enqueue_scripts', [ &$this, 'add_js' ] );
		add_action( 'wp_enqueue_scripts', [ &$this, 'add_js' ] );
	}

	/**
	 * Enqueue the css needed for the view.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_css(): void {
		wp_enqueue_style( $this->plugin_name );
	}

	/**
	 * Enqueue the js needed for the view.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_js(): void {
		wp_enqueue_script( $this->plugin_name );
	}

	/**
	 * Adds the view code in the WP admin and WP frontend footer.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_code(): void {
		add_action( 'admin_footer', [ &$this, 'add_html' ] );
		add_action( 'wp_footer', [ &$this, 'add_html' ] );
	}

	/**
	 * Prints the view code.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_html() {
		$view_parser = new ViewParser( new View() );
		echo $view_parser->parse();
	}
}

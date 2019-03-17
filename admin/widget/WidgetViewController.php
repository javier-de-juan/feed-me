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

namespace FeedMe\admin\widget;

use FeedMe\core\views\ViewParser;

/**
 * The plugin settings registration
 *
 * Defines the plugin name, registers the menu option and show the settings view
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class WidgetViewController {

	private $plugin_name;
	
	public function __construct(string $plugin_name) {
		$this->plugin_name = $plugin_name;
	}

	public function load() {
		$this->add_assets();
		$this->add_code();
	}

	public function add_assets() {
		add_action('admin_enqueue_scripts', [&$this, 'add_css']);
		add_action('wp_enqueue_scripts', [&$this, 'add_css']);
		add_action('admin_enqueue_scripts', [&$this, 'add_js']);
		add_action('wp_enqueue_scripts', [&$this, 'add_js']);
	}

	public function add_css() {
		wp_enqueue_style($this->plugin_name);
		wp_enqueue_style( 'select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css' );
	}

	public function add_js() {
		wp_enqueue_script($this->plugin_name);
		wp_enqueue_script( 'select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js', array( 'jquery' ), '', true );
	}

	public function add_code() {
		add_action('admin_footer', [&$this, 'add_html']);
		add_action('wp_footer', [&$this, 'add_html']);
	}

	public function add_html() {
		$view_parser = new ViewParser( $this->plugin_name, new View( $this->plugin_name ) );
		echo $view_parser->parse();
	}
}

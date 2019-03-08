<?php
/**
 * The plugin settings View logic
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 */

namespace FeedMe\admin\settings;

use FeedMe\core\views\ViewInterface;

/**
 * The plugin settings View logic
 *
 * Defines the plugin name and the methods used by the parser to populate the view template.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class View implements ViewInterface {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The settings controller.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings The settings controller used to get settings.
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The ID of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->settings    = new SettingsController( $this->plugin_name );
	}

	/**
	 * Returns the name of the view.
	 *
	 * @since    1.0.0
	 *
	 * @return string The name of the view.
	 */
	public function get_view_name(): string {
		return 'settings';
	}

	/**
	 * Returns the page title of the view.
	 *
	 * @since    1.0.0
	 *
	 * @return string The title of the view.
	 */
	public function get_title(): string {
		return __( sprintf( '%s Settings', ucwords( $this->plugin_name, '- ' ) ) );
	}

	/**
	 * Returns a little tip for get the Trello API Key and API Token.
	 *
	 * @since    1.0.0
	 *
	 * @return string The message with the link.
	 */
	public function get_trello_help(): string {
		$link = '<a href="https://trello.com/1/appKey/generate" target="_blank">https://trello.com/1/appKey/generate</a>';

		return __( sprintf( '<i>You can get your API Key and API Token here:</i> %s', $link ), $this->plugin_name );
	}

	/**
	 * Returns the Trello Key saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Key.
	 */
	public function get_key(): string {
		return $this->settings->get( 'key' );
	}

	/**
	 * Returns the Trello Token saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Token.
	 */
	public function get_token(): string {
		return $this->settings->get( 'token' );
	}
}

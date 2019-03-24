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

namespace FeedMe\settings;

use FeedMe\core\Feedme;
use FeedMe\core\views\ViewInterface;
use FeedMe\services\TrelloService;

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct() {
		$this->plugin_name = Feedme::PLUGIN_NAME;
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
		$link
			= '<a href="https://trello.com/1/appKey/generate" target="_blank">https://trello.com/1/appKey/generate</a>';

		return __( sprintf( '<i>You can get your API Key and API Token here:</i> %s', $link ), $this->plugin_name );
	}

	/**
	 * Returns the Trello Key saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Key.
	 */
	public function get_trello_api_key(): string {
		return SettingsController::get( 'trello-api-key' );
	}

	/**
	 * Returns the Trello Token saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Token.
	 */
	public function get_trello_api_token(): string {
		return SettingsController::get( 'trello-api-token' );
	}

	/**
	 * Returns all Trello boards related with API key and API token given.
	 *
	 * @since    1.0.0
	 *
	 * @return array Array with board name and board id.
	 */
	public function get_trello_boards(): array {
		$trello = new TrelloService( $this->get_trello_api_key(), $this->get_trello_api_token() );

		return $trello->get_boards();
	}

	/**
	 * Returns all the html with the inputs generated by WordPress for save the plugin settings.
	 *
	 * @since    1.0.0
	 *
	 * @return string Html inputs.
	 */
	public function get_wordpress_registered_hidden_fields(): string {
		ob_start();
		$settings = new SettingsController();
		settings_fields( $settings->get_settings_name() );
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	/**
	 * Returns if the Trello board selector must be shown or not.
	 *
	 * @return string 'none' if must trello info was not populated 1 in other wise.
	 */
	public function get_visibility_board_selector() {
		return ( $this->get_trello_api_key() && $this->get_trello_api_token() ) ?: 'none';
	}
}

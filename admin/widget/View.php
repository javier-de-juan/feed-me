<?php

namespace FeedMe\admin\widget;

use FeedMe\admin\settings\SettingsController;
use FeedMe\core\views\ViewInterface;
use FeedMe\services\TrelloService;

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
		return 'widget';
	}

	public function get_title():string {
		return __('Feed Me');
	}

	public function get_summary():string {
		return __('Please, introduce the information the most detailed way that you can', PLUGIN_NAME);
	}

	/**
	 * Returns the Trello Key saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Key.
	 */
	public function get_trello_api_key(): string {
		return $this->settings->get( 'trello-api-key' );
	}

	/**
	 * Returns the Trello Token saved in DB.
	 *
	 * @since    1.0.0
	 *
	 * @return string The Trello Token.
	 */
	public function get_trello_api_token(): string {
		return $this->settings->get( 'trello-api-token' );
	}

	public function get_columns(): array {
		$current_store = $this->settings->get('trello-board');

		$trello = new TrelloService( $this->get_trello_api_key(), $this->get_trello_api_token() );
		$columns =  $trello->get_columns($current_store);

		return array_filter($columns);
	}
}
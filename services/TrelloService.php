<?php
//TODO: make this class singleton

/**
 * The trello service to work with the API
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/services
 */

namespace FeedMe\services;

use Trello\Trello;

/**
 * The trello service to work with the API
 *
 * Defines the api, get boards, columns insert rows and attachments.
 *
 * @package    Feed_me
 * @subpackage Feed_me/services
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class TrelloService {

	/**
	 * The trello Api service.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $api The trello API.
	 */
	private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $key   Trello API key.
	 * @param      string $token Trello API token.
	 */
	public function __construct( string $key, string $token ) {
		try {
			$this->api = new Trello( $key, null, $token );
		} catch ( \Exception $exception ) {
			wp_die( $exception->getMessage() );
		}
	}

	/**
	 * Returns the trello boards for account.
	 *
	 * @since    1.0.0
	 * @return array Array with all boards.
	 */
	public function get_boards(): array {
		static $boards = array();

		if ( ! empty( $boards ) ) {
			return $boards;
		}

		$trello_boards = $this->api->members->get( 'my/boards/' ) ?: array();

		foreach ( $trello_boards as $board ) {
			$boards[ $board->id ] = $board->name;
		}

		return $boards;
	}

	/**
	 * Returns the board columns.
	 *
	 * @param string $board_id Trello board ID
	 *
	 * @since    1.0.0
	 * @return array Array with all boards.
	 */
	public function get_columns( string $board_id ): array {
		static $columns = [];

		if ( ! empty( $columns ) ) {
			return $columns;
		}

		$board_id = sanitize_text_field( $board_id );

		if ( empty( $board_id ) || empty( $this->get_boards() ) ) {
			return $columns;
		}

		$_columns = $this->api->get( "boards/{$board_id}/lists" ) ?: array();

		foreach ( $_columns as $column ) {
			if ( '_' !== substr( $column->name, 0, 1 ) ) {
				$key             = $column->id;
				$name            = $column->name;
				$columns[ $key ] = $name;
			}
		}


		return $columns;
	}

	/**
	 * Adds a card in trello.
	 *
	 * @param string $store The trello board.
	 * @param string $list  The trello column.
	 * @param array  $data  The card data.
	 * @param array  $file  The card attachment.
	 *
	 * @since    1.0.0
	 */
	public function add_card( string $store, string $list, array $data, array $file ): void {
		$data['idList']  = $list;
		$data['idBoard'] = $store;
		$data['closed']  = "false";

		$result = $this->api->post( 'cards', $data );

		if ( ! empty( $file ) && $result->id ) {
			$this->add_attachment( $result->id, $file );
		}
	}

	/**
	 * Adds an attachment to the trello card.
	 *
	 * @param string $id   Trello card ID.
	 * @param array  $file Attachment.
	 */
	private function add_attachment( string $id, array $file ): void {
		$this->api->post( "cards/{$id}/attachments", $file );
	}
}

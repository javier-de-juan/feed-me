<?php

namespace FeedMe\services;

use Trello\Trello;

class TrelloService {

	private $api;

	public function __construct( string $key, string $token ) {
		try {
			$this->api = new Trello( $key, null, $token );
		} catch ( \Exception $exception ) {
			wp_die( $exception->getMessage() );
		}
	}

	public function get_boards() {
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
}
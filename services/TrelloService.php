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

	public  function get_columns($from_store) {
		static $columns = null;

		if(isset($columns)) {
			return $columns;
		}

		$from_store = sanitize_text_field($from_store);

		$columns = [];
		if(empty($from_store) || empty($this->get_boards())) {
			return $columns;
		}

		$_columns =   $this->api->get("boards/{$from_store}/lists");


		if(!empty($_columns)) {

			foreach($_columns as $column) {
				if ( substr($column->name, 0, 1) !== '_' ) {
					$key                 = $column->id;
					$name                = $column->name;
					$columns[ $key ] = $name;
				}
			}
		}


		return $columns;
	}
}
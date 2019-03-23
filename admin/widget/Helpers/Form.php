<?php

/**
 * The form helper
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 */

namespace FeedMe\admin\widget\Helpers;

use FeedMe\admin\settings\SettingsController;
use FeedMe\services\TrelloService;

/**
 * The plugin widget controller
 *
 * Sets the form data and sends it to Trello service.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget/helpers
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class Form {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The data which will be sent to trello.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $data All trello data.
	 */
	private $data = [];

	/**
	 * The attachment sent to trello.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $attachment Attachment info.
	 */
	private $attachment = array();

	/**
	 * The current user logged.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Wp_User $current_user User info.
	 */
	private $current_user;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The ID of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name  = $plugin_name;
		$this->current_user = wp_get_current_user();
	}

	/**
	 * Sets the title
	 *
	 * @since    1.0.0
	 *
	 * @param string $title The card title.
	 *
	 * @return void
	 */
	public function set_title( string $title ): void {
		$this->data['title'] = sanitize_text_field( $title );
	}

	/**
	 * Sets the feedback type
	 *
	 * @since    1.0.0
	 *
	 * @param string $type The feedback type.
	 *
	 * @return void
	 */
	public function set_type( string $type ): void {
		$this->data['type'] = sanitize_text_field( $type );
	}

	/**
	 * Sets the description
	 *
	 * @since    1.0.0
	 *
	 * @param string $description The card description.
	 *
	 * @return void
	 */
	public function set_description( string $description ): void {
		$this->data['description'] = sanitize_textarea_field( $description );
	}

	/**
	 * Sets the URL
	 *
	 * @since    1.0.0
	 *
	 * @param string $url The url where the feedme was wrote.
	 *
	 * @return void
	 */
	public function set_url( string $url ): void {
		$this->data['url'] = esc_url_raw( base64_decode( $url ) );
	}

	/**
	 * Sets the environment
	 *
	 * @since    1.0.0
	 *
	 * @param string $url The url where the feedme was wrote.
	 *
	 * @return void
	 */
	public function set_environment( string $environment ): void {
		$this->data['environment'] = str_replace( '*', "\n\r*", sanitize_textarea_field( $environment ) );
	}

	/**
	 * Sets the JS log
	 *
	 * @since    1.0.0
	 *
	 * @param string $jsbacktrace The js back trace.
	 *
	 * @return void
	 */
	public function set_jsBacktrace( string $jsbacktrace ): void {
		$this->data['jsbacktrace'] = $jsbacktrace;
	}

	/**
	 * Sets the attachment
	 *
	 * @since    1.0.0
	 *
	 * @param array $attachment The attachment info.
	 *
	 * @return void
	 */
	public function set_attachment( array $attachment ): void {
		if ( $attachment['size'] > 0 ) {
			$this->attachment['name'] = $attachment['name'];
			$this->attachment['type'] = $attachment['type'];
			$this->attachment['file'] = new \CURLFile( $attachment['tmp_name'], $attachment['type'],
				$attachment['name'] );
		}
	}

	/**
	 * Imports all the form data and sets the info before send it.
	 *
	 * @since    1.0.0
	 *
	 * @param array $data The form data.
	 *
	 * @throws \Exception When an element receive doesn't have a method.
	 * @return void
	 */
	public function import( array $data ): void {
		foreach ( $data as $element => $value ) {
			$element = sanitize_text_field( $element );

			$method = "set_{$element}";
			if ( method_exists( $this, $method ) ) {
				$this->$method( $value );
			} else {
				$this->error( "I don't know data '{$element}'" );
			}
		}
	}

	/**
	 * Throws an exception with an error message.
	 *
	 * @since    1.0.0
	 *
	 * @param string $msg The exception message.
	 *
	 * @throws \Exception
	 * @return void
	 */
	protected function error( string $msg ): void {
		error_log( $msg );
		throw new \Exception( $msg );
	}

	/**
	 * Sends all form data to Trello.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function send_to_trello(): void {
		$settings = new SettingsController( $this->plugin_name );
		$trello   = new TrelloService( $settings->get( 'trello-api-key' ), $settings->get( 'trello-api-token' ) );
		$store    = $settings->get( 'store' );

		$trello->add_card( $store, $this->data['type'], $this->get_row(), $this->attachment );
	}


	/**
	 * Returns the data for the trello card.
	 *
	 * @since    1.0.0
	 *
	 * @return array An array with card name, description, position and source url (trello api fields)
	 */
	protected function get_row(): array {
		$row['name'] = $this->data['title'];
		$row['desc'] = $this->get_description();

		$row['pos']       = 'top';
		$row['urlSource'] = $this->data['url'];

		return $row;
	}

	/**
	 * Concatenate and returns the description with more data than sent in form.
	 *
	 * @since    1.0.0
	 *
	 * @return string Description with URL, current user, environment, js backtrace
	 */
	protected function get_description(): string {
		$desc = "**Usuario**: {$this->current_user->user_login} <{$this->current_user->user_email}\>";
		$desc .= "\n**Url**: " . $this->data['url'];
		$desc .= "\n**Dispositivo**: {$_SERVER['HTTP_USER_AGENT']}";
		$desc .= "\n\n**Descripción**:\n{$this->data['description']}";
		$desc .= $this->data['enviroment'];

		if ( $this->data['jsbacktrace'] ) {
			$desc .= "\n\r----\n\r *JavaScript**:";
			$desc .= "\n\r```" . $this->data['jsbacktrace'] . "```";
		}

		return $desc;
	}

}
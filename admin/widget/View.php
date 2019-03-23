<?php

/**
 * The plugin widget View logic
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 */

namespace FeedMe\admin\widget;

use FeedMe\admin\settings\SettingsController;
use FeedMe\core\views\ViewInterface;
use FeedMe\services\TrelloService;

/**
 * The plugin widget View logic
 *
 * Defines the plugin name and the methods used by the parser to populate the view template.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class View implements ViewInterface {

	const PLACEHOLDER_DESCRIPTION = array(
			'Describe con detalle el feedback. Trataremos de encontrar una solución ;-)',
			'Pasos para reproducirlo:\n\n- Acceder al backoffice.\n- Clickar en el botón de Feed Me.\n- Rellenar los campos del formulario.\n- Clickar en Enviar\n\nComportamiento actual:\n\nEnvía una tarjeta al panel de Trello para que el equipo se ponga a ello lo antes posible.\n\nComportamiento esperado:\n\nTener vacaciones ilimitadas en la playa.',
			'Sería genial añadir lazy loading a las imágenes de la web.',
			'¿Existe algún plugin que mejore las ventas de la tienda?'
		);

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
	 * The controller.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $controller The widget controller.
	 */
	private $controller;

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
		$this->controller  = new WidgetController( $this->plugin_name );
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

	/**
	 * Returns the title for modal and button.
	 *
	 * @since    1.0.0
	 *
	 * @return string The title.
	 */
	public function get_title(): string {
		return __( ucwords( $this->plugin_name, '- ' ) );
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

	/**
	 * Returns the Trello columns for selected trello board.
	 *
	 * @since    1.0.0
	 *
	 * @return array Trello columns with [trello-column-id] = trello-column-name format.
	 */
	public function get_columns(): array {
		$current_store = $this->settings->get( 'trello-board' );

		$trello  = new TrelloService( $this->get_trello_api_key(), $this->get_trello_api_token() );
		$columns = $trello->get_columns( $current_store );

		return array_filter( $columns );
	}

	/**
	 * Returns the action url for with wp-nonce
	 *
	 * @since    1.0.0
	 *
	 * @return string The action url.
	 */
	public function get_action_url(): string {
		$current_url = $this->get_current_url();

		return wp_nonce_url( $current_url, "{$this->plugin_name}_action", "{$this->plugin_name}_action_nonce" );
	}

	/**
	 * Returns the current url called in the server.
	 *
	 * @since    1.0.0
	 *
	 * @return string The current url.
	 */
	public function get_current_url(): string {
		$current_url = strip_tags( $_SERVER['REQUEST_URI'] );

		return $current_url;
	}

	/**
	 * Returns the full url called in the server.
	 *
	 * @since    1.0.0
	 *
	 * @return string The current url.
	 */
	public function get_url(): string {
		$full_url = get_site_url( null, self::get_current_url() );

		return base64_encode( $full_url );
	}

	/**
	 * Returns the ajax action name from the controller.
	 *
	 * @since    1.0.0
	 *
	 * @return string The ajax action name.
	 */
	public function get_ajax_action(): string {
		return $this->controller->get_ajax_action_name();
	}

	/**
	 * Returns the ajax url.
	 *
	 * @since    1.0.0
	 *
	 * @return string The current url.
	 */
	public function get_ajax_url(): string {
		return admin_url( 'admin-ajax.php' );
	}

	public function get_description_placeholder(): string {
		return __( self::PLACEHOLDER_DESCRIPTION[ array_rand( self::PLACEHOLDER_DESCRIPTION ) ], $this->plugin_name );
	}
}
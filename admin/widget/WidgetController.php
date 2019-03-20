<?php

/**
 * The widget controller
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 */

namespace FeedMe\admin\widget;

/**
 * The widget controller
 *
 * Loads the view or handle the form submit.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/widget
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class WidgetController {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The response for form submit.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $response An array with ok = true/false and error message if needed.
	 */
	private $response;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The ID of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->response    = [
			'ok'    => true,
			'error' => '',
		];
	}

	/**
	 * Defines the widget's behaviour.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		if ( wp_doing_ajax() ) {
			$this->register_ajax_controller();
		} else {
			$widgetViewController = new WidgetViewController( $this->plugin_name );
			$widgetViewController->load();
		}
	}

	/**
	 * Registers the action for plugin ajax requests.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	protected function register_ajax_controller(): void {
		$for_action = $this->get_ajax_action_name();

		$ajax_controller = [ &$this, 'handle_form' ];
		add_action( "wp_ajax_{$for_action}", $ajax_controller );
	}


	/**
	 * Returns the action name for ajax.
	 *
	 * @since    1.0.0
	 *
	 * @return string The action name for ajax.
	 */
	public function get_ajax_action_name(): string {
		return 'handle_' . $this->plugin_name;
	}

	/**
	 * Handles the form request and gives the response.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function handle_form(): void {
		try {
			$form = new Helpers\Form( $this->plugin_name );
			$form->import( $_POST[ $this->plugin_name ] );
			$form->set_attachment( $_FILES[ $this->plugin_name ] );
			$form->send_to_trello();
		} catch ( \Throwable $e ) {
			$this->response['ok']  = false;
			$this->response['msg'] = $e->getMessage();
		}

		wp_die( json_encode( $this->response ) );
	}

}

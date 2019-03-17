<?php

/**
 * The plugin settings registration
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
 */

namespace FeedMe\admin\widget;

/**
 * The plugin settings registration
 *
 * Defines the plugin name, settings, loads the view
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin
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

	public function __construct( string $plugin_name ) {
		$this->plugin_name    = $plugin_name;
	}

	public function init() {
		if ( wp_doing_ajax() ) {
			$this->register_ajax_controller();
		} else {
			$widgetViewController = new WidgetViewController($this->plugin_name);
			$widgetViewController->load();
		}
	}

	protected function register_ajax_controller() {
		$for_action = $this->get_ajax_action_name();

		$ajax_controller = [ &$this, 'handle_form' ];
		add_action( "wp_ajax_{$for_action}", $ajax_controller );
	}


	public function get_ajax_action_name() {
		return 'handle_' . $this->plugin_name;
	}

	public function handle_form() {
		$response = [
			'ok'    => true,
			'error' => '',
		];

		try {
			$form = new Helpers\Form();
			$form->import( $_POST[ PLUGIN_NAME ] );
			$form->putAttachment( $_FILES[ PLUGIN_NAME ] );
			$form->exportToCurrentStorage();
		} catch ( \Throwable $e ) {
			$response['ok']  = false;
			$response['msg'] = $e->getMessage();
		}

		echo json_encode( $response );
		wp_die();
	}

}

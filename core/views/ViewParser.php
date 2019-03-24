<?php

namespace FeedMe\core\views;

use FeedMe\settings\SettingsController;
use FeedMe\core\Feedme;

class ViewParser {
	public const PATH = PLUGIN_PATH . '/views/';
	public const EXTENSION = '.tpl';
	private const FIND_REPLACEMENT_VARS = '/\{%(.*?)%\}/';
	private const FIND_GET_TEXT_STRINGS = '/\{\'(.*?)\'\}/';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $content The parsed content.
	 */
	protected $content = '';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      null|view $view The view to parse.
	 */
	protected $view = null;

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
	 * @param      ViewInterface $view The view to parse
	 */
	public function __construct( ViewInterface $view ) {
		$this->plugin_name = Feedme::PLUGIN_NAME;
		$this->view        = $view;
		$this->content     = $this->get_view_content();
		$this->register_common_filters();
	}

	/**
	 * Returns the content of the view.
	 *
	 * @since    1.0.0
	 * @return string The content of the view.
	 */
	protected function get_view_content(): string {
		$view_name = $this->view->get_view_name();
		$view      = self::PATH . $view_name . self::EXTENSION;

		$content = '';
		if ( file_exists( $view ) ) {
			$content = file_get_contents( $view );
		} else {
			error_log( "Not found view {$view}" );
		}

		return $content;
	}

	/**
	 * Register the filters for the plugin for create options and settings.
	 *
	 * @since    1.0.0
	 */
	protected function register_common_filters() {
		static $done = false;

		if ( $done ) {
			return false;
		} else {
			$done = true;
		}

		add_filter( $this->plugin_name . '_options', [ &$this, 'create_options' ] );
		add_filter( $this->plugin_name . '_settings', [ &$this, 'create_settings' ], 10, 2 );
	}

	/**
	 * Parse the view content.
	 *
	 * @since    1.0.0
	 * @return  string View parsed content.
	 */
	public function parse(): string {
		$this->content = str_replace( "{{PLUGIN}}", $this->plugin_name, $this->content );
		$this->content = preg_replace_callback( self::FIND_REPLACEMENT_VARS, [ $this, 'replace_replacement_vars' ],
			$this->content );
		$this->content = preg_replace_callback( self::FIND_GET_TEXT_STRINGS, [ $this, 'replace_get_text_strings' ],
			$this->content );

		return $this->content;
	}

	/**
	 * Replace the selected var with the correct value.
	 *
	 * @since    1.0.0
	 *
	 * @param      array $tag The tag to replace.
	 *
	 * @return  string View parsed content.
	 */
	protected function replace_replacement_vars( array $tag ): string {
		$replacement = $tag[1] ?? '';

		$filters = $this->extract_filters( $replacement );
		$value   = $this->get_value( $replacement );

		foreach ( $filters as $filter ) {
			$filter = $this->plugin_name . '_' . $filter;
			$value  = apply_filters( $filter, $value, $replacement );
		}

		return $value;
	}

	/**
	 * Return the existing filters inside a string.
	 *
	 * @param string $replacement String with filters ( var | filter1 | filter2 ) format.
	 *
	 * @return array Array with all filters found.
	 */
	protected function extract_filters( string &$replacement ): array {
		$filters = explode( '|', $replacement );
		$filters = array_map( 'trim', $filters );

		$replacement = array_shift( $filters );

		return $filters;
	}

	/**
	 * Returns the value for a var name in the view.
	 *
	 * @since    1.0.0
	 *
	 * @param string $var_name The var name to get its value.
	 *
	 * @return string Var value.
	 */
	protected function get_value( string $var_name ) {
		$view   = $this->view;
		$method = 'get_' . $var_name;

		$var_exists = ! empty( $var_name ) && method_exists( $view, $method );

		$value = '';
		if ( $var_exists ) {
			$value = $view->$method();
		}

		return $value;
	}

	/**
	 * Translate the tagged text in the view.
	 *
	 * @param array $tag Text to translate.
	 *
	 * @return string|void Translated text.
	 */
	protected function replace_get_text_strings( array $tag ): ?string {
		$string = $tag[1] ?? '';

		return __( $string, $this->plugin_name );
	}

	/**
	 * Returns all the html needed to show options for settings.
	 *
	 * @param array  $options Options to loop.
	 * @param string $setting Setting used to set the option.
	 *
	 * @return string Html with all the options.
	 */
	public function create_settings( array $options, $setting = '' ): string {
		$html     = '';

		$setting       = str_replace( '_', '-', rtrim( $setting, 's' ) );
		$current_value = SettingsController::get( $setting, '' );

		//only values
		if ( isset( $options[0] ) ) {
			$options = array_combine( $options, $options );
		}

		foreach ( $options as $value => $label ) {

			$selected = $current_value === $value ? 'selected="selected"' : '';

			$html .= "<option value='{$value}' {$selected}>{$label}</option>";
		}

		return $html;
	}

	public function create_options( array $options ): string {
		$html = '';

		//only values
		if ( isset( $options[0] ) ) {
			$options = array_combine( $options, $options );
		}

		foreach ( $options as $value => $label ) {
			$html .= "<option value='{$value}'>{$label}</option>";
		}

		return $html;
	}
}
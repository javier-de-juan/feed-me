<?php

/**
 * The interface for plugin views.
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/core/views
 */
namespace FeedMe\core\views;

/**
 * The interface for plugin views.
 *
 * Defines the default behaviour for all views used in the plugin.
 *
 * @package    Feed_me
 * @subpackage Feed_me/admin/settings
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
interface ViewInterface {

	/**
	 * Returns the name of the view.
	 *
	 * @return string The name of the view.
	 */
	public function get_view_name(): string;
}
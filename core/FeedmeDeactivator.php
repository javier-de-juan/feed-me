<?php

/**
 * Fired during plugin deactivation
 *
 *
 * @since      1.0.0
 *
 * @package    Feed_me
 * @subpackage Feed_me/core
 */

namespace FeedMe\core;

use FeedMe\settings\SettingsController;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Feed_me
 * @subpackage Feed_me/core
 * @author     Javier De Juan Trujillo social@javierdejuan.es
 */
class FeedmeDeactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$settings = new SettingsController();
		$settings->deactivate_settings();
	}

}

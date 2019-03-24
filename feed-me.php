<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also core all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Feed_me
 *
 * @wordpress-plugin
 * Plugin Name:       Feed-Me
 * Plugin URI:        https://gitlab.com/javierdejuan/feedme
 * Description:       Feed-Me is a WordPress plugin which allows users to give feedback about the project in a Trello panel.
 * Version:           1.0.0
 * Author:            Javier De Juan Trujillo
 * Author URI:        https://www.javierdejuan.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       feed-me
 * Domain Path:       /languages
 */

use FeedMe\core\Feedme;
use FeedMe\core\FeedmeActivator;
use FeedMe\core\FeedmeDeactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FEED_ME_VERSION', '1.0.0' );

/**
 * Currently plugin path.
 */
define( 'PLUGIN_PATH', __DIR__ );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in core/class-feed-me-deactivator.php
 */
function deactivate_feed_me(): void {
	FeedmeDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_feed_me' );
register_deactivation_hook( __FILE__, 'deactivate_feed_me' );

/**
 * Register composer's autoloader
 */
function register_autoloader(): void {
	$autoload = __DIR__ . '/vendor/autoload.php';

	if ( ! file_exists( $autoload ) ) {
		wp_die( "Autoload not found for Feed-me plugin" );
	}

	require_once $autoload;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_feed_me(): void {
	register_autoloader();

	if ( ! is_user_logged_in() ) {
		return;
	}

	$plugin = new Feedme();
	$plugin->run();
}

add_action('init', 'run_feed_me');


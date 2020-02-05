<?php
/**
 * Plugin main file.
 *
 * @package   OnePlace\Article
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 *
 * @wordpress-plugin
 * Plugin Name: WP PLC Article
 * Plugin URI:  https://1plc.ch/wordpress-plugins/article
 * Description: onePlace Article for Wordpress. Widgets and Shortcodes for onePlace Articles
 * Version:     1.0.1
 * Author:      Verein onePlace
 * Author URI:  https://1plc.ch
 * License:     GNU General Public License, version 2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wp-plc-article
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define Version and directories for further use in plugin
define( 'WPPLC_ARTICLE_VERSION', '1.0.1' );
define( 'WPPLC_ARTICLE_MAIN_FILE', __FILE__ );
define( 'WPPLC_ARTICLE_MAIN_DIR', __DIR__ );
define( 'WPPLC_ARTICLE_PUB_DIR','/wp-content/plugins/wp-plc-article');

/**
 * Handles plugin activation.
 *
 * Throws an error if the plugin is activated on an older version than PHP 5.4.
 *
 * @access private
 *
 * @param bool $network_wide Whether to activate network-wide.
 */
function wpplc_article_activate_plugin( $network_wide ) {
    // check php version
    if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
        // show error if version is below 5.4
        wp_die(
            esc_html__( 'WP PLC Article requires PHP version 5.4.', 'wp-plc-article' ),
            esc_html__( 'Error Activating', 'wp-plc-article' )
        );
    }

    // check if oneplace connect is already loaded
    if ( ! defined('WPPLC_CONNECT_VERSION') ) {
        // show error if version cannot be determined
        wp_die(
            esc_html__( 'WP PLC Article requires WP PLC Connect', 'wp-plc-article' ),
            esc_html__( 'Error Activating', 'wp-plc-article' )
        );
    }

    // we currently support multisite - so we just activate on network wide
}
register_activation_hook( __FILE__, 'wpplc_article_activate_plugin' );

/**
 * Handles plugin deactivation.
 *
 * @access private
 *
 * @param bool $network_wide Whether to deactivate network-wide.
 */
function wpplc_article_deactivate_plugin( $network_wide ) {
    if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
        return;
    }

    // deactivation network wide is the same for now
}
register_deactivation_hook( __FILE__, 'wpplc_article_deactivate_plugin' );

// make sure php version is up2date
if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/loader.php';
}
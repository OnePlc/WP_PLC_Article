<?php

/**
 * WP PLC Article Shortcodes
 *
 * @package   OnePlace\Article\Modules
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/swissknife
 */

namespace OnePlace\Article\Modules;

use OnePlace\Article\Plugin;

final class Shortcodes {
    /**
     * Main instance of the module
     *
     * @since 1.0.0
     * @var Plugin|null
     */
    private static $instance = null;

    /**
     * Disable wordpress comments entirely
     *
     * @since 1.0.0
     */
    public function register() {
        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        add_shortcode('wpplc-article-slider', [$this, 'printArticleSlider'] );
    }

    /**
     * Article Slider Shortcode
     *
     * @since 1.0.0
     */
    public function printArticleSlider() {
        # Get Connection Data from WP PLC Connect
        $sHost = get_option('plcconnect_server_url');
        $sHostKey = get_option('plcconnect_server_key');

        # Dont start if we have no connection
        if($sHost == '') {
            echo 'oneplace not connected!';
        } else {
            # get article items
            $sJsonInfo = file_get_contents($sHost . '/article/api/list/0?authkey=' . $sHostKey);
            $oCatInfo = json_decode($sJsonInfo);

            # print error if no json
            if (!is_object($oCatInfo)) {
                echo 'could not load articles';
                if (is_user_logged_in()) {
                    echo '<pre>' . $sJsonInfo . '</pre>';
                }
            } else {
                # only view template if success
                if ($oCatInfo->state == 'success') {
                    # get items
                    $aItems = $oCatInfo->results;

                    # Generate Unique ID for Slider
                    $sSliderID = '1234';

                    # get template
                    include_once __DIR__.'/../view/partials/article-slider.php';
                } else {
                    echo 'Error: ' . $oCatInfo->message;
                }
            }
        }
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Core Settings

        // Sub Module Handling
        //register_setting( 'wpplc_article', 'plcarticle_elementor_active', false );
    }

    /**
     * Loads the module main instance and initializes it.
     *
     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load() {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new self();
        static::$instance->register();
        return true;
    }
}
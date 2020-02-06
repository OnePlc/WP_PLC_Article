<?php

/**
 * WP PLC Article List View
 *
 * @package   OnePlace\Article\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/swissknife
 */

namespace OnePlace\Article\Modules;

use OnePlace\Article\Plugin;

final class Listview {
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
        # Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        # Enable Custom Rewrite if set
        if(get_option('plcarticle_listview_rewrite_active') == 1) {
            add_action('init', [$this,'enableCustomRewriteRule'], 10, 0);
        }

        # Load Elementor Widget if active
        if(get_option( 'plcarticle_elementor_active') == 1) {
            // register elementor widgets
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'initElementorWidgets' ] );
        }
    }

    /**
     * Initialize Elementor Widgets if activated
     *
     * @since 1.0.0
     */
    public function initElementorWidgets() {
        # Load Article Slider if active
        if(get_option( 'plcarticle_elementor_article_slider_active') == 1) {
            require_once(__DIR__ . '/../elementor/widgets/article-list.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Article_List());
        }
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Sub Module Handling
        register_setting( 'wpplc_article_listview', 'plcarticle_listview_slug', 'article' );
        register_setting( 'wpplc_article_listview', 'plcarticle_listview_rewrite_active', false );
    }

    /**
     * Enable Rewrite Rule for List View
     *
     * @since 1.0.0
     */
    public function enableCustomRewriteRule() {
        # Only enable if slug is set
        if(get_option('plcarticle_listview_slug')) {
            // Article Category
            add_rewrite_tag('%article_id%', '([^&]+)');

            $sListViewSlug = get_option('plcarticle_listview_slug');
            $iBasePageID = get_option('plcarticle_listview_base_page');

            // Article Category
            add_rewrite_rule('^'.$sListViewSlug.'/([^/]*)/?', 'index.php?page_id=' . $iBasePageID . '&article_id=$matches[1]', 'top');
        }
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
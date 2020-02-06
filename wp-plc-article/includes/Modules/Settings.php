<?php

/**
 * WP PLC Article Settings
 *
 * @package   OnePlace\Article\Modules
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/swissknife
 */

namespace OnePlace\Article\Modules;

use OnePlace\Article\Plugin;

final class Settings {
    /**
     * Main instance of the module
     *
     * @since 0.1-stable
     * @var Plugin|null
     */
    private static $instance = null;

    /**
     * Disable wordpress comments entirely
     *
     * @since 0.1-stable
     */
    public function register() {
        // Add submenu page for settings
        add_action("admin_menu", [ $this, 'addSubMenuPage' ]);

        // Add submenu page for settings
        add_action("admin_menu", [ $this, 'addMySubMenuPage' ], 99);

        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // Add Plugin Languages
        add_action('plugins_loaded', [ $this, 'loadTextDomain' ] );

        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );
    }

    /**
     * load text domain (translations)
     *
     * @since 1.0.0
     */
    public function loadTextDomain() {
        load_plugin_textdomain( 'wp-plc-article', false, dirname( plugin_basename(WPPLC_ARTICLE_MAIN_FILE) ) . '/language/' );
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Core Settings

        // Sub Module Handling
        register_setting( 'wpplc_article', 'plcarticle_elementor_active', false );
        register_setting( 'wpplc_article', 'plcarticle_shortcodes_active', false );
        register_setting( 'wpplc_article', 'plcarticle_singleview_active', false );
        register_setting( 'wpplc_article', 'plcarticle_listview_active', false );
    }

    /**
     * Add Submenu Page to OnePlace Settings Menu
     *
     * @since 1.0.0
     */
    public function addSubMenuPage() {
        add_submenu_page( 'oneplace-connect', 'OnePlace Connect', 'OnePlace Connect',
            'manage_options', 'oneplace-connect' );
    }

    public function addMySubMenuPage() {
        add_submenu_page( 'oneplace-connect', 'OnePlace Article', 'Article',
            'manage_options', 'oneplace-article',  [$this,'renderArticleSettingsPage'] );
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
        if(get_option('plcarticle_elementor_article_slider_active') == 1) {
            wp_enqueue_script('plc-article-slider', '/wp-content/plugins/wp-plc-article/assets/js/article-slider.js', ['jquery']);
            wp_enqueue_style( 'plc-article-slider-style', '/wp-content/plugins/wp-plc-article/assets/css/article-slider.css');
        }
    }

    /**
     * Render Settings Page for Plugin
     *
     * @since 1.0.0
     */
    public function renderArticleSettingsPage() {
        require_once __DIR__.'/../view/settings.php';
    }

    /**
     * Loads the module main instance and initializes it.
     *
     * @since 0.1-stable
     *
     * @return bool True if the plugin main instance could be loaded, false otherwise.
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
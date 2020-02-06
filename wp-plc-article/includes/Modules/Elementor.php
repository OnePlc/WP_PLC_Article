<?php

/**
 * Elementor Integration for OnePlace Article
 *
 * @package   OnePlace\Article\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/article
 */

namespace OnePlace\Article\Modules;

use OnePlace\Article\Plugin;

final class Elementor {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Shop Elementor Integration
     *
     * @since 1.0.0
     */
    public function register() {
        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // create category for elementor
        add_action( 'elementor/elements/categories_registered', [$this,'addElementorWidgetCategories'] );

        // register elementor widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'initElementorWidgets' ] );

        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );

        add_action( 'elementor/controls/controls_registered', [ $this, 'registerElementorControls' ] );
    }

    public function registerElementorControls() {
        require_once(__DIR__ . '/../elementor/controls/plcmultisortable-control.php');
        $controls_manager = \Elementor\Plugin::$instance->controls_manager;
        $controls_manager->register_control( 'plc-multi-sortable', new \OnePlace\Elementor\Controls\WPPLC_Multisortable_Control() );
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
    }

    /**
     * Initialize Elementor Widgets if activated
     *
     * @since 1.0.0
     */
    public function initElementorWidgets() {
        # Load Article Slider if active
        if(get_option( 'plcarticle_elementor_article_slider_active') == 1) {
            require_once(__DIR__ . '/../elementor/widgets/article-slider.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Article_Slider());
        }
    }

    /**
     * Create Category for our Elementor
     * Widgets
     *
     * @since 1.0.0
     */
    public function addElementorWidgetCategories( $elements_manager ) {
        $elements_manager->add_category(
            'wp-plc-article',
            [
                'title' => __( 'OnePlace Article', 'wp-plc-article' ),
                'icon' => 'fa fa-tags',
            ]
        );
    }

    /**
     * Register Elementor specific settings
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Widgets
        register_setting( 'wpplc_article_elementor', 'plcarticle_elementor_article_slider_active', false );
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
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

        # Register Update Settings AJAX Hook
        add_action('wp_ajax_wpplc_article_listview', [ $this, 'renderListView' ] );

        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );
        add_action( 'init', [$this, 'registerSingleViewSitemap'], 99 );
        add_action( 'init', [$this, 'registerSingleViewSitemapActions'] );
    }

    public function registerSingleViewSitemapActions() {
        add_action( 'wp_seo_do_sitemap_our-maschinenindexes', [$this, 'generateSitemap'] );
    }

    public function registerSingleViewSitemap() {
        global $wpseo_sitemaps;
        $wpseo_sitemaps->register_sitemap( 'maschinenindexes', [$this, 'generateSitemap'] );
    }

    public function generateSitemap() {
        global $wpseo_sitemaps;

        $aParams = ['listmode' => 'entity', 'listmodefilter' => 'webonly'];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/1', $aParams);
        $sSingleViewSlug = get_option('plcarticle_listview_slug');

        $output = '';

        if ($oAPIResponse->state == 'success') {
            # get items
            $iPages = $oAPIResponse->pages;

            for($i = 1;$i <= $iPages;$i++) {
                $pri = 1;
                $chf = 'weekly';
                $mod = date('Y-m-d',time());
                $url = array();
                $url['loc'] = site_url().'/'.$sSingleViewSlug.'/'.$i;
                $url['pri'] = $pri;
                $url['mod'] = $mod;
                $url['chf'] = $chf;
                $output .= $wpseo_sitemaps->renderer->sitemap_url( $url );
            }
        }

        //Build the full sitemap
        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        //echo $sitemap;
        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
        # add necessary js files
        wp_enqueue_script( 'wp-plc-article-list', plugins_url('assets/js/article-list.js', WPPLC_ARTICLE_MAIN_FILE), [ 'jquery' ] );
        wp_localize_script('wp-plc-article-list', 'plcArticleList', [
            'pluginUrl' => plugins_url('',WPPLC_ARTICLE_MAIN_FILE),
            'ajaxUrl' => admin_url('admin-ajax.php'),
        ]);
    }

    public function renderListView() {
        #Get Articles from onePlace API
        $aParams = ['listmode' => 'entity'];
        $sLang = '';
        $iPage = $_REQUEST['page_id'];
        if (defined('ICL_LANGUAGE_CODE')) {
            if (ICL_LANGUAGE_CODE == 'en') {
                $sLang = 'en_US';
            }
            if (ICL_LANGUAGE_CODE == 'de') {
                $sLang = 'de_DE';
            }
            $aParams['lang'] = $sLang;
        }

        echo 'lang:'.$sLang;

        $sJson = str_replace(['\"'],['"'],$_REQUEST['widget_settings']);
        $aSettings = (array)json_decode($sJson);
        if(isset($aSettings['list_base_category'])) {
            $aParams['listmodefilter'] = 'webonly';
            $aParams['filter'] = 'category';
            $aParams['filtervalue'] = $aSettings['list_base_category'];
        }
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/'.$iPage, $aParams);

        if ($oAPIResponse->state == 'success') {
            $aItems = $oAPIResponse->results;
            $iPages = $oAPIResponse->pages;
            $aFields = [];

            $oAPISubResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0', ['lang' => $sLang]);
            if($oAPISubResponse->state == 'success') {
                $aFieldsAPI = (array)$oAPISubResponse->aFields;
                if(count($aFieldsAPI) > 0) {
                    foreach($aFieldsAPI as $oField) {
                        $aFields[$oField->fieldkey] = $oField->label;
                    }
                }
            }

            require_once(__DIR__ . '/../view/partials/article-list-ajax.php');
        } else {
            echo 'error loading articles';
        }
        exit();
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
            add_rewrite_tag('%list_page_id%', '([^&]+)');
            add_rewrite_tag('%list_base_category%', '([^&]+)');

            $sListViewSlug = get_option('plcarticle_listview_slug');
            $iBasePageID = get_option('plcarticle_listview_base_page');

            // Article Category
            add_rewrite_rule('^'.$sListViewSlug.'/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?page_id=' . $iBasePageID . '&list_base_category=$matches[2]&list_page_id=$matches[3]', 'top');
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
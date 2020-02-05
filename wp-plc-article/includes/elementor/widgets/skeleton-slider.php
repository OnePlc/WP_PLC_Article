<?php

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;

class WPPLC_Article_Slider extends \Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcarticleslider';
    }

    public function get_title() {
        return __('Article Slider', 'wp-plc-article');
    }

    public function get_icon() {
        return 'fa fa-images';
    }

    public function get_categories() {
        return ['wp-plc-article'];
    }

    protected function render() {
        # Get Elementor Widgets Settings
        $aSettings = $this->get_settings_for_display();

        //$aSettings['slides_per_view'] = 3;
        # Set default values

        # Get Connection Data from onePlace Core
        $sHost = get_option('plcconnect_server_url');
        $sHostKey = get_option('plcconnect_server_key');

        if($sHost == '') {
            echo 'oneplace not connected!';
        } else {
            $sJsonInfo = file_get_contents($sHost.'/article/api/list/0?authkey='.$sHostKey);
            $oCatInfo = json_decode($sJsonInfo);

            if(!is_object($oCatInfo)) {
                echo 'could not load articles';
                if(is_user_logged_in()) {
                    echo '<pre>'.$sJsonInfo.'</pre>';
                }
            } else {
                if($oCatInfo->state == 'success') {
                    // get items
                    $aItems = $oCatInfo->results;

                    // Generate Elementor Unique ID for Slider
                    $sSliderID = \ Elementor\Utils::generate_random_string();

                    // Load Template
                    include __DIR__.'/../../view/partials/article-slider.php';
                } else {
                    echo 'Error: '.$oCatInfo->message;
                }
            }
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * GENERAL SETTINGS - START
         * @since 1.0.0
         */

        # Section - Start
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => __('Slider - General Settings', 'wp-plc-article'),
            ]
        );

        # Control - Slides per View
        $this->add_control(
            'section_slider_slides_per_view',
            [
                'label' => __('Slides per View', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
            ]
        );

        # Section - End
        $this->end_controls_section();

        /**
         * GENERAL SETTINGS - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'slider_slide_title_settings',
            [
                'label' => __('Slides - Title', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Title Color
        $this->add_control(
            'slider_slide_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} h3.plc-slider-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_title_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h3.plc-slider-title',
            ]
        );

        # Slide Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'slider_slide_title_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} h3.plc-slider-title',
            ]
        );

        # Section - End
        $this->end_controls_section();
    }
}
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

        # Get Articles from onePlace API
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0',['listmode'=>'entity']);

        if($oAPIResponse->state == 'success') {
            # get items
            $aItems = $oAPIResponse->results;

            # Generate Elementor Unique ID for Slider
            $sSliderID = \ Elementor\Utils::generate_random_string();

            # Get Fields from onePlace
            $aFields = [];
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0');
            if(is_object($oAPIResponse)) {
                if($oAPIResponse->state == 'success') {
                    if(count($oAPIResponse->aFields) > 0) {
                        foreach($oAPIResponse->aFields as $oField) {
                            $aFields[$oField->fieldkey] = $oField->label;
                        }
                    }
                } else {
                    echo 'Error: '.$oAPIResponse->message;
                }

                $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();

                # Load Template
                include __DIR__.'/../../view/partials/article-slider.php';
            } else {
                echo 'error loading fields';
            }
        } else {
            echo 'Error: '.$oAPIResponse->message;
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

        # Get Fields from onePlace
        $aFields = [];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0');
        if(is_object($oAPIResponse)) {
            if($oAPIResponse->state == 'success') {
                if(count($oAPIResponse->aFields) > 0) {
                    foreach($oAPIResponse->aFields as $oField) {
                        $aFields[$oField->fieldkey] = $oField->label;
                    }
                }
            }
        }

        # Section - Start
        $this->start_controls_section(
            'section_slider_fields',
            [
                'label' => __('Slider - Fields', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'slider_featured_fields',
            [
                'label' => __( 'Fields', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $aFields,
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

        /**
         * STYLE SETTINGS - TITLE - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - ATTRIBUTE LEFT - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'slider_slide_attr_left_settings',
            [
                'label' => __('Slides - Attribute Labels', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'slider_slide_attr_left_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-attribute-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_attr_left_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-attribute-label',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'slider_slide__attr_left_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-slider-attribute-label',
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - ATTRIBUTE LEFT - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - ATTRIBUTE RIGHT - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'slider_slide_attr_right_settings',
            [
                'label' => __('Slides - Attribute Values', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'slider_slide_attr_right_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-attribute-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_attr_right_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-attribute-value',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'slider_slide__attr_right_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-slider-attribute-value',
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - ATTRIBUTE RIGHT - END
         * @since 1.0.0
         */
    }
}
<?php

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;

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

        $sLang = 'de_DE';
        if(defined('ICL_LANGUAGE_CODE')) {
            if (ICL_LANGUAGE_CODE == 'en') {
                $sLang = 'en_US';
            }
            if (ICL_LANGUAGE_CODE == 'de') {
                $sLang = 'de_DE';
            }
        }

        # Get Articles from onePlace API
        $aApiData = ['listmode' => 'entity', 'lang' => $sLang];
        if($aSettings['slider_articles_filter'] != 'all' && $aSettings['slider_articles_filter'] != '') {
            $aApiData['filter'] = $aSettings['slider_articles_filter'];
        }
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0',$aApiData);

        if($oAPIResponse->state == 'success') {
            # get items
            $aItems = $oAPIResponse->results;

            # Generate Elementor Unique ID for Slider
            $sSliderID = \ Elementor\Utils::generate_random_string();

            # Get Fields from onePlace
            $aFields = [];
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0', ['lang' => $sLang]);
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
        # Get Fields from onePlace
        $aFields = [];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0');
        $bHighLightFilterActive = false;
        if(is_object($oAPIResponse)) {
            if($oAPIResponse->state == 'success') {
                if(count($oAPIResponse->aFields) > 0) {
                    foreach($oAPIResponse->aFields as $oField) {
                        if($oField->fieldkey == 'web_highlight_idfs') {
                            $bHighLightFilterActive = true;
                        }
                        $aFields[$oField->fieldkey] = $oField->label;
                    }
                }
            }
        }

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

        $aSliderFilters = ['all' => __('All', 'wp-plc-article')];
        if($bHighLightFilterActive) {
            $aSliderFilters['highlights'] = __('Only Highlights', 'wp-plc-article');
        }

        $this->add_control(
            'slider_articles_filter',
            [
                'label' => __( 'Articles in Slider', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $aSliderFilters,
            ]
        );

        # Section - End
        $this->end_controls_section();

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
         * STYLE SETTINGS - BOX - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'slider_slide_box_settings',
            [
                'label' => __('Slides - Box', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Background for box
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __( 'Background', 'wp-plc-article' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .plc-article-slider-box-content',
            ]
        );

        # Padding
        $this->add_control(
            'slider_box_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-article-slider-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - BOX - END
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
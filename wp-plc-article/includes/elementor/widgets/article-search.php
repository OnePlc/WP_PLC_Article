<?php

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

class WPPLC_Article_Search extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcarticlesearch';
    }

    public function get_title() {
        return __('Article Search', 'wp-plc-article');
    }

    public function get_icon() {
        return 'fa fa-search';
    }

    public function get_categories() {
        return ['wp-plc-article'];
    }

    protected function render() {
        # Get Elementor Widgets Settings
        $aSettings = $this->get_settings_for_display();

        include __DIR__ . '/../../view/partials/article-search.php';
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * GENERAL SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'section_search_title',
            [
                'label' => __('Search - Title', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'search_title',
            [
                'label' => __( 'Title', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Search', 'wp-plc-article' ),
                'placeholder' => __( 'Search', 'wp-plc-article' ),
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * GENERAL SETTINGS - TITLE - END
         * @since 1.0.0
         */

        /**
         * GENERAL SETTINGS - BUTTON - START
         * @since 1.0.0
         */
        $this->start_controls_section(
            'search_button',
            [
                'label' => __('Search - Button', 'wp-plc-article'),
            ]
        );

        // Button Text
        $this->add_control(
            'search_button_text',
            [
                'label' => __('Text', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Find Article', 'wp-plc-article'),
                'placeholder' => __('Find Article', 'wp-plc-article'),
            ]
        );

        $sListViewSlug = '/'.get_option('plcarticle_listview_slug');
        $this->add_control(
            'search_button_link',
            [
                'label' => __( 'Link', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'wp-plc-article' ),
                'show_external' => true,
                'default' => [
                    'url' => $sListViewSlug.'/search/',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button Icon
        $this->add_control(
            'search_button_icon',
            [
                'label' => __('Icon', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * GENERAL SETTINGS - BUTTON - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'search_title_settings',
            [
                'label' => __('Search - Title', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Title Color
        $this->add_control(
            'search_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-article-search-widget h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_title_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-article-search-widget h3',
            ]
        );

        # Slide Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'search_title_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-article-search-widget h3',
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - TITLE - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - BUTTONS - START
         * @since 1.0.0
         */
        $this->start_controls_section(
            'search_buttons_style',
            [
                'label' => __( 'Search - Button', 'wp-plc-article' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'search_button_text_margin',
            [
                'label' => __( 'Margin', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'search_buttons_typo',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-search-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'search_tab_button_normal',
            [
                'label' => __( 'Normal', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'search_button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_button_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'search_tab_button_hover',
            [
                'label' => __( 'Hover', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'search_button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_button_hover_animation',
            [
                'label' => __( 'Hover Animation', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'search_buttons_border',
                'selector' => '{{WRAPPER}} .plc-search-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'search_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'search_button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-search-button',
            ]
        );
        $this->add_responsive_control(
            'search_button_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - BUTTONS - END
         * @since 1.0.0
         */
    }
}
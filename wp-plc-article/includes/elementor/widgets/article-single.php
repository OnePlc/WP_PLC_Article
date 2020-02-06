<?php

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

class WPPLC_Article_Single extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcarticlesingle';
    }

    public function get_title() {
        return __('Article Single', 'wp-plc-article');
    }

    public function get_icon() {
        return 'fa fa-image';
    }

    public function get_categories() {
        return ['wp-plc-article'];
    }

    protected function render() {
        # Get Elementor Widgets Settings
        $aSettings = $this->get_settings_for_display();

        # Get Article ID
        $iArticleID = get_query_var('article_id');

        if(empty($iArticleID)) {
            $iArticleID = 1;
        }

        if(is_numeric($iArticleID) && !empty($iArticleID)) {
            # Get Articles from onePlace API
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/get/' . $iArticleID, []);

            if ($oAPIResponse->state == 'success') {
                # get items
                $oItem = $oAPIResponse->oItem;

                # Generate Elementor Unique ID for Slider
                $sSliderID = \ Elementor\Utils::generate_random_string();

                # Get Fields from onePlace
                $aFields = [];
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0');
                if (is_object($oAPIResponse)) {
                    if ($oAPIResponse->state == 'success') {
                        if (count($oAPIResponse->aFields) > 0) {
                            foreach ($oAPIResponse->aFields as $oField) {
                                $aFields[$oField->fieldkey] = $oField->label;
                            }
                        }
                    } else {
                        echo 'Error: ' . $oAPIResponse->message;
                    }

                    $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();

                    # Load Template
                    include __DIR__ . '/../../view/partials/article-single.php';
                } else {
                    echo 'error loading fields';
                }
            } else {
                echo 'Error: ' . $oAPIResponse->message;
            }
        } else {
            echo 'no article selected';
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
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

        /**
         * GENERAL SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'section_singleview_title',
            [
                'label' => __('Single View - Title', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'singleview_title_template',
            [
                'label' => __( 'Title', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( '##title##', 'wp-plc-article' ),
                'placeholder' => __( 'Title', 'wp-plc-article' ),
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * GENERAL SETTINGS - TITLE - END
         * @since 1.0.0
         */

        /**
         * GENERAL SETTINGS - FIELDS - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'section_singleview_fields',
            [
                'label' => __('Single View - Fields', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'singleview_featured_fields',
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
         * GENERAL SETTINGS - FIELDS - END
         * @since 1.0.0
         */

        /**
         * GENERAL SETTINGS - BUTTON 1 - START
         * @since 1.0.0
         */
        $this->start_controls_section(
            'single_view_button_1',
            [
                'label' => __('Single View Item - Button 1', 'wp-plc-article'),
            ]
        );

        // Button Text
        $this->add_control(
            'single_view_button_1_text',
            [
                'label' => __('Text', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Contact us', 'wp-plc-article'),
                'placeholder' => __('Contact us', 'wp-plc-article'),
            ]
        );

        $sSingleViewSlug = '/'.get_option('plcarticle_singleview_slug');
        $this->add_control(
            'single_view_button_1_link',
            [
                'label' => __( 'Link', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'wp-plc-article' ),
                'show_external' => true,
                'description'=>'You can use placeholders in your links<br/>##ID## List Item ID <br/>##title## List Item Title',
                'default' => [
                    'url' => 'mailto:info@example.com',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button Icon
        $this->add_control(
            'single_view_button_1_icon',
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
         * GENERAL SETTINGS - BUTTON 1 - END
         * @since 1.0.0
         */

        /**
         * GENERAL SETTINGS - BUTTON 2 - START
         * @since 1.0.0
         */
        $this->start_controls_section(
            'single_view_button_2',
            [
                'label' => __('Single View Item - Button 2', 'wp-plc-article'),
            ]
        );

        // Button Text
        $this->add_control(
            'single_view_button_2_text',
            [
                'label' => __('Text', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Youtube Video', 'wp-plc-article'),
                'placeholder' => __('Youtube Video', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'single_view_button_2_link',
            [
                'label' => __( 'Link', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'wp-plc-article' ),
                'show_external' => true,
                'description'=>'You can use placeholders in your links<br/>##ID## List Item ID <br/>##attribute## Item Attribute',
                'default' => [
                    'url' => '##weblink_youtube##',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button Icon
        $this->add_control(
            'single_view_button_2_icon',
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
         * GENERAL SETTINGS - BUTTON 2 - END
         * @since 1.0.0
         */

        /**
         * GENERAL SETTINGS - DESCRIPTION - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'section_singleview_description',
            [
                'label' => __('Single View - Description', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'singleview_description_title',
            [
                'label' => __( 'Title', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( '', 'wp-plc-article' ),
                'placeholder' => __( 'Title', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'singleview_show_description',
            [
                'label' => __( 'Show Description', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-article' ),
                'label_off' => __( 'Hide', 'wp-plc-article' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * GENERAL SETTINGS - DESCRIPTION - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'single_view_title_settings',
            [
                'label' => __('Single View - Title', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Single Title Color
        $this->add_control(
            'single_view_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-article-single-view h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Single Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_title_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-article-single-view h3',
            ]
        );

        # Single Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'single_view_title_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-article-single-view h3',
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
            'single_view_attr_left_settings',
            [
                'label' => __('Single View Item - Attribute Labels', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'single_view_attr_left_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-single-attribute-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_attr_left_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-single-attribute-label',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'single_view_attr_left_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-single-attribute-label',
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
            'single_view_attr_right_settings',
            [
                'label' => __('Single View Item - Attribute Values', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'single_view_attr_right_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-single-attribute-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_attr_right_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-single-attribute-value',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'single_view_attr_right_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-single-attribute-value',
            ]
        );

        # Section - End
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - ATTRIBUTE RIGHT - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - BUTTONS - START
         * @since 1.0.0
         */
        $this->start_controls_section(
            'single_view_buttons_style',
            [
                'label' => __( 'Single View Item - Buttons', 'wp-plc-article' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_buttons_typo',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-single-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'single_view_button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'single_view_button_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'single_view_tab_button_hover',
            [
                'label' => __( 'Hover', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'single_view_button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'single_viewbutton_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'single_view_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'single_view_button_hover_animation',
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
                'name' => 'single_view_buttons_border',
                'selector' => '{{WRAPPER}} .plc-single-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'single_view_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'single_view_button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-single-button',
            ]
        );
        $this->add_responsive_control(
            'single_view_button_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-single-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /**
         * STYLE SETTINGS - BUTTONS - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - DESCRIPTION TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'single_view_desc_title_settings',
            [
                'label' => __('Single View - Description - Title', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Single Title Color
        $this->add_control(
            'single_view_desc_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} h4.plc-single-description-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Single Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_desc_title_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h4.plc-single-description-title',
            ]
        );

        # Single Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'single_view_desc_title_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} h4.plc-single-description-title',
            ]
        );

        $this->add_responsive_control(
            'single_view_desc_title_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} h4.plc-single-description-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        # Section - End
        $this->end_controls_section();

        /**
         * STYLE SETTINGS - DESCRIPTION TITLE - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - DESCRIPTION - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'single_view_desc_settings',
            [
                'label' => __('Single View - Description', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Single Title Color
        $this->add_control(
            'single_view_desc_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-single-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Single Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'single_view_desc_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-single-description,{{WRAPPER}} .plc-single-description p',
            ]
        );

        # Single Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'single_view_desc_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-single-description',
            ]
        );

        $this->add_responsive_control(
            'single_view_desc_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-single-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        # Section - End
        $this->end_controls_section();

        /**
         * STYLE SETTINGS - DESCRIPTION - END
         * @since 1.0.0
         */
    }
}
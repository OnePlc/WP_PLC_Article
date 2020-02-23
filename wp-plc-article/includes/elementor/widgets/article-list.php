<?php

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

class WPPLC_Article_List extends Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcarticlelist';
    }

    public function get_title() {
        return __('Article List', 'wp-plc-article');
    }

    public function get_icon() {
        return 'fa fa-list';
    }

    public function get_categories() {
        return ['wp-plc-article'];
    }

    protected function render()
    {
        # Get Elementor Widgets Settings
        $aSettings = $this->get_settings_for_display();


        # Get Article ID
        $aParams = ['listmode' => 'entity'];
        $sLang = '';
        $iPage = (int)get_query_var('list_page_id');
        $iBaseCat = (int)get_query_var('list_base_category');
        $aSettings['list_base_category'] = $iBaseCat;
        if (defined('ICL_LANGUAGE_CODE')) {
            if (ICL_LANGUAGE_CODE == 'en') {
                $sLang = 'en_US';
            }
            if (ICL_LANGUAGE_CODE == 'de') {
                $sLang = 'de_DE';
            }
            $aParams['lang'] = $sLang;
        }
        if($iPage == 0) {
            $iPage = 1;
        }

        $sSliderID = \Elementor\Utils::generate_random_string();

        if($iBaseCat != 0) {
            $aParams['listmodefilter'] = 'webonly';
            $aParams['filter'] = 'category';
            $aParams['filtervalue'] = $iBaseCat;
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

            require_once(__DIR__ . '/../../view/partials/article-list.php');
        } else {
            echo 'error loading articles';
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * GENERAL SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Get Fields from onePlace
        $aFields = [];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/getfields/0');
        if(is_object($oAPIResponse)) {
            if($oAPIResponse->state == 'success') {
                $aFieldsAPI = (array)$oAPIResponse->aFields;
                if(count($aFieldsAPI) > 0) {
                    foreach($aFieldsAPI as $oField) {
                        $aFields[$oField->fieldkey] = $oField->label;
                    }
                }
            }
        }

        # Section - Start
        $this->start_controls_section(
            'section_listview_title',
            [
                'label' => __('List View - Title', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'listview_title_template',
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
            'section_listview_fields',
            [
                'label' => __('List View - Fields', 'wp-plc-article'),
            ]
        );

        $this->add_control(
            'listview_featured_fields',
            [
                'label' => __( 'Fields', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $aFields,
            ]
        );

        $this->add_control(
            'listview_hide_emptyfields',
            [
                'label' => __( 'Hide empty fields', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Hide', 'wp-plc-article' ),
                'label_off' => __( 'Show', 'wp-plc-article' ),
                'return_value' => 'yes',
                'default' => 'no',
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
            'list_view_button_1',
            [
                'label' => __('List View Item - Button 1', 'wp-plc-article'),
            ]
        );

        // Button Text
        $this->add_control(
            'list_view_button_1_text',
            [
                'label' => __('Text', 'wp-plc-article'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Read more', 'wp-plc-article'),
                'placeholder' => __('Read more', 'wp-plc-article'),
            ]
        );

        $sSingleViewSlug = '/'.get_option('plcarticle_singleview_slug');
        $this->add_control(
            'list_view_button_1_link',
            [
                'label' => __( 'Link', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'wp-plc-article' ),
                'show_external' => true,
                'description'=>'You can use placeholders in your links<br/>##ID## List Item ID <br/>##title## List Item Title',
                'default' => [
                    'url' => $sSingleViewSlug.'/##ID##',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button Icon
        $this->add_control(
            'list_view_button_1_icon',
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
            'list_view_button_2',
            [
                'label' => __('List View Item - Button 2', 'wp-plc-article'),
            ]
        );

        // Button Text
        $this->add_control(
            'list_view_button_2_text',
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

        $this->add_control(
            'list_view_button_2_link',
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
            'list_view_button_2_icon',
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
         * STYLE SETTINGS - LIST - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'list_view_list_item_settings',
            [
                'label' => __('List View - List Item', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'list_item_border',
                'selector' => '{{WRAPPER}} .plc-article-list li',
            ]
        );
        $this->add_control(
            'list_item_border_radius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .plc-article-list li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'list_item_box_shadow',
                'selector' => '{{WRAPPER}} .plc-article-list li',
            ]
        );
        $this->add_responsive_control(
            'list_item_text_padding',
            [
                'label' => __('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .plc-article-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        # Section - End
        $this->end_controls_section();

        /**
         * STYLE SETTINGS - LIST - END
         * @since 1.0.0
         */

        /**
         * STYLE SETTINGS - TITLE - START
         * @since 1.0.0
         */
        # Section - Start
        $this->start_controls_section(
            'list_view_title_settings',
            [
                'label' => __('List View Item - Title', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Title Color
        $this->add_control(
            'list_view_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-article-list li h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_view_title_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-article-list li h3',
            ]
        );

        # Slide Title Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'list_view_title_shadow',
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
            'list_view_attr_left_settings',
            [
                'label' => __('List View Item - Attribute Labels', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'list_view_attr_left_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-list-attribute-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_view_attr_left_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-list-attribute-label',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'list_view_attr_left_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-list-attribute-label',
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
            'list_view_attr_right_settings',
            [
                'label' => __('List View Item - Attribute Values', 'wp-plc-article'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        # Slide Attributes Left Color
        $this->add_control(
            'list_view_attr_right_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-article' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-list-attribute-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        # Slide Attributes Left Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_view_attr_right_typo',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-list-attribute-value',
            ]
        );

        # Slide Attributes Left Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'list_view_attr_right_shadow',
                'label' => __( 'Text Shadow', 'wp-plc-article' ),
                'selector' => '{{WRAPPER}} .plc-list-attribute-value',
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
            'list_view_buttons_style',
            [
                'label' => __( 'List View Item - Buttons', 'wp-plc-article' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'list_view_buttons_typo',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-list-button',
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
            'list_view_button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_view_button_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'list_view_tab_button_hover',
            [
                'label' => __( 'Hover', 'wp-plc-article' ),
            ]
        );

        $this->add_control(
            'list_view_button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_viewbutton_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_view_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_view_button_hover_animation',
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
                'name' => 'list_view_buttons_border',
                'selector' => '{{WRAPPER}} .plc-list-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'list_view_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'list_view_button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-list-button',
            ]
        );
        $this->add_responsive_control(
            'list_view_button_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-article' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-list-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
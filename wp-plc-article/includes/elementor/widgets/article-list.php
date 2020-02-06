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
        return 'fa fa-images';
    }

    public function get_categories() {
        return ['wp-plc-article'];
    }

    protected function render() {
        # Get Elementor Widgets Settings
        $aSettings = $this->get_settings_for_display();

        # Get Article ID
        //$iArticleID = get_query_var('article_id');

        # Get Articles from onePlace API
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0', ['listmode'=>'entity']);

        if ($oAPIResponse->state == 'success') {
            # get items
            $aItems = $oAPIResponse->results;

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
                include __DIR__ . '/../../view/partials/article-list.php';
            } else {
                echo 'error loading fields';
            }
        } else {
            echo 'Error: ' . $oAPIResponse->message;
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
    }
}
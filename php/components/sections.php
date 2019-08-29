<?php

if (!class_exists('Section')) {

    $class_section_section_fields = array(
        'background-image' => new CustomFieldOption(array('name'=>'background-image', 'label'=> __ ( 'Background image' ), 'type'=>'image', 'default_value' => '', 'key'=>'background-image')),
        'with_background' => new CustomFieldOption(array('name'=>'with_background', 'label'=> __ ( 'with_background' ), 'type'=>'true_false', 'default_value' => '', 'key'=>'with_background')),
        'not_display_in_mobile' => new CustomFieldOption(array('name'=>'not_display_in_mobile', 'label'=> __ ( 'Hidden on the mobile devices' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'not_display_in_mobile'))
    );

    $class_section_section_option_default = array(
        'section_fields' => array(),
        'section_additional_class' => '',
        'background-image' => '',
        'with_background' => false,
        'not_display_in_mobile' => false
    );

    class Section {

        protected $cfg;
        protected $section_fields = array();

        public $data = array();
        public $section_option = array();

        function __construct($section_option = array(), $data = array(), $section_fields = array()) {

            $data_default = array();

            global $class_section_section_option_default;
            global $class_section_section_fields;

            $this->section_fields = array_merge($class_section_section_fields, $section_fields);

            $section_option_fields = array_merge($class_section_section_option_default, $section_option);
            foreach ($this->section_fields as $field) {
                if($field instanceof CustomFieldOption) {
                    $section_option_fields[$field->name] = $field->default_value;
                } elseif ($field instanceof CustomField) {
                    $field_value = '';
                    $acf = get_field_object($field->name);
                    if ($acf) {
                        $field_value = get_field($field->name);
                    }
                    $data_default[$field->name] = $field_value;
                }
            }

            foreach ($section_option_fields as $option_field_name => $value) {
                $this->section_option[$option_field_name] = $value;

                if(is_array($value)) {
                    continue;
                }
                $option_field = get_field_object($option_field_name);

                if (!$option_field) {
                    continue;
                }

                $this->section_option[$option_field_name] = get_field($option_field_name);
            }

            $this->data = array_merge($data_default, $data);
        }

        public static function html_wrap_to_section($html_input, $section_option = array()) {
            global $class_section_section_option_default;

            $section_option = array_merge($class_section_section_option_default, $section_option);

            $html = '
                <section class="section-block {{section_additional_class}}" style="{{section_style_background}}">
        
                    <div class="container">
                    
                        {{html_input}}
                    
                    </div>
        
                </section>                        
            ';
            $section_additional_class = $section_option['section_additional_class'];
            if ($section_option['with_background']) {
                $section_additional_class .= ' section_with_background ';
            }
            if (wp_is_mobile() && $section_option['not_display_in_mobile']) {
                $section_additional_class .= ' is-hidden-mobile ';
            }
            if(strpos($_SERVER['REQUEST_URI'], 'our-projects') !== false && wp_is_mobile()) {
                $section_additional_class .= ' our-projects-block ';
            }
            $section_style_background = '';
            if($section_option['background-image'] != '') {
                $section_style_background = '
                    background-image: url(' .$section_option['background-image']. ');
                    background-size: cover;
                    background-repeat: no-repeat;
                    min-height: 400px;                
                ';
            }

            $html = str_replace('{{section_style_background}}', $section_style_background, $html);
            $html = str_replace('{{section_additional_class}}', $section_additional_class, $html);
            $html = str_replace('{{html_input}}', $html_input, $html);

            return $html;
        }

        public static function block_render_callback() {
            echo '';
        }

        public function acf_build_field() {
            if(is_null($this->cfg)) {
                return;
            }

            $this->cfg->addField(new CustomFieldDecoration(array('name'=>'data', 'label'=> __ ( $this->get_block_name() ), 'type'=>'tab', 'key'=>'data')));
            foreach ($this->section_fields as $field) {
                if(($field instanceof CustomFieldOption)) {

                } else {
                    $this->cfg->addField($field);
                }
            }
            $this->cfg->addField(new CustomFieldDecoration(array('name'=>'option', 'label'=> __ ( 'Option' ), 'type'=>'tab', 'key'=>'option')));
            foreach ($this->section_fields as $field) {
                if($field instanceof CustomFieldOption) {
                    $this->cfg->addField($field);
                }
            }

            $this->cfg->setLocation('block', '==', 'acf/' . str_replace ('_', '-', $this->get_block_name()));
            $this->cfg->build();
        }

        public function register_block($block_name = '', $args = array()) {
            global $theme_spg;

            $section_class_name = str_replace('Class', '', get_class($this));
            if($block_name == '') {
                $block_name = $this->get_block_name();
            }

            $args_default = array(
                'name'				=> $block_name,
                'title'				=> __($block_name ),
                'description'		=> __($block_name . ' block.'),
                'render_callback'	=> $section_class_name . '::block_render_callback',
                'category'			=> $theme_spg->acf_prefix,
                'icon'				=> 'admin-comments',
                'keywords'			=> array( $block_name, 'quote' ),
            );

            if (function_exists('acf_register_block')) {

                acf_register_block(array_merge($args_default, $args));

            }

        }

        public function get_block_name() {
            global $theme_spg;

            $section_class_name = str_replace('Class', '', get_class($this));
            $block_name = $theme_spg->acf_prefix .'_'. from_camel_case($section_class_name);

            return $block_name;
        }

    }
}

require_once(__DIR__ . '/section-nav-menu.php');
require_once(__DIR__ . '/SectionPageIntro.php');
require_once(__DIR__ . '/section-page-title.php');
require_once(__DIR__ . '/section-page-banner.php');
require_once(__DIR__ . '/section-page-content.php');

require_once(__DIR__ . '/section-cards-list.php');
require_once(__DIR__ . '/section-testimonials.php');
require_once(__DIR__ . '/section-technologies.php');
require_once(__DIR__ . '/section-our-projects.php');
require_once(__DIR__ . '/section-our-team.php');
require_once(__DIR__ . '/section-our-services.php');
require_once(__DIR__ . '/section-our-industries.php');
require_once(__DIR__ . '/section-contact-form.php');
require_once(__DIR__ . '/section-tweets.php');
require_once(__DIR__ . '/section-contact-us.php');

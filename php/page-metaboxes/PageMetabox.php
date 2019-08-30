<?php

if (!class_exists('PageMetabox')) {

    abstract class PageMetabox
    {
        public $data;
        public $name;

        protected $fields = array();
        protected $cfg;
        protected $location = 'page_template';
        protected $location_value = 'default';

        function __construct( ) {

        }

        public function register_page_metabox() {
            $this->acf_build_field();
        }

        private function acf_build_field() {
            if(is_null($this->cfg)) {
                return;
            }

            foreach ($this->fields as $field) {
                $this->cfg->addField($field);
            }

            $this->cfg->setLocation('page_type', '==', 'front_page');
            $this->cfg->build();
        }

        public function read_data_fields() {

            foreach ($this->fields as $key => $field) {
                $field_value = '';
                $acf = get_field_object($field->name);
                if ($acf) {
                    $field_value = get_field($field->name);
                }
                $this->data[$key] = $field_value;
            }

            return $this;
        }

    }

}

require_once(__DIR__ . '/PageMetaboxHero.php');
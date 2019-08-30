<?php

if (!class_exists('CustomField')) {

    class CustomField
    {

        public $name = '';
        public $label = '';
        public $key = '';
        public $type = '';
        public $display_in_list = false;

        public $default_value;
        public $fields = array();

        public $post_type = array();
        public $taxonomy = '';

        public $choices = array();

        public $args = array();

        function __construct(array $args)
        {
            foreach($args as $key=>$val) {
                $this->$key = $val;
            }
            if ($this->key == '') {
                $this->key = $this->name;
            }
        }

        public function get_value($post_id)
        {
            return get_field( $this->name, $post_id );
        }

    }

}

if (!class_exists('CustomFieldOption')) {

    class CustomFieldOption extends CustomField
    {


    }

}

if (!class_exists('CustomFieldDecoration')) {

    class CustomFieldDecoration extends CustomField
    {


    }

}
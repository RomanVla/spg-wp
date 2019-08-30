<?php
if (!class_exists('CustomFieldsBuilder')) {

    class CustomFieldsBuilder
    {
        private $fieldsBuilder;
        private $customFields = [];

        function __construct($title, $groupConfig)
        {
            $this->fieldsBuilder = new StoutLogic\AcfBuilder\FieldsBuilder($title, $groupConfig);

        }

        public function addField($field) {
            array_push($this->customFields, $field);

            return $this;
        }

        public function setLocation($param, $operator, $value) {
            return $this->fieldsBuilder->setLocation($param, $operator, $value);
        }

        public function build()
        {
            foreach ($this->customFields as $customField) {
                $this->buildField($this->fieldsBuilder, $customField);
            }

            $acf_config = $this->fieldsBuilder->build();

            acf_add_local_field_group($acf_config);
        }

        private function buildField(StoutLogic\AcfBuilder\Builder $fieldsBuilder, $customField) {
            switch ($customField->type) {
                case 'text':
                    $fieldsBuilder->addText($customField->name,       array('key'=>$customField->key));
                    break;
                case 'textarea':
                    $fieldsBuilder->addTextarea($customField->name,       array('key'=>$customField->key));
                    break;
                case 'number':
                    $fieldsBuilder->addNumber($customField->name,       array('key'=>$customField->key));
                    break;
                case 'email':
                    $fieldsBuilder->addEmail($customField->name,       array('key'=>$customField->key));
                    break;
                case 'url':
                    $fieldsBuilder->addUrl($customField->name,       array('key'=>$customField->key));
                    break;
                case 'password':
                    $fieldsBuilder->addPassword($customField->name,       array('key'=>$customField->key));
                    break;
                case 'wysiwyg':
                    $fieldsBuilder->addWysiwyg($customField->name,       array('key'=>$customField->key));
                    break;
                case 'oembed':
                    $fieldsBuilder->addOembed($customField->name,       array('key'=>$customField->key));
                    break;
                case 'image':
                    $fieldsBuilder->addImage($customField->name,       array('key'=>$customField->key, 'return_format'=> 'url'));
                    break;
                case 'file':
                    $fieldsBuilder->addFile($customField->name,       array('key'=>$customField->key, 'return_format'=> 'url'));
                    break;
                case 'true_false':
                    $fieldsBuilder->addTrueFalse($customField->name,       array('key'=>$customField->key));
                    break;
                case 'gallery':
                    $fieldsBuilder->addGallery($customField->name,       array('key'=>$customField->key));
                    break;
                case 'select':
                    $fieldsBuilder->addSelect($customField->name,       array('key'=>$customField->key, 'choices'=>$customField->choices));
                    break;
                case 'radio':
                    $fieldsBuilder->addRadio($customField->name,       array('key'=>$customField->key));
                    break;
                case 'checkbox':
                    $fieldsBuilder->addCheckbox($customField->name,       array('key'=>$customField->key));
                    break;
                case 'button_group':
                    $fieldsBuilder->addButtonGroup($customField->name,       array('key'=>$customField->key));
                    break;
                case 'post_object':
                    $post_type_builder = $fieldsBuilder->addPostObject($customField->name,       array('key'=>$customField->key));
                    $post_type_builder->setConfig('post_type', $customField->post_type);
                    break;
                case 'page_link':
                    $fieldsBuilder->addPageLink($customField->name,       array('key'=>$customField->key, 'return_format'=>'object'));
                    break;
                case 'relationship':
                    $fieldsBuilder->addRelationship($customField->name,       array('key'=>$customField->key));
                    break;
                case 'taxonomy':
                    $fieldsBuilder->addTaxonomy($customField->name,       array('key'=>$customField->key, 'taxonomy'=>$customField->taxonomy, 'field_type'=> 'select', 'return_format'=>'id'));
                    break;
                case 'user':
                    $fieldsBuilder->addUser($customField->name,       array('key'=>$customField->key));
                    break;
                case 'date_picker':
                    $fieldsBuilder->addDatePicker($customField->name,       array('key'=>$customField->key));
                    break;
                case 'time_picker':
                    $fieldsBuilder->addTimePicker($customField->name,       array('key'=>$customField->key));
                    break;
                case 'date_time_picker':
                    $fieldsBuilder->addDateTimePicker($customField->name,       array('key'=>$customField->key));
                    break;
                case 'color_picker':
                    $fieldsBuilder->addColorPicker($customField->name,       array('key'=>$customField->key));
                    break;
                case 'google_map':
                    $fieldsBuilder->addGoogleMap($customField->name,       array('key'=>$customField->key));
                    break;
                case 'link':
                    $fieldsBuilder->addLink($customField->name,       array('key'=>$customField->key));
                    break;
                case 'range':
                    $fieldsBuilder->addRange($customField->name,       array('key'=>$customField->key));
                    break;
                case 'tab':
                    $fieldsBuilder->addTab($customField->name,       array('key'=>$customField->key));
                    break;
                case 'accordion':
                    $fieldsBuilder->addAccordion($customField->name,       array('key'=>$customField->key));
                    break;
                case 'group':
                    $repeater = $fieldsBuilder->addGroup($customField->name,       array('key'=>$customField->key, 'layout' => 'block'));
                    foreach ($customField->fields as $repeaterField) {
                        $this->buildField($repeater, $repeaterField);
                    }
                    $repeater->end();
                    break;
                case 'repeater':
                    $args = array_merge(
                        array(
                            'key'=>$customField->key,
                            'layout' => 'table'),
                        $customField->args);
                    $repeater = $fieldsBuilder->addRepeater($customField->name, $args);
                    foreach ($customField->fields as $repeaterField) {
                        $this->buildField($repeater, $repeaterField);
                    }
                    $repeater->end();
                    break;
                default:
                    break;
            }

        }

        public function setCustomFields($customFields) {
            $this->customFields = $customFields;

            return $this;
        }

        public function getCustomFields() {
            return $this->customFields;
        }

        public function getCustomField($name) {
            $custom_field = null;

            foreach ($this->customFields as $current_custom_field) {
                if ($current_custom_field->name == $name) {
                    $custom_field = $current_custom_field;
                    break;
                }
            }

            return $custom_field;
        }

    }

}
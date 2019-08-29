<?php
/**
 * Create  Employee Post Type
 *
 */

if (!class_exists('EmployeePostType')) {

    class EmployeePostType extends PostType
    {

        function __construct($option = array())
        {
            $object_option = set_array_atts(array(
                'post_type' => PostTypeName::Employee,
            ), $option);

            $cfg = new CustomFieldsBuilder('Post Type: Employee', array(
                'key' => '5ce80dd678b6f',
                'position' => 'acf_after_title'
            ));

            $cfg->
            addField(new CustomField(array('name'=>'photo', 'label'=> __ ( 'Photo' ), 'type'=>'image', 'display_in_list'=>true, 'key'=>'5ce80e3522b4c')))
            ->addField(new CustomField(array('name'=>'position', 'label'=> __ ( 'Position' ), 'type'=>'text', 'display_in_list'=>true, 'key'=>'5ce80e8c22b4d')))
            ->addField(new CustomField(array('name'=>'name', 'label'=> __ ( 'Name' ), 'type'=>'text', 'key'=>'5ce80df022b4b')));

            parent::__construct($object_option, $cfg);
        }

        public function register_post_type()
        {
            global $theme_spg;
            $post_type_name = PostTypeName::Employee;

            $labels = array(
                'name' => _x('Team members', 'Post Type General Name', $theme_spg->domain),
                'menu_name' => __('Team members', $theme_spg->domain)
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_ui' => true,
                'menu_icon' => 'dashicons-groups'
            );

            parent::wp_register_post_type($post_type_name, $args);
        }

        public function get_single_post_html($post_type_object, $option_custom = array()) {

            $employee_html = '
                    <div class="col-lg-3 col-md-3 col-sm-6 {{section_additional_class}}">
                        <div class="p-4">
                            <div>
                                <img class="rounded mx-auto d-block" src="{{employee_photo_url}}" alt="{{employee_name}}" data-no-retina
                                onerror="this.onerror=null;this.src=\'{{employee_default_photo_url}}\';">
                            </div>
            
                            <div class="text-center">
                                {{employee_name}}
                            </div>
                            <div class="text-center">
                                {{employee_position}}
                            </div>
            
                        </div>
                    </div>        
                ';

            $section_additional_class = '';
            $employee_html = str_replace('{{employee_default_photo_url}}', get_resource_path('/img/no-image.png'), $employee_html);
            $employee_html = str_replace('{{employee_photo_url}}', $post_type_object['photo'], $employee_html);
            $employee_html = str_replace('{{employee_name}}', $post_type_object['name'], $employee_html);
            $employee_html = str_replace('{{employee_position}}', $post_type_object['position'], $employee_html);
            $employee_html = str_replace('{{section_additional_class}}', $section_additional_class, $employee_html);

            return $employee_html;
        }

    }

}
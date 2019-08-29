<?php

if (!class_exists('PostType')) {

    abstract class PostType
    {

        protected $cfg;
        public $option;

        function __construct($option, CustomFieldsBuilder $cfg = null)
        {
            $this->option = set_array_atts(array(
                'post_type' => '',
            ), $option);

            $this->cfg = $cfg;
        }

        abstract function register_post_type();

        public function wp_register_post_type($post_type_name, $args)
        {
            $args_default = array(
                'supports'            => array( 'title', 'revisions', 'thumbnail', 'editor', 'excerpt' ),
                'taxonomies'          => array( '' ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
            );

            register_post_type($post_type_name, array_merge($args_default, $args));

            //$post_class_name = str_replace('Class', '', get_class($this));
            add_filter ( 'manage_'.$post_type_name.'_posts_columns', [$this, 'wp_add_post_acf_columns']);
            add_action ( 'manage_'.$post_type_name.'_posts_custom_column', [$this, 'wp_post_custom_column'], 10, 2 );

            // create post type option page
            if (function_exists('acf_add_options_sub_page')) {
                acf_add_options_sub_page(array(
                    'title' => 'Options',
                    'parent' => 'edit.php?post_type=' . $post_type_name,
                    'capability' => 'manage_options'
                ));
            }
        }

        public function wp_add_post_acf_columns ( $columns ) {

            if(is_null($this->cfg)) {
                return $columns;
            }

            $customFields = $this->cfg->getCustomFields();
            foreach ($customFields as $customField) {
                if($customField->display_in_list) {
                    $columns[$customField->name] = $customField->label;
                }
            }
            return $columns;
        }

        public function wp_post_custom_column ( $column, $post_id ) {

            if(is_null($this->cfg)) {
                return '';
            }

            $custom_field = $this->cfg->getCustomField($column);
            if (is_null($custom_field)) {
                return '';
            }

            //call_user_func($post_columns[$column]['get_value'], $column, $post_id)
            echo $custom_field->get_value($post_id);
            return '';
        }

        public function acf_build_field() {
            if(is_null($this->cfg)) {
                return;
            }

            $this->cfg->setLocation('post_type', '==', $this->option['post_type']);
            $this->cfg->build();
        }

        public function get_posts()
        {
            return self::wp_get_posts();
        }

        public function get_posts_by_term($term, $args = array()) {

            $taxonomy_name = $term['term_taxonomy'];

            $args_default = array(
                'numberposts' => 3,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'id',
                        'terms' => $term['term_id'], // Where term_id of Term 1 is "1".
                        'include_children' => true
                    )
                )
            );

            return self::wp_get_posts(array_merge($args_default, $args));
        }

        public function get_postById($post_id)
        {
            return  self::wp_get_post_additional_data(get_post($post_id));
        }

        public function get_single_post_html($post_type_object, $option_custom = array()) {

            $option_default = array(
                'page_title_size' => '2'
            );
            $option_current = array_merge($option_default, $option_custom);

            $post_html = '
                <div class="row m-4">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h{{page_title_size}} class="is-size-2 is-size-4-mobile"> {{post_title}} </h{{page_title_size}}>
                    
                        <div class="is-size-6"> {{post_content}} </div>
                        
                    </div>
                </div>             
            ';

            $post_html = str_replace('{{post_link}}', $post_type_object['post_link'], $post_html);
            $post_html = str_replace('{{post_title}}', $post_type_object['post_title'], $post_html);
            $post_html = str_replace('{{post_excerpt}}', $post_type_object['post_excerpt'], $post_html);
            $post_html = str_replace('{{post_content}}', $post_type_object['post_content'], $post_html);
            $post_html = str_replace('{{page_title_size}}', $option_current['page_title_size'], $post_html);

            return $post_html;
        }

        private function wp_get_posts($args = array())
        {
            $args_default = array(
                'post_type' => $this->option['post_type'],
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'ASC'
            );

            $wp_posts = [];

            $posts = get_posts(array_merge($args_default, $args));
            foreach ($posts as $post) {
                $wp_posts[] = self::wp_get_post_additional_data($post);
            }

            return $wp_posts;
        }

        private function wp_get_post_additional_data($post) {

            $wp_post = get_fields($post->ID);
            $wp_post['post_id'] = $post->ID;
            $wp_post['post_link'] = get_permalink($post->ID);
            $wp_post['post_title'] = $post->post_title;
            $wp_post['post_content'] = $post->post_content;
            $wp_post['post_excerpt'] = get_the_excerpt($post->ID);

            return  $wp_post;
        }
    }

}

require_once(__DIR__ . '/spg-post-type-employee.php');
require_once(__DIR__ . '/spg-post-type-project.php');
require_once(__DIR__ . '/spg-post-type-testimonial.php');
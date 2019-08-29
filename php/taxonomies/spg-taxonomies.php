<?php

if (!class_exists('Taxonomy')) {

    abstract class Taxonomy
    {

        protected $option = array();

        function __construct($option)
        {
            $this->option = set_array_atts(array(
                'taxonomy' => '',
                'associated_object' => array()
            ), $option);

        }

        abstract function register_taxonomy($associated_object, $args = array());

        public static function get_taxonomy_permalink($taxonomy_name)
        {

            $taxonomy_permalink = "";

            $terms = get_terms([
                'taxonomy' => $taxonomy_name,
                'hide_empty' => false,
            ]);

            if (count($terms) > 0) {
                $url = explode('/', get_term_link($terms[0], $taxonomy_name));
                if(end($url) == '') {
                    array_splice($url, -1);
                }
                array_splice($url, -1);
                $taxonomy_permalink = implode('/', $url);
            }

            return $taxonomy_permalink;
        }

        public function wp_register_taxonomy($args) {
            $args_default = array(
                'public' => true,
                'show_ui' => true,
                'hierarchical' => true,
                'menu_icon' => 'dashicons-groups',
                'show_admin_column' => true,
                'query_var'    => true
            );

            register_taxonomy($this->option['taxonomy'], $this->option['associated_object'], array_merge($args_default, $args));
        }

        public function get_term_by_id($term_id) {

            return  self::wp_get_term_additional_data(get_term($term_id));

        }

        private function wp_get_term_additional_data($wp_term) {
            $taxonomy = $wp_term->taxonomy;

            $term['term_id'] = $wp_term->term_id;
            $term['term_taxonomy'] = $taxonomy;
            $term['term_link'] = get_term_link($wp_term->term_id, $taxonomy);
            $term['term_title'] = $wp_term->name;
            $term['term_excerpt'] = $wp_term->description;
            $term['image'] =  get_field('image', $wp_term);
            $term['content'] =  get_field('content', $wp_term);

            return  $term;

        }

        public function get_term_section_html($term, $option_custom = array()) {

            $option_default = array(
                'display_sub_terms' => true,
                'display_sub_terms_per_row' => 3,
                'term_section_html_template' => $this->get_term_section_html_template()
            );
            $option = array_merge($option_default, $option_custom);

            if(!$option['display_sub_terms']) {
                $term_section_html_template = $this->get_term_html($term, $this->get_term_children_section_html_template(), $option);

            } else {
                $term_section_html_template = $this->get_term_html($term, $option['term_section_html_template'], $option);
            }

            return $term_section_html_template;
        }

        private function get_term_children_section_html($term_parent, $option) {

            $term_children_section_html_template = '';

            $taxonomy = $term_parent['term_taxonomy'];

            $children_terms = get_term_children( $term_parent['term_id'], $taxonomy );
            foreach ($children_terms as $term_id) {

                $term = $this->get_term_by_id( $term_id);

                $term_children_section_html_template .= $this->get_term_html($term, $this->get_term_children_section_html_template(), $option);
            }

            return '<div class="row">' . $term_children_section_html_template . '</div>';
        }

        private function get_term_html($term, $term_html_template, $option) {
            $term_html = $term_html_template;

            $coll_class = '';
            if ($option['display_sub_terms_per_row'] == 2) {
                $coll_class = 'col-lg-6 col-md-6';
            } elseif ($option['display_sub_terms_per_row'] == 3) {
                $coll_class = 'col-lg-4 col-md-4';
            }

            $term_html = str_replace('{{$coll_class}}', $coll_class, $term_html);
            $term_html = str_replace('{{term_link}}',$term['term_link'], $term_html);
            $term_html = str_replace('{{term_title}}', $term['term_title'], $term_html);
            $term_html = str_replace('{{term_description}}', $term['term_excerpt'], $term_html);
            $term_html = str_replace('{{term_image}}', $term['image'], $term_html);
            $term_html = str_replace('{{sub_term_section_html}}', $this->get_term_children_section_html($term, $option), $term_html);

            return $term_html;
        }

        private function get_term_section_html_template() {
            $html =  '
                <div class="term-section text-justify">
                
                    <h1 class="is-size-1 is-size-4-mobile"> {{term_title}} </h1>
                    <div class="is-size-6"> {{term_description}} </div>
                
                </div>                
                {{sub_term_section_html}}
            ';

            return Section::html_wrap_to_section(
                $html
            );

        }

        private function get_term_children_section_html_template() {
            return '
                <div class="row-eq-height {{$coll_class}} col-sm-12 p-4">            
                    
                    <div class="card mb-3 w-100">
                        <div class="row no-gutters">
                            <div class="col-md-4" style="margin: 80px 0;">
                                <img src="{{term_image}}" class="card-img" alt="{{term_title}}">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h2 class="card-title is-size-5-desktop"> <a href="{{term_link}}">{{term_title}}</a> </h2>
                                    <p class="card-text"> {{term_description}} </p>
                                    <p class="card-text"> <small class="text-muted"> <a href="{{term_link}}"> see more...</a> </small></p>
                                </div>                    
                            </div>
                        </div>
                    </div>
                    
                </div>';
        }

    }

}

require_once(__DIR__ . '/spg-taxonomy-industry.php');
require_once(__DIR__ . '/spg-taxonomy-services.php');
require_once(__DIR__ . '/spg-taxonomy-technologies.php');

<?php
/**
 * Create  Project Post Type
 *
 */

if (!class_exists('ProjectPostType')) {

    class ProjectPostType extends PostType
    {

        function __construct($option = array())
        {
            $object_option = set_array_atts(array(
                'post_type' => PostTypeName::Project,
            ), $option);

            parent::__construct($object_option);
        }

        public function register_post_type()
        {
            global $theme_spg;

            $post_type_name = PostTypeName::Project;

            $labels = array(
                'name' => _x('Our projects', 'Post Type General Name', $theme_spg->domain),
                'menu_name' => __('Case studies', $theme_spg->domain),
                'all_items' => __('Projects', $theme_spg->domain)
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_ui' => true,
                'menu_icon' => 'dashicons-lightbulb',
                'capability_type'     => 'page',
                'query_var'             => true
            );

            parent::wp_register_post_type($post_type_name, $args);
        }

        public function get_single_post_html($post_type_object, $option_custom = array()) {

            return $this->get_project_description_html($post_type_object, $option_custom);

        }

        public function get_project_description_html($project, $option_custom = array()) {

            if($project == null) {
                return '';
            }

            $option_default = array(
                'page_title_size' => '2',
                'reverse_order' => false,
                'short_template' => false
            );
            $option = array_merge($option_default, $option_custom);

            $project_html = $this->html_template_post_description();
            if($option['short_template']) {
                $project_html = $this->html_template_post_short_description();
                if($option['show_as_cards']) {
                    $project_html = $this->html_template_post_short_description_card();
                }
            }

            $project_url_html = '';
            if($project['url'] != '') {
                $project_url_html = '
                <div style="padding: .625rem 0;">
                    <i class="fa fa-globe"></i>
                    <a target="_blank" href="{{project_url}}" rel="nofollow">{{project_url_path}}</a>
                </div>
                ';
                $project_url_html = str_replace('{{project_url}}', $project['url'], $project_url_html);
                $project_url_html = str_replace('{{project_url_path}}', parse_url($project['url'], PHP_URL_HOST), $project_url_html);
            }

            $reverse_order_class = '';
            if($option['reverse_order']) {
                $reverse_order_class = 'order-1';
            }

            $project_html = str_replace('{{post_link}}', $project['post_link'], $project_html);
            $project_html = str_replace('{{project_url_html}}', $project_url_html, $project_html);
            $project_html = str_replace('{{project_name}}', $project['name'], $project_html);
            $project_html = str_replace('{{post_excerpt}}', $project['post_excerpt'], $project_html);
            $project_html = str_replace('{{project_description}}', $project['description'], $project_html);
            $project_html = str_replace('{{project_image}}', $project['image'], $project_html);
            $project_html = str_replace('{{reverse_order_class}}', $reverse_order_class, $project_html);
            $project_html = str_replace('{{page_title_size}}', $option['page_title_size'], $project_html);
            return $project_html;

        }

        private function html_template_post_description() {
            return '
            <div class="col-12 project-block p-0" style="margin-bottom: 0;">
                <h{{page_title_size}} class="is-size-2 is-size-4-mobile"> {{project_name}} </h{{page_title_size}}>
            </div>
            <div class="col-12 p-0">
                <div class="row project-block d-flex">
                    <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12 col-xs-12 projects-align{{reverse_order_class}}">
                    
                            {{project_url_html}}
                    
                        <div class="is-size-6 text-justify"> {{project_description}} </div>
                        
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-xs-12 projects-align" style="padding-top: 5px;">
                        
                        <img class="img-fluid img-portfolio" alt="{{project_name}}" src="{{project_image}}" data-retinable="">
    
                    </div> 
                </div>             
            </div>
            ';
        }

        private function html_template_post_short_description() {
            return '
            <div class="col-12 project-block p-0" style="margin-bottom: 0;">
                <h{{page_title_size}} class="is-size-2 is-size-4-mobile"> {{project_name}} </h{{page_title_size}}>
            </div>
            <div class="col-12 p-0">
                <div class="row project-block d-flex">
                
                    <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12 col-xs-12 projects-align {{reverse_order_class}}">
                            {{project_url_html}}
                    
                        <div class="is-size-6 text-justify"> {{post_excerpt}} </div>
                        
                        <div class="is-size-6">  <a href="{{post_link}}"> See more...</a> </div>
                        
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-xs-12 projects-align" style="padding-top: 5px;">
                        
                        <a href="{{post_link}}"><img class="img-fluid img-portfolio" alt="{{project_name}}" src="{{project_image}}" data-retinable=""></a>
    
                    </div> 
                </div>             
            </div>
            ';
        }

        private function html_template_post_short_description_card() {
            return '
                <div class="row-eq-height col-lg-4 col-md-4 col-sm-12 p-3">            
                    
                    <div class="card mb-3" style=" flex-grow: 1;">
                        <div>
                            <img src="{{project_image}}" class="card-img" alt="{{project_name}}">
                        </div>
                        <div class="row no-gutters">
                            <div class="col">
                                <div class="card-body">
                                    <h2 class="card-title is-size-5-desktop"> <a href="{{post_link}}">{{project_name}}</a> </h2>
                                    <p class="card-text"> {{post_excerpt}} </p>
                                    <p class="card-text"> <small class="text-muted"> <a href="{{post_link}}"> see more...</a> </small></p>
                                </div>                    
                            </div>
                        </div>
                    </div>
                    
                </div>
                ';
        }

    }

}

<?php

// Register Section CardsList

if (!class_exists('SectionTechnologiesOverview')) {

    class SectionTechnologiesOverview extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'used_technologies_title' => new CustomField(array('name'=>'used_technologies_title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'5d1f732e15970')),
                'used_technologies_text' => new CustomField(array('name'=>'used_technologies_text', 'label'=> __ ( 'Text' ), 'type'=>'wysiwyg', 'key'=>'5d1f732e15d4a')),
                'used_technologies' => new CustomField(array('name'=>'used_technologies', 'label'=> __ ( 'Technologies' ), 'type'=>'repeater', 'key'=>'5d1f732e16133', 'fields' => array(
                    'taxonomy_technology' => new CustomField(array('name'=>'taxonomy_technology', 'label'=> __ ( 'Technology' ), 'type'=>'taxonomy', 'taxonomy'=>'Technologies','key'=>'5d1f732e3a7da')),
                ))),
                'technologies_show_section_title' => new CustomFieldOption(array('name'=>'technologies_show_section_title', 'label'=> __ ( 'Show section title' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'technologies_show_section_title')),
                'show_as_tile' => new CustomFieldOption(array('name'=>'show_as_tile', 'label'=> __ ( 'Show as tile' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_as_tile')),
                'show_projects' => new CustomFieldOption(array('name'=>'show_projects', 'label'=> __ ( 'Show projects' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_projects')),
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Our technologies', array(
                'key' => '5d25e0ff5dfa9'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {
            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = '';

            $terms = array();
            $technologiesTaxonomy = new TechnologiesTaxonomy();

            foreach ($this->data['used_technologies'] as $our_term) {
                $term = $technologiesTaxonomy->get_term_by_id($our_term['taxonomy_technology']);
                array_push($terms, $term);
            }

            if(count($terms) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($terms), $this->section_option);
            }

            return $html;
        }

        private function html_template($terms) {

            $technologies_html = '';
            if($this->section_option['show_as_tile']) {
                $technologies_html .= $this->html_template_technologies_as_tile($terms);
            } else {
                $technologies_html .= $this->html_template_technologies($terms);
            }

            $title_html = '';
            if($this->section_option['technologies_show_section_title']) {
                $title_html = '
                    <div class="col-12">
                        <h2 class="is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{title}} </h2>
                    </div>
                    
                    <div class="col-12 is-size-6">
                        {{description}}
                    </div>            
                ';
            }

            $html = '
                <div class="row">
                    
                    {{title_html}}                        
                    
                    <div class="col-12 is-size-6">                                                        
                        {{technologies_html}}                             
                    </div>    
                    
                    <div class="col-12 py-4 has-text-right has-text-centered-mobile">
                        <a id="projects-info" class="btn btn-dark rounded-pill" href="{{page_our_projects_link}}" role="button"> More information about our projects </a>            
                    </div>
                        
                </div>';

            $html = str_replace('{{title_html}}', $title_html, $html);
            $html = str_replace('{{technologies_html}}', $technologies_html, $html);
            $html = str_replace('{{title}}', $this->data['used_technologies_title'], $html);
            $html = str_replace('{{description}}', $this->data['used_technologies_text'], $html);

            $html = str_replace('{{page_our_projects_link}}', home_url( '/what-we-do' ), $html);


            return $html;
        }

        private function html_template_technologies($terms) {

            $html = '';

            $technologies_taxonomy = new TechnologiesTaxonomy();

            foreach ($terms as $term) {
                $technology_html = $technologies_taxonomy->get_term_section_html($term, array(
                    'display_sub_terms' => true
                ));

                $technology_html = '<div class="col-12 is-size-6">' .$technology_html. '</div>';
                $projects_html = '<div class="col-12 is-size-6">' .$this->html_template_technologies_added_projects($term). '</div>';

                $html .= '<div class="row">' . $technology_html . $projects_html . '</div>';

            }

            return $html;

        }

        private function html_template_technologies_as_tile($terms) {

            $html = '';

            foreach ($terms as $term) {
                $html .= '
                    <div class="col-4 col-lg-2 col-xl-2 col-md-2 col-sm-4 py-2"> 
                        <div class="text-center">
                            <a href="{{term_link}}"><img src="{{term_image}}" alt="{{term_title}}" data-no-retina class="img-fluid"></a>
                        </div>
                        <div class="text-center is-size-7-mobile is-size-6-desktop">{{term_title}}</div>
                    </div>
                    ';

                $html = str_replace('{{term_title}}', $term['term_title'], $html);
                $html = str_replace('{{term_image}}', $term['image'], $html);
                $html = str_replace('{{term_link}}', $term['term_link'], $html);
            }
            $html = '<div class="row justify-content-center">' .$html. '</div>';

            return $html;

        }

        private function html_template_technologies_added_projects($term) {

            $html = '';

            if(!$this->section_option['show_projects']) {
                return $html;
            }

            $projectPostType = new ProjectPostType();
            $sectionOurProjects = new SectionOurProjects(
                array(),
                array(
                    'show_section_title' => true,
                    'short_template' => true,
                    'show_as_cards' => true
                )
            );

            return '<div class="row">' .$sectionOurProjects->get_html_section($projectPostType->get_posts_by_term($term)). '</div>';

        }
    }

}

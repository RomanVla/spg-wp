<?php

// Register Section OurServices

if (!class_exists('SectionOurIndustries')) {

    class SectionOurIndustries extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'industries' => new CustomField(array('name'=>'industries', 'label'=> __ ( 'Industries' ), 'type'=>'repeater', 'key'=>'5d2492e3a42a5', 'fields' => array(
                    'industry' => new CustomField(array('name'=>'industry', 'label'=> __ ( 'Industry' ), 'type'=>'taxonomy', 'taxonomy'=>'Industry','key'=>'5d2492e3b587f')),
                )))
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Our Industries', array(
                'key' => '5d2492e392cd8'
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
            $taxonomyService = new TechnologiesTaxonomy();
            foreach ($this->data['industries'] as $our_term) {
                $term = $taxonomyService->get_term_by_id($our_term['industry']);
                array_push($terms, $term);

            }

            if(count($terms) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($terms), $this->section_option);
            }

            return $html;
        }

        private function html_template($terms) {
            $html = '';

            $taxonomyService = new TechnologiesTaxonomy();
            $sectionOurProjects = new SectionOurProjects(
                array(),
                array(
                    'show_section_title' => true,
                    'short_template' => true,
                    'show_as_cards' => true
                )
            );
            $projectPostTypeService = new ProjectPostType();

            foreach ($terms as $term) {

                $html .= '
                    <div class="row">'
                        .$taxonomyService->get_term_section_html($term, array(
                        'display_sub_terms' => true
                    )).
                    '</div>';

                $html .= '<div class="row">' .$sectionOurProjects->get_html_section($projectPostTypeService->get_posts_by_term($term)). '</div>';
            }

            return $html;
        }

    }

}

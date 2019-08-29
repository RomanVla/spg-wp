<?php

if (!class_exists('SectionOurProjects')) {

    class SectionOurProjects extends Section
    {
        function __construct($data = array(), $section_option = array())
        {

            $section_fields = array(
                'projects' => new CustomField(array('name'=>'projects', 'label'=> __ ( 'Projects' ), 'type'=>'repeater', 'key'=>'5d235d6c6b468', 'fields' => array(
                    'project' => new CustomField(array('name'=>'project', 'label'=> __ ( 'Project' ), 'type'=>'post_object', 'post_type'=>'project','key'=>'5d235d6c74a26')),
                ))),
                'show_section_title' => new CustomFieldOption(array('name'=>'show_section_title', 'label'=> __ ( 'Show section title' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_section_title')),
                'short_template' => new CustomFieldOption(array('name'=>'short_template', 'label'=> __ ( 'Short template' ), 'type'=>'true_false', 'default_value' => true, 'key'=>'short_template')),
                'show_as_cards' => new CustomFieldOption(array('name'=>'show_as_cards', 'label'=> __ ( 'Show as cards' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_as_cards')),
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Our Project', array(
                'key' => '5d235d6c53ec6'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {
            $section = new static();
            $projects = array();
            $projectPostType = new ProjectPostType();
            foreach ($section->data['projects'] as $project_post) {
                $project = $projectPostType->get_postById($project_post['project']->ID);
                array_push($projects, $project);
            }
            echo $section->get_html_section($projects);
        }

        public function get_html_section($projects = null) {
            $html = '';

            if( !is_array($projects) && $projects == null) {
                $projects = array();
                $projectPostType = new ProjectPostType();
                foreach ($this->data['projects'] as $project_post) {
                    $project = $projectPostType->get_postById($project_post['post_id']);
                    array_push($projects, $project);
                }
            }

            if(count($projects) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($projects), $this->section_option);
            }

            return $html;
        }

        private function html_template($projects) {

            $html_title = '';
            if($this->section_option['show_section_title']) {

                $sectionPageTitle = new SectionPageTitle(
                    array(
                        'page_title_size' => '4'
                    ),
                    array(
                        'page_title' => 'Our Projects:',
                        'page_description' => ''
                    )
                );

                $html_section_title = $sectionPageTitle->get_html_section();

                $html_title .=  $html_section_title;

            }

            $reverse_order = false;
            $html_projects = '';
            $projectPostType = new ProjectPostType();
            foreach ($projects as $project) {
                $single_post_section_option = array_merge(array('reverse_order'=> $reverse_order), $this->section_option);
                $html_projects .= $projectPostType->get_single_post_html($project, $single_post_section_option);
                //$reverse_order = !$reverse_order;
            }
            $html_projects = '
                <div class="row">'
                .$html_projects. '
                </div>';
            if(strpos($_SERVER['REQUEST_URI'], 'our-projects')) {
                $post_type_archive_link = home_url( '/our-projects' );
            } else {
                $post_type_archive_link = get_post_type_archive_link($projectPostType->option['post_type']);
            }

            $post_type_label = get_post_type_object($projectPostType->option['post_type'])->label;
            $html_suffix = '    
                    <div class="row p-4">
                        <div style="padding: .625rem 0;">
                            <a href="' .$post_type_archive_link. '" rel="nofollow">See more ' .$post_type_label. '...</a>
                        </div>
                    </div>
            ';

            return $html_title. $html_projects . $html_suffix;

        }

        public function get_html_section_by_term($term) {

            $projectPostType = new ProjectPostType();
            $this->data['projects'] = $projectPostType->get_posts_by_term($term);

            return $this->get_html_section();

        }

    }

}

<?php

// Register Section SectionOutTeam

if (!class_exists('SectionOurTeam')) {

    class SectionOurTeam extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'title')),
                'team_members' => new CustomField(array('name'=>'team_members', 'label'=> __ ( 'Team members' ), 'type'=>'repeater', 'key'=>'5d2331e47771d', 'fields' => array(
                    'employee' => new CustomField(array('name'=>'employee', 'label'=> __ ( 'Employee' ), 'type'=>'post_object', 'post_type'=>'employee','key'=>'5d23445e57cce')),
                )))
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Our Team', array(
                'key' => '5d2331a6023f3'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {
            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = '';

            $teams = array();
            $teamPostType = new EmployeePostType();
            foreach ($this->data['team_members'] as $team_post) {
                $team = $teamPostType->get_postById($team_post['employee']->ID);
                array_push($teams, $team);

            }
            if(count($teams) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($teams), $this->section_option);
            }

            return $html;
        }

        private function html_template($team_posts)
        {
            $employees_html = '';

            $teamPostType = new EmployeePostType();
            foreach ($team_posts as $employee) {

                $employees_html .= $teamPostType->get_single_post_html($employee, $this->section_option);

            }

            $html = '
                <h2 class="is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{section_title}} </h2>
            
                <div class="row is-size-6 py-2">
            
                    {{team_html}}
            
                </div>
            
                <div class="is-size-6 py-2 is-hidden-tablet">
                    <div class="text-center">
                        And eighty more...
                    </div>
                </div>
            
                <div class="is-size-6 py-2 is-hidden-mobile">
                    <div class="text-center">
                        And another 60+ great people!
                    </div>
                </div>    
            ';

            $html = str_replace('{{section_title}}', $this->data['title'], $html);
            $html = str_replace('{{team_html}}', $employees_html, $html);

            return $html;
        }

    }

}

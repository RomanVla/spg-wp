<?php

// Register Section OurServices

if (!class_exists('SectionOurServices')) {

    class SectionOurServices extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'5d244305e7fec')),
                'description' => new CustomField(array('name'=>'description', 'label'=> __ ( 'Description' ), 'type'=>'text', 'key'=>'5d244380e7fed')),
                'services' => new CustomField(array('name'=>'services', 'label'=> __ ( 'Services' ), 'type'=>'repeater', 'key'=>'5d24438ae7fee', 'fields' => array(
                    'service' => new CustomField(array('name'=>'service', 'label'=> __ ( 'Service' ), 'type'=>'taxonomy', 'taxonomy'=>'Services','key'=>'5d23445e57cce')),
                ))),
                'show_section_title' => new CustomFieldOption(array('name'=>'show_section_title', 'label'=> __ ( 'Show section title' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_section_title')),
                'display_sub_terms' => new CustomFieldOption(array('name'=>'display_sub_terms', 'label'=> __ ( 'Display sub terms' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'display_sub_terms')),
                'show_as_cards' => new CustomFieldOption(array('name'=>'show_as_cards', 'label'=> __ ( 'Show as cards' ), 'type'=>'true_false', 'default_value' => false, 'key'=>'show_as_cards')),
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Our Services', array(
                'key' => '5d243af674920'
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
            $taxonomyService = new ServicesTaxonomy();

            foreach ($this->data['services'] as $our_term) {
                $term = $taxonomyService->get_term_by_id($our_term['service']);
                array_push($terms, $term);
            }

            if(count($terms) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($terms), $this->section_option);
            }

            return $html;
        }

        private function html_template($terms) {
            $servicesTaxonomy = new ServicesTaxonomy();
            $services_html = '';
            foreach ($terms as $term) {
                $service_html = '';
                if($this->section_option['show_as_cards']) {
                    $service_html .= $servicesTaxonomy->get_term_section_html($term, array(
                        'display_sub_terms' => $this->section_option['display_sub_terms']
                    ));
                } else {
                    $service_html = ' 
                    <li class="col-lg-4 col-md-12 col-sm-12 col-xs-12"><p> <a href="{{term_link}}"> {{term_title}} </a> </p></li>
                    ';
                    $service_html = str_replace('{{term_title}}', $term['term_title'], $service_html);
                    $service_html = str_replace('{{term_link}}', $term['term_link'], $service_html);
                }
                $services_html .= $service_html;
            }
            if($this->section_option['show_as_cards']) {
                $services_html = '<div class="is-size-6 row">' .$services_html. '</div>';
            } else {
                $services_html = '<ul class="is-size-6 row px-5" style="list-style-type: disc">' .$services_html. '</ul>';
            }

            $html = $services_html;
            if($this->section_option['show_section_title']) {
                $html = '
                 <h2 class="is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{title}} </h2>
                 <div class="is-size-6"> {{description}} </div>
            
                 <div class="p-3">
                    {{services_html}}
                 </div>  
                ';
            }

            $html = str_replace('{{title}}',$this->data['title'], $html);
            $html = str_replace('{{description}}',$this->data['description'], $html);
            $html = str_replace('{{services_html}}',$services_html, $html);

            return $html;
        }

    }

}

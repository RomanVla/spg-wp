<?php


// Register Section PageTitle

if (!class_exists('SectionPageContent')) {

    class SectionPageContent extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Block Title' ), 'type'=>'text', 'key'=>'title')),
                'description' => new CustomField(array('name'=>'description', 'label'=> __ ( 'Block Description' ), 'type'=>'textarea', 'key'=>'description')),
                'banner_url' => new CustomField(array('name'=>'banner_url', 'label'=> __ ( 'Banner' ), 'type'=>'image', 'key'=>'banner_url'))
            );

            $section_option_default = array(
                'title_size' => '2',
                'description_size' => '6'
            );

            $this->cfg = new CustomFieldsBuilder('Block: Page Description', array(
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {

            $section = new static();

            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = '';

            if ($this->data['banner_url'] != '') {
                $html .= $this->html_template_title_with_banner();
            } else {
                $html .= $this->html_template_title();
            }

            $html = str_replace('{{title}}', $this->data['title'], $html);
            $html = str_replace('{{description}}', $this->data['description'], $html);
            $html = str_replace('{{banner_url}}', $this->data['banner_url'], $html);
            $html = str_replace('{{title_size}}', $this->section_option['title_size'], $html);
            $html = str_replace('{{description_size}}', $this->section_option['description_size'], $html);

            return $this->html_wrap_to_section($html, $this->section_option);
        }

        private function html_template_title() {
            return '
            <div class="row title-block">
                <div class="col projects-col">
                    <h{{title_size}} class="is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{title}} </h{{title_size}}>
                    <div class="is-size-{{description_size}} text-justify" style="padding-left: 5px;"> {{description}} </div>
                </div>
            </div>
            ';
        }

        private function html_template_title_with_banner() {
            return '
                <div class="row">
                    <div class="col-lg-5 col-xl-5 col-md-5 col-sm-auto p-rl-3 text-justify">
                        <h{{title_size}} class="is-size-{{title_size}} is-size-4-mobile">{{title}}</h{{title_size}}>
                        <div class="is-size-{{description_size}} is-size-6-mobile">
                            {{description}}
                        </div>
                    </div>
                    <div class="col-lg-7 col-xl-7 col-md-7 col-sm-auto is-hidden-mobile d-md-flex d-lg-flex justify-content-end align-items-center">
                        <img src="{{banner_url}}" alt="{{title}}" data-no-retina class="img-fluid">
                    </div>
                </div>
            
            ';
        }

    }

}

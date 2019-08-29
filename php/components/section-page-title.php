<?php


// Register Section PageTitle

if (!class_exists('SectionPageTitle')) {

    class SectionPageTitle extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'page_title' => new CustomField(array('name'=>'page_title', 'label'=> __ ( 'page_title' ), 'type'=>'text', 'key'=>'5d1f05817479a')),
                'page_description' => new CustomField(array('name'=>'page_description', 'label'=> __ ( 'page_description' ), 'type'=>'text', 'key'=>'5d1f058174b76')),
                'page_banner_url' => new CustomField(array('name'=>'page_banner_url', 'label'=> __ ( 'Banner' ), 'type'=>'image', 'key'=>'5d1f058175343'))
            );

            $section_option_default = array(
                'page_title_size' => '1',
                'page_description_size' => '6'
            );

            $this->cfg = new CustomFieldsBuilder('Block: Page Description', array(
                'key' => '5d1f058163b7d'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {

            $section_option = array();
            if (is_front_page()) {
                $section_option = array(
                    'page_description_size' => 4,
                    'with_background' => true
                );
            }

            $section = new static($section_option, array());

            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = '';

            if ($this->data['page_banner_url'] != '') {
                $html .= $this->html_template_title_with_banner();
            } else if($this->data['page_title'] != '') {
                $html .= $this->html_template_title();
            }

            $html = str_replace('{{page_title}}', $this->data['page_title'], $html);
            $html = str_replace('{{page_description}}', $this->data['page_description'], $html);
            $html = str_replace('{{page_banner_url}}', $this->data['page_banner_url'], $html);
            $html = str_replace('{{page_title_size}}', $this->section_option['page_title_size'], $html);
            $html = str_replace('{{page_description_size}}', $this->section_option['page_description_size'], $html);

            return $this->html_wrap_to_section($html, $this->section_option);
        }

        private function html_template_title() {
            return '
            <div class="row title-block">
                <div class="col projects-col">
                    <h{{page_title_size}} class="is-size-{{page_title_size}} is-size-4-mobile"> {{page_title}} </h{{page_title_size}}>
                    <div class="is-size-{{page_description_size}} text-justify" style="padding-left: 5px;"> {{page_description}} </div>
                </div>
            </div>
            ';
        }

        private function html_template_title_with_banner() {
            return '
                <div class="row">
                    <div class="col-lg-5 col-xl-5 col-md-5 col-sm-auto p-rl-3 text-justify">
                        <h{{page_title_size}} class="is-size-{{page_title_size}} is-size-4-mobile">{{page_title}}</h{{page_title_size}}>
                        <div class="is-size-{{page_description_size}} is-size-6-mobile">
                            {{page_description}}
                        </div>
                    </div>
                    <div class="col-lg-7 col-xl-7 col-md-7 col-sm-auto is-hidden-mobile d-md-flex d-lg-flex justify-content-end align-items-center">
                        <img src="{{page_banner_url}}" alt="{{page_title}}" data-no-retina class="img-fluid">
                    </div>
                </div>
            
            ';
        }

    }

}

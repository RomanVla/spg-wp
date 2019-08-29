<?php


// Register Section PageTitle

if (!class_exists('SectionPageBanner')) {

    class SectionPageBanner extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'banner_url' => new CustomField(array('name'=>'banner_url', 'label'=> __ ( 'Page Banner' ), 'type'=>'image')),
                'description' => new CustomField(array('name'=>'description', 'label'=> __ ( 'Description' ), 'type'=>'textarea')),
                'call_to_action' => new CustomField(array('name'=>'call_to_action', 'label'=> __ ( 'Call to Action' ), 'type'=>'text'))
            );

            $section_option_default = array(
                'title_size' => '1',
                'with_background' => true
            );

            $this->cfg = new CustomFieldsBuilder('Block: Page Banner', array(

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
                $html .= $this->html_template_banner();
            } else {
                $html .= $this->html_template_title();
            }

            $call_to_action = '';
            if($this->data['call_to_action'] != '') {
                $call_to_action = '
            <div style="margin: 20pt 0;">
                <a id="contact-us-btn" href="http://spg-wp.in6k.com/spg/contact-us/#message-form" class="btn btn-secondary is-size-4 is-size-6-mobile contact-us-btn" role="button">{{call_to_action}}</a>                            
            </div>';
                $call_to_action = str_replace('{{call_to_action}}', $this->data['call_to_action'], $call_to_action);
            }

            $html = str_replace('{{title}}', get_the_title(), $html);
            $html = str_replace('{{banner_url}}', $this->data['banner_url'], $html);
            $html = str_replace('{{description}}', $this->data['description'], $html);
            $html = str_replace('{{call_to_action}}', $call_to_action, $html);
            $html = str_replace('{{title_size}}', $this->section_option['title_size'], $html);

            return $this->html_wrap_to_section($html, $this->section_option);
        }

        private function html_template_title() {
            return '
            <div class="row title-block">
                <div class="col projects-col">
                    <h{{title_size}} class="is-size-{{title_size}} is-size-4-mobile"> {{title}} </h{{title_size}}>
                    <div class="is-size-4 text-justify" style="padding-left: 5px;"> {{description}} </div>
                </div>
            </div>
            ';
        }

        private function html_template_banner() {
            $this->section_option['section_additional_class'] = 'p-2';
            return '
                <div class="row is-min-height-450-fullhd is-min-height-450-desktop is-min-height-450-widescreen">
                    <div class="col-lg-4 col-xl-4 col-md-4 col-sm-auto p-rl-3 section-block">
                        <h{{title_size}} class="is-size-1-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile">{{title}}</h{{title_size}}>
                        <div class="is-size-4-fullhd is-size-5-desktop is-size-7-tablet is-size-7-mobile text-justify">
                            {{description}}
                        </div>
                        {{call_to_action}}
                    </div>
                    <div class="col-lg-8 col-xl-8 col-md-8 col-sm-auto is-hidden-mobile d-md-flex d-lg-flex justify-content-end align-items-center">
                        <img src="{{banner_url}}" alt="{{title}}" data-no-retina class="img-fluid">
                    </div>
                </div>
            
            ';
        }

    }

}

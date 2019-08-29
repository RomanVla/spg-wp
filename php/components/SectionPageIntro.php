<?php

// Register Section SectionPageIntro

if (!class_exists('SectionPageIntro')) {

    class SectionPageIntro extends Section
    {

        function __construct( $section_option = array(), $data = array() ) {

            $section_fields = array(
                'description' => new CustomField(array(
                    'name'=>'description',
                    'label'=> __ ( 'Description' ),
                    'type'=>'textarea'
                    )
                ),
                'video_mp4' => new CustomField(array(
                        'name'=>'video_mp4',
                        'label'=> __ ( 'Video file (mp4)' ),
                        'type'=>'file'
                    )
                ),
                'video_webm' => new CustomField(array(
                        'name'=>'video_webm',
                        'label'=> __ ( 'Video file (webm)' ),
                        'type'=>'file'
                    )
                ),
                'buttons' => new CustomField(array(
                    'name'=>'buttons',
                    'label'=> __ ( 'Actions' ),
                    'type'=>'repeater',
                    'fields' => array(
                        'button_text' => new CustomField(array(
                            'name'=>'button_text',
                            'label'=> __ ( 'Text' ),
                            'type'=>'text'
                            )),
                        'button_url' => new CustomField(array(
                            'name'=>'button_url',
                            'label'=> __ ( 'Text' ),
                            'type'=>'text'
                            ))
                        ),

                    )
                )
            );

            $section_option_default = array(
                'with_background' => false
            );

            $this->cfg = new CustomFieldsBuilder('Block: Page Banner', array());

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback()
        {

            $section = new static();

            echo $section->get_html_section();
        }

        public function get_html_section()
        {
            $html = $this->html_template();
            $html = str_replace('{{title}}', get_the_title(), $html);
            $html = str_replace('{{description}}', $this->data['description'], $html);
            $html = str_replace('{{video_mp4}}', $this->data['video_mp4'], $html);
            $html = str_replace('{{video_webm}}', $this->data['video_webm'], $html);
            $html = str_replace('{{html_buttons}}', $this->get_html_buttons(), $html);

            //return $this->html_wrap_to_section($html, $this->section_option);
            return $html;
        }

        private function get_html_buttons() {
            $html = '
                <div class="spg-page-intro-main__buttons">
                    {{html_buttons}}                    
                </div>            
            ';

            $html_buttons = '';
            foreach ($this->data['buttons'] as $button) {
                $html_button = '
                    <div class="spg-page-intro-main__button">
                        <a id="page-intro-btn-1" href="{{button_url}}" role="button"> {{button_text}} </a>
                    </div>';

                $html_button = str_replace('{{button_url}}', $button['button_url'], $html_button);
                $html_button = str_replace('{{button_text}}', $button['button_text'], $html_button);

                $html_buttons .= $html_button;
            }

            $html = str_replace('{{html_buttons}}', $html_buttons, $html);

            return $html;
        }

        private function html_template() {
            return '
                <section class="spg-page-intro">
                        <div class="spg-page-intro-main__wrapper">
                            <div class="spg-page-intro-main">
                            
                                <h1 class="spg-page-intro-main__h1">
                                        {{title}}
                                </h1>
                                
                                <div class="spg-page-intro-main__content">
                                    <h2 class="spg-page-intro-main__content-h2">
                                        {{description}}
                                    </h2>
                                </div>
                                
                                {{html_buttons}}                                                                
                
                            </div>
                        </div>
                
                <video class="spg-page-intro__video" data-element="main-video" autoplay="" loop="" muted="">
                    <source src="{{video_mp4}}" type="video/mp4">
                    <source src="{{video_webm}}" type="video/webm">
                </video>
            </section>            
            ';
        }
    }

}
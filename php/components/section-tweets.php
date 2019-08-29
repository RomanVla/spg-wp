<?php

// Register Section PageTitle

if (!class_exists('SectionTweets')) {

    class SectionTweets extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'twitter_title' => new CustomField(array('name'=>'twitter_title', 'label'=> __ ( 'Title' ), 'type'=>'text')),
                'twitter_url' => new CustomField(array('name'=>'twitter_url', 'label'=> __ ( 'Url' ), 'type'=>'text', 'key'=>'5d272acd0f706'))
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Page Description', array(
                'key' => '5d271b54cfb73'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {

            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = $this->html_wrap_to_section($this->html_template(), $this->section_option);

            return $html;
        }

        private function html_template() {

            $html = '
             <div>
                <article>
                    <a id="twitter-timeline" class="twitter-timeline" href="{{twitter_url}}" rel="nofollow" height="740">{{twitter_title}}</a>
                        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </article>
            </div>
            ';

            $html = str_replace('{{twitter_title}}', $this->data['twitter_title'], $html);
            $html = str_replace('{{twitter_url}}', $this->data['twitter_url'], $html);

            return $html;
        }

    }

}

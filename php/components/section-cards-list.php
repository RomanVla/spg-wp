<?php

// Register Section CardsList

if (!class_exists('SectionCardsList')) {

    class SectionCardsList extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'5d1f6ea985d3c')),
                'description' => new CustomField(array('name'=>'description', 'label'=> __ ( 'Description' ), 'type'=>'textarea', 'key'=>'5d1f6eb485d3d')),
                'cards' => new CustomField(array('name'=>'cards', 'label'=> __ ( 'Cards' ), 'type'=>'repeater', 'key'=>'5d1f6ed985d3f', 'fields' => array(
                    'card' => new CustomField(array('name'=>'card', 'label'=> __ ( 'Сard' ), 'type'=>'group', 'key'=>'5ce8213e4ff91', 'fields' => array(
                        'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'5ce8215b4ff92')),
                        'description' => new CustomField(array('name'=>'description', 'label'=> __ ( 'Description' ), 'type'=>'text', 'key'=>'5ce821634ff93')),
                        'title_icon' => new CustomField(array('name'=>'title_icon', 'label'=> __ ( 'Title icon' ), 'type'=>'text', 'key'=>'5ce821754ff94')),
                        'image' => new CustomField(array('name'=>'image', 'label'=> __ ( 'Image' ), 'type'=>'image', 'key'=>'5d2878badc995')),
                        'link' => new CustomField(array('name'=>'link', 'label'=> __ ( 'Link' ), 'type'=>'image', 'key'=>'5d2879656c1fb')),
                    ))),
                ))),
                'show_cards_as' => new CustomFieldOption(array('name'=>'show_cards_as', 'label'=> __ ( 'Title' ), 'type'=>'select', 'key'=>'5d287cfbfa845', 'choices'=>array('list'=>'list','card'=>'card','tile'=>'tile'))),
                'cards_per_row' => new CustomFieldOption(array('name'=>'cards_per_row', 'label'=> __ ( 'Cards per row' ), 'type'=>'number', 'key'=>'cards_per_row')),
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Card List', array(
                'key' => '5d1f5edb506d5'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {
            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section() {
            return $this->html_wrap_to_section($this->html_template(), $this->section_option);
        }

        private function html_template() {

            $html = '';

            $card_count = 0;
            $cards_html = '';
            foreach ($this->data['cards'] as $cardSection) {
                $card = $cardSection['card'];

                switch ($this->section_option['show_cards_as']) {
                    case 'tile':
                        $card_html = $this->html_template_tile();
                        break;
                    case 'card':
                        $card_html = $this->html_template_card();
                        break;
                    default:
                        $card_html = $this->html_template_list();
                }

                $card_class = 'col-lg-4 col-md-4';
                if($this->section_option['cards_per_row'] > 0) {
                    $k = round (12 / $this->section_option['cards_per_row']);
                    $card_class = 'col-lg-' .$k. ' col-md-' .$k;
                }

                $card_html = str_replace('{{card_count}}', $card_count += 1, $card_html);
                $card_html = str_replace('{{card_class}}', $card_class, $card_html);
                $card_html = str_replace('{{title_icon}}', $card['title_icon'], $card_html);
                $card_html = str_replace('{{title}}', $card['title'], $card_html);
                $card_html = str_replace('{{description}}', $card['description'], $card_html);
                $card_html = str_replace('{{image}}', $card['image'], $card_html);
                $card_html = str_replace('{{link}}', $card['link'], $card_html);

                $cards_html .= $card_html;
            }

            $title_description_html = '';
            if(($this->data['title'] != '') && ($this->data['description'] != '')) {
                $title_description_html = '
                    <div class="col-12">
            
                        <h2 class="is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{section_title}} </h2>
                        <div class="is-size-6"> {{section_description}} </div>
            
                    </div>
                ';

                $title_description_html = str_replace('{{section_title}}', $this->data['title'], $title_description_html);
                $title_description_html = str_replace('{{section_description}}', $this->data['description'], $title_description_html);
            }

            $html .= '
                <div class="row">
                
                    {{title_description_html}}
            
                    <div class="col-12">
                        
                        <div class="row">            
                            {{cards_html}}                
                        </div>
                                
                    </div>
            
                </div>';

            $html = str_replace('{{title_description_html}}', $title_description_html, $html);
            $html = str_replace('{{cards_html}}', $cards_html, $html);

            return $html;
        }

        private function html_template_list() {

            $html = '
                    <div class=" {{card_class}} col-sm-12 p-4">
                        <div class="row">
                            <div class="col ">
                                <h4 class="text-center is-size-4">{{title}}</h4>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-12 col-xs-12 is-size-2 is-size-3-mobile text-center">
                                <span class="rounded-bullet">{{card_count}}</span>
                            </div>
                            <div class="col-lg-9 col-sm-12 col-xs-12"> {{description}} </div>
                        </div>
                    </div>
                    ';

            return $html;
        }

        private function html_template_tile() {

            $html = '
                    <div class=" {{card_class}} col-sm-12 p-4"> 
                        <div class="text-center">
                            <a href="{{link}}"><img src="{{image}}" alt="{{title}}" data-no-retina class="img-fluid"></a>
                        </div>
                        <div class="text-center is-size-7-mobile is-size-6-desktop">{{title}}</div>
                    </div>
                    ';

            return $html;
        }

        private function html_template_card() {

            $html = '
                <div class=" {{card_class}} col-sm-12 p-4">            
                    
                    <div class="card mb-3 border-0">
                        <div class="row">
                            <div class="col" style="margin: auto">
                                <img src="{{image}}" class="card-img mx-auto d-block is-max-width-250" alt="{{title}}">
                            </div>                        
                        </div>
                        <div class="row no-gutters">
                            <div class="col">
                                <div class="card-body">
                                    <h2 class="card-title is-size-5-desktop"> <a href="{{link}}">{{title}}</a> </h2>
                                    <p class="card-text"> {{description}} </p>
                                </div>                    
                            </div>
                        </div>
                    </div>
                    
                </div>
                    ';

            return $html;
        }

    }

}

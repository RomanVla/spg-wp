<?php

// Register Section PageTitle

if (!class_exists('SectionContactUs')) {

    class SectionContactUs extends Section
    {
        function __construct($section_option = array(), $data = array())
        {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'title')),
                'contacts' => new CustomField(array('name'=>'contacts', 'label'=> __ ( 'Contacts' ), 'type'=>'repeater', 'key'=>'5d00fccc18caf', 'fields' => array(
                    'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'5d00fcde18cb0')),
                    'address' => new CustomField(array('name'=>'address', 'label'=> __ ( 'Address' ), 'type'=>'text', 'key'=>'5d00fcf018cb1')),
                    'email' => new CustomField(array('name'=>'email', 'label'=> __ ( 'Email' ), 'type'=>'text', 'key'=>'5d00fd0918cb2')),
                    'telephone_number' => new CustomField(array('name'=>'telephone_number', 'label'=> __ ( 'telephone_number' ), 'type'=>'text', 'key'=>'5d00fd2f18cb3')),
                ))),

                'cards_per_row' => new CustomFieldOption(array('name'=>'cards_per_row', 'label'=> __ ( 'Cards per row' ), 'type'=>'select', 'key'=>'cards_per_row', 'choices'=>array('1'=>'1','2'=>'2')))
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Contact Us', array(
                'key' => '5d00fa8e27b5b'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback()
        {

            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section()
        {

            $html = $this->html_wrap_to_section($this->html_template(), $this->section_option);

            return $html;
        }

        private function html_template()
        {

            $contacts_html = '';
            $contacts = $this->data['contacts'];

            $class_contact = 'col-lg-12 col-md-12';
            if($this->section_option['cards_per_row'] == '2') {
                $class_contact = 'col-lg-6 col-md-6';
            }

            foreach ($contacts as $contact) {

                $contact_html = '
                    <div class="{{$class_contact}} col-xs-12 col-sm-12 py-3">
                        <div class="pb-2 font-weight-bold">
                            {{contact_title}}
                        </div>
                        <div class="pb-1">
                            <span class="px-1"><i class="fas fa-map-marker-alt"></i></span> {{contact_address}}
                        </div>
                        <div class="pb-1">
                            <span class="px-1">
                            <i class="far fa-envelope"></i><a id="contact-email" href="mailto:{{contact_email}}"> {{contact_email}}</a> 
                            </span>
                        </div>
                        <div class="pb-1">
                            <span class="px-1">
                            <a id="phone-number" href="tel:{{contact_telephone_number}}" class="cont_no">
                           <i class="fas fa-phone"></i></span> {{contact_telephone_number}}</a>
                        </div>            
                    </div>
                ';

                $contact_html = str_replace('{{contact_title}}', $contact['title'], $contact_html);
                $contact_html = str_replace('{{contact_address}}', $contact['address'], $contact_html);
                $contact_html = str_replace('{{contact_email}}', $contact['email'], $contact_html);
                $contact_html = str_replace('{{contact_telephone_number}}', $contact['telephone_number'], $contact_html);
                $contact_html = str_replace('{{$class_contact}}', $class_contact, $contact_html);

                $contacts_html .= $contact_html;
            }

            $html = '<div> 
                        <h2 class="is-size-4-fullhd is-size-4-desktop is-size-4-tablet is-size-4-mobile">{{section_title}}</h2> 
                        <div class="row"> 
                            {{contacts_html}}
                        </div> 
                     </div>';

            $html = str_replace('{{section_title}}', $this->data['title'], $html);
            $html = str_replace('{{contacts_html}}', $contacts_html, $html);

            return $html;
        }
    }

}

<?php

// Register Section PageTitle

if (!class_exists('SectionContactForm')) {

    class SectionContactForm extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'form_title' => new CustomField(array('name'=>'form_title', 'label'=> __ ( 'Form Title' ), 'type'=>'text', 'key'=>'form_title')),
                'field_name_label' => new CustomField(array('name'=>'field_name_label', 'label'=> __ ( 'Label for name input' ), 'type'=>'text', 'key'=>'field_name_label')),
                'field_email_label' => new CustomField(array('name'=>'field_email_label', 'label'=> __ ( 'Label for email input' ), 'type'=>'text', 'key'=>'field_email_label')),
                'field_message_label' => new CustomField(array('name'=>'field_message_label', 'label'=> __ ( 'Label for message input' ), 'type'=>'text', 'key'=>'field_message_label')),
                'button_send_label' => new CustomField(array('name'=>'button_send_label', 'label'=> __ ( 'Label for Send button' ), 'type'=>'text', 'key'=>'button_send_label'))
            );

            $section_option_default = array(

            );

            $this->cfg = new CustomFieldsBuilder('Block: Contact Form', array(
                'key' => '5d2752e15f229'
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
            <div class="">
                <div class="card contacts-form">
                            <div class="card-body">
     
                    <div class="is-size-4 pb-4">
                      {{form_title}}
                    </div>
                
                    <div class="pb-4">
                        
                        <div method="post">
                        
                            <form id="message-form" class="needs-validation row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>{{field_name_label}}</label>
                                        <input id="name" type="text" name="name" class="form-control" placeholder="e.g. Miles Bennett Dyson" required/>
                                    </div>
                    
                                    <div class="form-group">
                                        <label>{{field_email_label}}</label>
                                        <input id="email" class="form-control" type="email" name="email" aria-describedby="emailHelp" placeholder="mb.dyson@cyberdyne.net" required/>
                                        <small class="form-text" style="display: none; color: #dc3545;" id="invalid_email">Email is invalid</small>
                                        <small class="form-text text-muted" id="email-text"></small>
                                    </div>                                
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>{{field_message_label}}</label>
                                        <textarea id="message" class="form-control" name="message" placeholder="I need a quote for a Skynet project development/security testing." required></textarea>
                                    </div>
                                </div>                        
                            </form>
                                                
                            <div class="row justify-content-end p-2">
                                <div class="px-4 hidden-sm hidden-xs"></div>
                                <div class="px-4">                                                            
                                    <a id="send-message" class="form-control btn btn-dark text-light message-form-btn-send-message">
                                        {{button_send_label}}
                                        <span class="px-1"> <i class="fab fa-telegram-plane"></i> </span>
                                    </a>
                                    <div class="invalid-feedback">Error sending message. Try again.</div>             
                                    <p class="invalid-feedback" id="empty-field"></p> 
                                </div>                            
                            </div>
                                                
                        </div>
                                                              
                    </div>           
                
                </div> 
            </div>    ';

            $html = str_replace('{{form_title}}', get_value($this->data['form_title'], 'Request additional details from us'), $html);
            $html = str_replace('{{field_name_label}}', get_value($this->data['field_name_label'], 'Your Name'), $html);
            $html = str_replace('{{field_email_label}}', get_value($this->data['field_email_label'], 'Email address'), $html);
            $html = str_replace('{{field_message_label}}', get_value($this->data['field_message_label'], 'Message to us'), $html);
            $html = str_replace('{{button_send_label}}', $this->data['button_send_label'], $html);

            return $html;
        }

    }

}

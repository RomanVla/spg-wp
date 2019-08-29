<?php

// Register Section SectionTestimonials

if (!class_exists('SectionTestimonials')) {

    class SectionTestimonials extends Section {
        function __construct($section_option = array(), $data = array()) {

            $section_fields = array(
                'title' => new CustomField(array('name'=>'title', 'label'=> __ ( 'Title' ), 'type'=>'text', 'key'=>'title')),
                'testimonials' => new CustomField(array('name'=>'testimonials', 'label'=> __ ( 'Testimonials' ), 'type'=>'repeater', 'key'=>'5d235d6c6b468', 'fields' => array(
                    'testimonial' => new CustomField(array('name'=>'testimonial', 'label'=> __ ( 'Testimonial' ), 'type'=>'post_object', 'post_type'=>'testimonial','key'=>'5d1f5078fb6ac')),
                )))
            );

            $section_option_default = array(
            );

            $this->cfg = new CustomFieldsBuilder('Block: Testimonials', array(
                'key' => '5d1f5032c144d'
            ));

            parent::__construct(array_merge($section_option_default, $section_option), $data, $section_fields);
        }

        public static function block_render_callback() {
            $section = new static();
            echo $section->get_html_section();
        }

        public function get_html_section() {
            $html = '';

            $testimonials = array();
            $testimonialPostType = new TestimonialPostType();
            foreach ($this->data['testimonials'] as  $testimonial_post) {
                $testimonial = $testimonialPostType->get_postById($testimonial_post['testimonial']->ID);
                array_push($testimonials, $testimonial);

            }
            if(count($testimonials) > 0) {
                $html = $this->html_wrap_to_section($this->html_template($testimonials), $this->section_option);
            }

            return $html;
        }

        private function html_template($testimonial_posts) {

            $testimonials_html = '';
            $testimonials_indicator_html = '';

            $slide_num = 0;
            $active_class = 'active';
            foreach ($testimonial_posts as $testimonial) {

                $testimonial_html = '
                <div class=" carousel-item {{active_class}} is-min-height-180-desktop is-min-height-350-mobile" >
                    <div class="d-flex flex-column" >
                        <div class="py-3" > {{testimonial_text}} </div >
                        <div class="ml-auto p-2 py-3" > - {{testimonial_author}} </div>
                    </div>
                </div >';

                $testimonial_html = str_replace('{{active_class}}', $active_class, $testimonial_html);
                $testimonial_html = str_replace('{{testimonial_text}}', $testimonial['testimonial_text'], $testimonial_html);
                $testimonial_html = str_replace('{{testimonial_author}}', $testimonial['author'], $testimonial_html);

                $testimonials_html .= $testimonial_html;

                $testimonial_indicator_html = '
                    <li data-target="#carousel-testimonials" data-slide-to="{{slide_num}}" class="{{active_class}}"></li>';

                $testimonial_indicator_html = str_replace('{{active_class}}', $active_class, $testimonial_indicator_html);
                $testimonial_indicator_html = str_replace('{{slide_num}}', $slide_num, $testimonial_indicator_html);

                $testimonials_indicator_html .= $testimonial_indicator_html;

                $active_class = '';
                $slide_num += 1;
            }

            $html = '
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                        <h2 class="client-say-title is-size-2-fullhd is-size-2-desktop is-size-4-tablet is-size-4-mobile"> {{section_title}} </h2>
                        <div data-clear="is-size-6" id="carousel-testimonials" class="carousel" data-ride="carousel">
                        
                            <div>
                                <div class="carousel-inner col-lg-10 col-md-10 col-xs-10 offset-lg-1 offset-md-1 offset-xs-1 col-sm-12 offset-sm-0 px-3">
    
                                    {{testimonials_html}}
                                    
                                </div>
    
                                <ol class="carousel-indicators is-marginless">
                                
                                    {{testimonials_indicator_html}}
                                    
                                </ol>    
                            </div>                         
                            <a class="carousel-control-prev is-hidden-mobile" href="#carousel-testimonials" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true">‹</span>
                                <span class="sr-only">Previous</span>                                
                            </a>              
                            <a class="carousel-control-next is-hidden-mobile" href="#carousel-testimonials" role="button" data-slide="next">                  
                                <span class="carousel-control-next-icon" aria-hidden="true">›</span>
                                <span class="sr-only">Next</span>
                            </a>
                                                          
                        </div>                                        
                </div>
            </div>
            ';

            $html = str_replace('{{section_title}}', $this->data['title'], $html);
            $html = str_replace('{{testimonials_html}}', $testimonials_html, $html);
            $html = str_replace('{{testimonials_indicator_html}}', $testimonials_indicator_html, $html);

            return $html;
        }

    }

}

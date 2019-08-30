<?php

if (!class_exists('PageMetaboxHero')) {

    class PageMetaboxHero extends PageMetabox
    {

        function __construct( ) {

            $this->name = 'page_metabox_hero';

            $this->fields = array(
                'title' => new CustomField(array(
                        'name'=> $this->name.'title',
                        'label'=> __ ( 'Title' ),
                        'type'=>'text'
                    )
                ),
                'description' => new CustomField(array(
                        'name'=> $this->name.'_description',
                        'label'=> __ ( 'Description' ),
                        'type'=>'textarea'
                    )
                ),
                'video_mp4' => new CustomField(array(
                        'name'=> $this->name.'_video_mp4',
                        'label'=> __ ( 'Video file (mp4)' ),
                        'type'=>'file'
                    )
                ),
                'video_webm' => new CustomField(array(
                        'name'=> $this->name.'_video_webm',
                        'label'=> __ ( 'Video file (webm)' ),
                        'type'=>'file'
                    )
                ),
                'buttons' => new CustomField(array(
                        'name'=> $this->name.'_buttons',
                        'label'=> __ ( 'Actions' ),
                        'type'=>'repeater',
                        'args'=> array(
                            'min'=>2,
                            'max'=>2
                        ),
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

            $this->cfg = new CustomFieldsBuilder('Hero: Front Page', array());

            parent::__construct();
        }

    }

}
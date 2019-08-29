<?php
/**
 * Create  Testimonial Post Type
 *
 */

if (!class_exists('TestimonialPostType')) {

    class TestimonialPostType extends PostType
    {

        function __construct($option = array())
        {
            $object_option = set_array_atts(array(
                'post_type' => PostTypeName::Testimonial,
            ), $option);

            parent::__construct($object_option);
        }

        public function register_post_type()
        {
            global $theme_spg;

            $post_type_name = PostTypeName::Testimonial;

            $labels = array(
                'name' => _x('Testimonials', 'Post Type General Name', $theme_spg->domain),
                'menu_name' => __('Testimonials', $theme_spg->domain)
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_ui' => true,
                'menu_icon' => 'dashicons-testimonial'
            );

            parent::wp_register_post_type($post_type_name, $args);
        }

    }
}
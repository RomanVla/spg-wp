<?php
/**
 * Create  Services Taxonomy
 *
 */

if (!class_exists('ServicesTaxonomy')) {

    class ServicesTaxonomy extends Taxonomy
    {

        function __construct($option = array())
        {
            $object_option = array(
                'taxonomy' => TaxonomyName::Services
            );

            parent::__construct(array_merge($object_option, $option));
        }

        public function register_taxonomy($associated_object, $args = array()) {
            global $theme_spg;

            $this->option['associated_object'] = $associated_object;

            $args_default = array(
                'labels' => array(
                    'name' => _x('Services', 'Post Type taxonomy "Service" General Name', $theme_spg->domain),
                    'menu_name' => __('Services', $theme_spg->domain)
                ),
                'rewrite'=> array( 'slug' => 'what-we-do' )
            );

            parent::wp_register_taxonomy(array_merge($args_default, $args));
        }

    }

}

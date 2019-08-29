<?php
/**
 * Create  Technologies Taxonomy
 *
 */

if (!class_exists('TechnologiesTaxonomy')) {

    class TechnologiesTaxonomy extends Taxonomy
    {

        function __construct($option = array())
        {
            $object_option = array(
                'taxonomy' => TaxonomyName::Technologies
            );

            parent::__construct(array_merge($object_option, $option));
        }

        function register_taxonomy($associated_object, $args = array()) {
            global $theme_spg;

            $this->option['associated_object'] = $associated_object;

            $args_default = array(
                'labels' => array(
                    'name' => _x('Technologies', 'Post Type taxonomy "Technology" General Name', $theme_spg->domain),
                    'menu_name' => __('Technologies', $theme_spg->domain)
                    )
            );

            parent::wp_register_taxonomy(array_merge($args_default, $args));
        }

    }

}

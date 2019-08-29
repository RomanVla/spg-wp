<?php
/**
 * Create  Industry Taxonomy
 *
 */

if (!class_exists('IndustryTaxonomy')) {

    class IndustryTaxonomy extends Taxonomy
    {

        function __construct($option = array())
        {
            $object_option = array(
                'taxonomy' => TaxonomyName::Industry
            );

            parent::__construct(array_merge($object_option, $option));
        }

        function register_taxonomy($associated_object, $args = array()) {
            global $theme_spg;

            $this->option['associated_object'] = $associated_object;

            $args_default = array(
                'labels' => array(
                    'name' => _x('Industry', 'Post Type taxonomy "Industry" General Name', $theme_spg->domain),
                    'menu_name' => __('Industries', $theme_spg->domain)
                )
            );

            parent::wp_register_taxonomy(array_merge($args_default, $args));
        }

    }

}

<?php

if (!class_exists('SPG_Enum')) {


    abstract class SPG_Enum
    {
        final public function __construct($value)
        {
            $c = new ReflectionClass($this);
            if (!in_array($value, $c->getConstants())) {
                throw IllegalArgumentException();
            }
            $this->value = $value;
        }

        final public function __toString()
        {
            return $this->value;
        }
    }

}

if (!class_exists('PostTypeName')) {

    class PostTypeName extends SPG_Enum
    {
        const __default = '';

        const Employee = 'employee';
        const Project = 'project';
        const Testimonial = 'testimonial';
    }

}

if (!class_exists('TaxonomyName')) {

    class TaxonomyName extends SPG_Enum
    {
        const __default = '';

        const Industry = 'Industry';
        const Services = 'Services';
        const Technologies = 'Technologies';
    }

}

if (!class_exists('Theme_SPG')) {

    class Theme_SPG
    {

        public $domain = 'spg';
        public $theme_file_suffix = 'spg';
        public $acf_prefix = 'spg';

        private $post_types = array();
        private $taxonomies = array();
        private $page_metaboxes = array();

        function __construct(){

        }

        public function init_theme() {

            $this->register_post_types();
            $this->register_taxonomies();
            $this->register_widgets();

            $this->init_acf();
        }

        private function init_acf() {

            $this->register_options_page();
            $this->register_page_metaboxes();

            add_action('acf/init', function() {
                global $theme_spg;

                if (function_exists('acf_register_block')) {
                    $theme_spg->acf_build_field();
                    $theme_spg->register_blocks();
                }

            });

        }

        public function is_blog_page() {
            global $post;

            // Post type must be 'post'.
            $post_type = get_post_type($post);

            // Check all blog-related conditional tags, as well as the current post type,
            // to determine if we're viewing a blog page.
            return ( $post_type === 'post' ) && ( is_home() || is_archive() || is_single() );
        }

        public function get_theme_nav_menu($theme_location, $only_menu_items = false)
        {
            $locations = get_nav_menu_locations();
            if(($theme_location) && isset($locations[$theme_location])) {

                $navigation_buttons = get_term( $locations[$theme_location], 'nav_menu' );

                $nav_menu = new SectionNavMenu(array(
                    'theme_location' => $theme_location,
                    'logo' => get_field( 'site_logo', 'options' ),
                    'menu_items' => wp_get_nav_menu_items($navigation_buttons->term_id),
                    'nav_btns' => get_field('navigation_buttons' , $navigation_buttons)
                ));
                if ($only_menu_items) {
                    $menu_list = $nav_menu->get_menu_items_html();
                } else {
                    $menu_list = $nav_menu->get_html_section();
                }

            } else {

                $menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';

            }

            return $menu_list;

        }

        private function register_page_metaboxes() {
            $this->register_page_metabox(new PageMetaboxHero());
        }

        private function register_post_types() {
            $this->register_post_type(PostTypeName::Employee, new EmployeePostType());
            $this->register_post_type(PostTypeName::Project, new ProjectPostType());
            $this->register_post_type(PostTypeName::Testimonial, new TestimonialPostType());
        }

        private function register_taxonomies() {
            $this->register_taxonomy(TaxonomyName::Industry, new IndustryTaxonomy(), array(PostTypeName::Project));
            $this->register_taxonomy(TaxonomyName::Services, new ServicesTaxonomy(), array(PostTypeName::Project));
            $this->register_taxonomy(TaxonomyName::Technologies, new TechnologiesTaxonomy(), array(PostTypeName::Project));
        }

        private function register_widgets() {

            add_action('widgets_init', function() {

                register_sidebar(array(
                    'name' => 'Logo widget',
                    'id' => 'logo_widget',
                    'before_widget' => '<div>',
                    'after_widget' => '</div>',
                    'before_title' => '<h5>',
                    'after_title' => '</h5>',
                ));

                register_sidebar(array(
                    'name' => 'Footer menu widget',
                    'id' => 'footer_menu_widget',
                    'before_widget' => '<div class="footer-menu">',
                    'after_widget' => '</div>',
                    'before_title' => '<h5>',
                    'after_title' => '</h5>',
                ));

            }
            );

        }

        private function register_options_page() {

            if ( function_exists( 'acf_add_options_page' ) ) {

                acf_add_options_page( array(
                    'page_title' => 'Theme General Settings',
                    'menu_title' => 'Theme Settings',
                    'menu_slug'  => 'theme-general-settings',
                    'capability' => 'edit_posts',
                    'redirect'   => false
                ) );

            }

        }

        public function register_blocks() {
            add_filter( 'block_categories', function( $categories ) {
                global $theme_spg;

                return array_merge(
                    $categories,
                    array(
                        array(
                            'slug'  => $theme_spg->acf_prefix,
                            'title' => ucwords($theme_spg->acf_prefix) . ' Blocks',
                        ),
                    )
                );
            }, 10, 2 );

            add_filter( 'render_block', function ( $block_content, $block ) {
                global $theme_spg;

                $block_name = $block['blockName'];
                if(
                    (!strpos($block_name, $theme_spg->acf_prefix))
                    && ($block_name != 'core/column')
                ){
                    $section = new Section();
                    $block_content = $section->html_wrap_to_section( $block_content );
                }
                return $block_content;
            }, PHP_INT_MAX - 1, 2 );

            $this->register_block(new SectionPageIntro());
            $this->register_block(new SectionPageTitle());
            $this->register_block(new SectionPageBanner());
            $this->register_block(new SectionPageContent());
            $this->register_block(new SectionTestimonials());
            $this->register_block(new SectionCardsList());
            $this->register_block(new SectionTechnologiesOverview());
            $this->register_block(new SectionOurProjects());
            $this->register_block(new SectionOurTeam());
            $this->register_block(new SectionOurServices());
            $this->register_block(new SectionOurIndustries());
            $this->register_block(new SectionContactForm());
            $this->register_block(new SectionContactUs());
            $this->register_block(new SectionTweets());

        }

        public function acf_build_field() {
            $post_type_services = $this->get_post_type_services_by_post_type_name(PostTypeName::Employee);
            $post_type_services->acf_build_field();
        }

        public function get_post_type_services_by_post_type($wp_post_type) {
            return $this->get_post_type_services_by_post_type_name($wp_post_type->name);
        }
        public function get_taxonomy_services_by_term($wp_term) {
            return $this->get_taxonomy_services_by_taxonomy_name($wp_term->taxonomy);
        }

        public function get_post_type_services_by_post_type_name($wp_post_type_name) {
            $post_type_services = null;
            if(array_key_exists($wp_post_type_name, $this->post_types)) {
                $post_type_services = $this->post_types[$wp_post_type_name];
            }

            return $post_type_services;
        }

        public function get_taxonomy_services_by_taxonomy_name($taxonomy_name) {
            $current_taxonomy_services = null;
            if(array_key_exists($taxonomy_name, $this->taxonomies)) {
                $current_taxonomy_services = $this->taxonomies[$taxonomy_name];
            }

            return $current_taxonomy_services;
        }

        public function get_page_metabox_service_by_name($page_metabox_name) {
            $current_page_metabox_service = null;
            if(array_key_exists($page_metabox_name, $this->page_metaboxes)) {
                $current_page_metabox_service = $this->page_metaboxes[$page_metabox_name];
                $current_page_metabox_service->read_data_fields();
            }

            return $current_page_metabox_service;
        }

        private function register_block($block) {
            if($block instanceof Section ) {
                $block->acf_build_field();
                $block->register_block();
            }
        }

        private function register_post_type($wp_post_type_name, PostType $post_type) {

            $this->post_types[$wp_post_type_name] = $post_type;
            $post_type->register_post_type();

        }

        private function register_taxonomy($wp_taxonomy_name, Taxonomy $taxonomy, $associated_object) {

            $this->taxonomies[$wp_taxonomy_name] = $taxonomy;
            $taxonomy->register_taxonomy($associated_object);

        }

        private function register_page_metabox(PageMetabox $page_metabox) {

            $this->page_metaboxes[$page_metabox->name] = $page_metabox;
            $page_metabox->register_page_metabox();
        }

        public function get_menu_snippet() {
            global $theme_spg;

            return '
                <header></header>
                ';

            return '
                <header class="header container-fluid px-0">
                    <section class="px-4">
                        <div class="container-head">
                            <div>'

                        .$theme_spg->get_theme_nav_menu( 'primary' ).

                        '</div>
                        </div>
                    </section>
                    <div>'

                        .$this->get_section_breadcrumb_html().

                        '</div>
                    
                </header>    
            ';

        }

        private function get_section_breadcrumb_html() {

            $html = '';

            if(!is_front_page() && !is_404() && function_exists('bcn_display')) {

                $html .= '
                    <div class="page-title-wrap section_with_background">
                        <div class="container-head">
                            <div class="columns">
                                <div class="column">
                                    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">'

                            .bcn_display(true).

                            '</div>
                                </div>
                            </div>
                        </div>
                    </div>    
                ';

            }

            return $html;
        }

        public function get_page_title() {

            $html = '
                    <header class="entry-header">
                        <section style="padding: 0.6rem 0;">
                            <div class="container">
                                <div class="row title-block">
                                    <div class="col">
                                        <h1 class="is-size-1 is-size-4-mobile"> {{page_title}} </h1>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </header>
                    ';

            $html = str_replace('{{page_title}}', get_the_title(), $html);

            $section_page_intro = new SectionPageIntro();
            $section_page_banner = new SectionPageBanner();
            $section_page_title = new SectionPageTitle();

            $post = get_post();
            if ( has_blocks( $post->post_content ) ) {
                $blocks = parse_blocks( $post->post_content );

                $block_name = $blocks[0]['blockName'];
                if (
                    strpos($block_name, str_replace('_', '-', $section_page_banner->get_block_name()))
                    || strpos($block_name, str_replace('_', '-', $section_page_intro->get_block_name()))
                    || strpos($block_name, str_replace('_', '-', $section_page_title->get_block_name()))
                ) {
                    $html = '';
                }
            }

            return $html;
        }


    }

}

require_once(__DIR__ . '/custom-fields/spg-custom-fields.php');

require_once(__DIR__ . '/taxonomies/spg-taxonomies.php');
require_once(__DIR__ . '/post-types/spg-post-types.php');

require_once(__DIR__ . '/page-metaboxes/PageMetabox.php');
require_once(__DIR__ . '/components/sections.php');

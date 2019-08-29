<?php

require_once(locate_template('/php/spg-services.php'));
require_once(locate_template('/php/spg-theme.php'));

$theme_spg = new Theme_SPG();
$domain = 'spg';

if (!function_exists('spg_setup')) {
    function spg_setup_theme() {
        global $theme_spg;

        add_theme_support('automatic-feed-links');

        add_theme_support('post-formats', array('aside', 'gallery', 'quote', 'image', 'video'));

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', $theme_spg->domain),
            'secondary' => esc_html__('Secondary Menu', $theme_spg->domain)
        ));

        show_admin_bar(false);

        add_post_type_support( 'page', 'excerpt' );

        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'rsd_link');

        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }

}
add_action('after_setup_theme', 'spg_setup_theme');

if (!function_exists('spg_admin_scripts')) {

    function spg_admin_scripts()
    {

    }

}
add_action('admin_enqueue_scripts', 'spg_admin_scripts');

if (!function_exists('spg_scripts')) {

    function spg_scripts()
    {
        wp_register_style('bulma', get_stylesheet_directory_uri() . '/css/bulma.css');
        wp_register_style('spg-style', get_stylesheet_directory_uri() . '/css/spg-style.css');
        wp_register_style('spg-style-blog', get_stylesheet_directory_uri() . '/css/spg-style-blog.css');
        wp_register_style('fontawesome-spg', get_stylesheet_directory_uri() . '/node_modules/@fortawesome/fontawesome-free/css/all.min.css');

        wp_register_script('jquery-spg', get_stylesheet_directory_uri() . '/node_modules/jquery/dist/jquery.min.js');
        wp_register_script('spg-scripts', get_stylesheet_directory_uri() . '/js/scripts.min.js');

        wp_dequeue_style( 'wp-block-library' );
    }

}
add_action('wp_enqueue_scripts', 'spg_scripts');

add_action('init', function() {
    global $theme_spg;

    $theme_spg->init_theme();

}, 0);

function wp_upload_mimes($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'wp_upload_mimes');


function spg_search_box($form) {
    $form = '<form class="form-inline my-2 my-lg-0" style="flex-flow: nowrap" action="' . home_url('/') . '">
         <input class="form-control mr-sm-2" type="search" placeholder="" aria-label="Search" value="' . get_search_query() . '" name="s">
         <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
         </form>';
    return $form;
}
add_filter('get_search_form', 'spg_search_box');

function spg_change_and_link_excerpt( $more ) {
    if ( is_admin() ) {
        return $more;
    }

    // Change text, make it link, and return change
    return '&hellip; <a href="' . get_the_permalink() . '">More »</a>';
}
add_filter( 'excerpt_more', 'spg_change_and_link_excerpt', 999 );


function custom_editor_css() {
    echo '<style type="text/css">
.wp-block { max-width: none; }
</style>';
}
add_action('admin_head', 'custom_editor_css');

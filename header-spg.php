<?php

if (!function_exists('spg_register_style')) {

    function spg_register_style()
    {
        global $theme_spg;

        if ( !$theme_spg->is_blog_page() ) {

            wp_enqueue_style('spg-style' );
            wp_enqueue_style('fontawesome-spg' );

            wp_enqueue_script('jquery-spg' );
            wp_enqueue_script('spg-scripts' );

        }

    }

}
add_action( 'wp_enqueue_scripts', 'spg_register_style', 20 );

global $theme_spg;

?>

<!DOCTYPE html>

<html class="no-js" lang="en">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <title><?php wp_title('|', true); ?> </title>
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <link rel="shortcut icon" href="<?= get_resource_path('/img/favicon.png') ?>" type="image/png">

    <?php wp_head(); ?>
</head>
<body>


    <?= $theme_spg->get_section_header_html(); ?>
    <div id="site-content">

<?php
/**
 * Template part for displaying page content in page.php
 *
 *
 */

if (!function_exists('get_current_page_title')) {
    function get_current_page_title()
    {

        global $theme_spg;

        if (is_singular()) :
            return $theme_spg->get_page_title();
        else :
            return '';
        endif;

    }

}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('m-0'); ?>>

    <?= get_current_page_title(); ?>

    <?php
        if ( !empty( get_the_content() ) ) {
                the_content();

        }
    ?>

</article><!-- #post-<?php the_ID(); ?> -->
<?php
/**
 * The template for displaying all pages
 *
 */

get_header($theme_spg->theme_file_suffix);
?>

    <div id="primary" class="container-fluid px-0">
        <main id="main" class="site-main">

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

                get_template_part( 'php/page-part/content', 'page' );

            endwhile; endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer($theme_spg->theme_file_suffix); ?>
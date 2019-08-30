<?php
/**
 * The template for Home Page
 *
 */

global $theme_spg;
$page_metabox_hero = $theme_spg->get_page_metabox_service_by_name('page_metabox_hero');

get_header();
?>

    <section class="hero">

        <div class="hero__description">
            <div class="intro">

                <div class="intro__title">
                    <h1><?= $page_metabox_hero->data['title']; ?></h1>
                </div>

                <div class="intro__subtitle">
                    <?= $page_metabox_hero->data['description']; ?>
                </div>

                <div class="intro__toolbar">
                    <button class="intro__button">
                        <a id="page-intro-btn-1"
                           href="<?= $page_metabox_hero->data['buttons'][0]['button_url']; ?>" role="button">
                            <?= $page_metabox_hero->data['buttons'][0]['button_text']; ?>
                        </a>
                    </button>
                    <button class="intro__button btn__transparent">
                        <i class="fas fa-play"></i>
                        <a id="page-intro-btn-2"
                           href="<?= $page_metabox_hero->data['buttons'][1]['button_url']; ?>" role="button">
                            <?= $page_metabox_hero->data['buttons'][1]['button_text']; ?> </a>
                    </button>
                </div>

            </div>
        </div>

        <video class="hero__bg-video" data-element="main-video" autoplay="" loop="" muted="">
            <source src="<?= $page_metabox_hero->data['video_mp4']; ?>" type="video/mp4">
            <source src="<?= $page_metabox_hero->data['video_webm']; ?>" type="video/webm">
        </video>

    </section>

<?php get_footer(); ?>
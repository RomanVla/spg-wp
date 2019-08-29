<?php
/**
 * The template for displaying the footer
 *
 */

function get_badges_html() {
    $badge_index = 0;
    $badges_html = '';
    $badges = get_field( 'badges', 'option' );
    foreach ( $badges as $badge ) {
        $badges_html .= '
        <div id="badge-link-{{badge_index}}" style="{{badge_section-style}}"> 
          <a class="text-light" href="{{badge_link}}"> 
            <img src="{{badge_image}}" alt="Software Planet Group" class="img-scalable is-max-width-120-desktop" data-retinable></a> 
         </div>
        ';

        $badges_html = str_replace('{{badge_image}}', $badge['image'], $badges_html);
        $badges_html = str_replace('{{badge_index}}', $badge_index, $badges_html);
        $badges_html = str_replace('{{badge_link}}', $badge['link'], $badges_html);
        $badges_html = str_replace('{{badge_section-style}}', $badge['section-style'], $badges_html);

        $badge_index += 1;
    }

    return $badges_html;

}

function get_social_network_html() {

    $social_data_html = '';
    $social_network = get_field( 'social_network', 'option' );
    foreach ( $social_network as $social_data ) {

        $social_data_html .= '
        <div class="col-1 social-col"> 
           <a class="text-light" href="{{social_link}}"> <i class="{{social_icon}}"></i></a> 
        </div>
        ';

        $social_data_html = str_replace('{{social_icon}}', $social_data['icon'], $social_data_html);
        $social_data_html = str_replace('{{social_link}}', $social_data['link'], $social_data_html);

    }

    $html = '
            <div class="row social-link-size justify-content-start">
                {{social_data_html}}
            </div> 
        ';

    $html = str_replace('{{social_data_html}}', $social_data_html, $html);

    return $html;
}

function get_section_footer_html() {
    global $theme_spg;

        $html = '
            
                    <div class="row">
        
                        <div class="col-lg-6 col-md-7 col-sm-7 col-3">
                        
                            <div class="row">
                                <div class="footer-nav-menu col-12 is-hidden-mobile">
                                    {{theme_nav_menu}}
                                </div>
                                <div class="col-12 is-hidden-mobile banner-block">
                                    <div class="banner-setting">© {{current_year}} All Rights Reserved.</div>
                                </div>                                                        
                            </div>
                            <div class="row">
                                <div class="col-12">                                    
                                    {{social_network_html}}
                                </div>
                            </div>
                        </div>
                              
                        <div class="col-lg-6 col-md-5 col-sm-5 col-9">
        
                            <div class="d-flex justify-content-end align-items-center">
                            
                                {{badges_html}}
                            
                            </div>
        
                        </div>
                                
                                   <div class="col-12 is-hidden-tablet banner-block">
                                    <div class="text-center">© {{current_year}} All Rights Reserved.</div>
                                </div>  
                                                                                
                    </div>
        
        ';

    $html = str_replace('{{theme_nav_menu}}', $theme_spg->get_theme_nav_menu( 'primary', true), $html);
    $html = str_replace('{{current_year}}', date( 'Y' ), $html);
    $html = str_replace('{{home_url}}', get_home_url(), $html);
    $html = str_replace('{{site_logo}}', file_get_contents(get_field( 'site_logo', 'options' )), $html);

    $html = str_replace('{{badges_html}}', get_badges_html(), $html);
    $html = str_replace('{{social_network_html}}', get_social_network_html(), $html);

    return Section::html_wrap_to_section(
        $html
    );

}

?>
                        </div><!-- #site-content -->
        <footer class="footer">
            <?= '' //get_section_footer_html(); ?>
        </footer>

    <?php wp_footer(); ?>

    </body>
</html>

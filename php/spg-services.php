<?php

function getTplPageURL($TEMPLATE_NAME){
    $url = null;

    $pages = get_posts(array(
        'post_type' =>'page',
        'meta_key'  =>'_wp_page_template',
        'meta_value'=> $TEMPLATE_NAME
    ));

    // cycle through $pages here and either grab the URL
    // from the results or do get_page_link($id) with
    // the id of the page you want

    $url = null;
    if(isset($pages[0])) {
        $url = get_page_link($pages[0]);
    }
    return $url;
}

function get_value($value, $default_value) {
    if($value === '') {
        return $default_value;
    }

    return $value;
}

function from_camel_case($input) {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

function get_resource_path($path)
{
    return get_stylesheet_directory_uri() . $path;
}

function content_wrap_class($class = '') {
    echo 'class="' . get_content_wrap_class( $class) . '"';
}

function get_content_wrap_class($class = '') {
    return $class . ' content is-size-14-mobile is-size-6-desktop has-text-left';
}

function set_array_atts($pairs, $atts) {
    $atts = (array)$atts;
    $out = array();
    foreach ($pairs as $name => $default) {
        if ( array_key_exists($name, $atts) ) {
            $out[$name] = $atts[$name];
        } else {
            $out[$name] = $default;
        }
    }

    return $out;
}

add_action( 'wp_ajax_send_mail', 'wpd_ajax_send_mail' );
add_action( 'wp_ajax_nopriv_send_mail', 'wpd_ajax_send_mail' );
function wpd_ajax_send_mail() {

    $body = json_decode(file_get_contents( 'php://input' ), true);

    $mailType = $body['mailType'];
    $form_data = $body['form_data'];

    $headers     = array(
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=iso-8859-1',
        'From: SPG Website <request@softwareplanetgroup.com>',
    );
    $htmlContent = '';

    if ( $mailType == 'contact_form_message' ) {
        fill_contact_form_message_mail( $form_data, $htmlContent );
    }

    $result = false;
    try {
        $result = wp_mail( get_field( 'mail_receiver', 'option' ),
            'New Message Enquiry from SPG Website - ' . date( "h:i d-M-Y" ),
            $htmlContent,
            implode( "\r\n", $headers ) );

    } catch ( Exception $e ) {
        echo json_encode( array( 'result' => false, 'error' => $e->getMessage() ) );
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    echo json_encode( array( 'result' => $result ) );
    wp_die();
}

function fill_contact_form_message_mail($form_data, &$htmlContent ) {

    $mailTemplateFile = WP_CONTENT_DIR . "/themes/spg-wp-theme/mailTemplate.html";
    if ( file_exists( $mailTemplateFile ) ) {
        $htmlContent = file_get_contents( $mailTemplateFile );
        $htmlContent = str_replace( "{{template_directory_uri}}", get_stylesheet_directory_uri(), $htmlContent );
        $htmlContent = str_replace( "{{site_logo}}", file_get_contents(get_field( 'site_logo', 'options' )), $htmlContent );
        $htmlContent = str_replace( "{{site_url}}", get_site_url(), $htmlContent );
        $htmlContent = str_replace( "{{contactInformation}}", '', $htmlContent );

        $htmlContent = str_replace( '{{contact_form_name}}', $form_data['name'], $htmlContent );
        $htmlContent = str_replace( '{{contact_form_email}}', $form_data['email'], $htmlContent );
        $htmlContent = str_replace( '{{contact_form_message}}', str_replace("\n\r", '<br><br>', $form_data['message']), $htmlContent );

    }

}

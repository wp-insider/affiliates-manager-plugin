<?php

function wpam_display_addons_menu()
{
    echo '<div class="wrap">';
    echo '<h2>' .__('Affiliates Manager Add-ons', 'wpam') . '</h2>';
    echo '<link type="text/css" rel="stylesheet" href="' . WPAM_URL . '/style/wpam-addons-listing.css" />' . "\n";
    
    $addons_data = array();

    $addon_1 = array(
        'name' => 'MailChimp Integration',
        'thumbnail' => WPAM_URL . '/images/addons/mailchimp-integration.png',
        'description' => 'Allows you to signup the affiliates to your MailChimp list after registration',
        'page_url' => 'https://wpaffiliatemanager.com/signup-affiliates-mailchimp-list/',
    );
    array_push($addons_data, $addon_1);

    $addon_2 = array(
        'name' => 'Google reCAPTCHA',
        'thumbnail' => WPAM_URL . '/images/addons/google-recaptcha.png',
        'description' => 'Allows you to add Google recaptcha to your affiliate signup page. Helps prevent spam signup.',
        'page_url' => 'https://wpaffiliatemanager.com/affiliates-manager-google-recaptcha-integration/',
    );
    array_push($addons_data, $addon_2);

    $addon_3 = array(
        'name' => 'Google reCAPTCHA',
        'thumbnail' => WPAM_URL . '/images/addons/google-recaptcha.png',
        'description' => 'Allows you to add Google recaptcha to your affiliate signup page. Helps prevent spam signup.',
        'page_url' => 'https://wpaffiliatemanager.com/affiliates-manager-google-recaptcha-integration/',
    );
    array_push($addons_data, $addon_3);
    
    //Display the list
    foreach ($addons_data as $addon) {
        $output .= '<div class="wpam_addon_item_canvas">';

        $output .= '<div class="wpam_addon_item_thumb">';
        $img_src = $addon['thumbnail'];
        $output .= '<img src="' . $img_src . '" alt="' . $addon['name'] . '">';
        $output .= '</div>'; //end thumbnail

        $output .='<div class="wpam_addon_item_body">';
        $output .='<div class="wpam_addon_item_name">';
        $output .= '<a href="' . $addon['page_url'] . '" target="_blank">' . $addon['name'] . '</a>';
        $output .='</div>'; //end name

        $output .='<div class="wpam_addon_item_description">';
        $output .= $addon['description'];
        $output .='</div>'; //end description

        $output .='<div class="wpam_addon_item_details_link">';
        $output .='<a href="'.$addon['page_url'].'" class="wpam_addon_view_details" target="_blank">View Details</a>';
        $output .='</div>'; //end detils link      
        $output .='</div>'; //end body

        $output .= '</div>'; //end canvas
    }
    echo $output;
    
    echo '</div>';//end of wrap
}
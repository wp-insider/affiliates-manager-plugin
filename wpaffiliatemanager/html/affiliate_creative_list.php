<div class="aff-wrap">
    <?php
    include WPAM_BASE_DIRECTORY . "/html/affiliate_cp_nav.php";
    /** better method but currently does not work
    global $wpdb;
    $currentUser = wp_get_current_user();
    $user_id = $currentUser->ID;
    $table_name = WPAM_AFFILIATES_TBL;
    $affiliate = $wpdb->get_row("SELECT * FROM $table_name WHERE userId = '$user_id' AND status = 'active'");
    $record_found = true;
    if(!$affiliate){
        $record_found = false;
    }
    $table_name = WPAM_CREATIVES_TBL;
    $default_creative_id = get_option(WPAM_PluginConfig::$DefaultCreativeId);
    $creative = '';
    if(empty($default_creative_id)){
        $record_found = false;
    }
    else{
        $creative = $wpdb->get_row("SELECT * FROM $table_name WHERE creativeId = '$default_creative_id'");
        if(!$creative){
            $record_found = false;
        }
    }
    $alink = '';
    $alink_id = '';
    $alink_email = '';
    if($record_found){
        $aid = $affiliate->affiliateId;
        $alink_id = add_query_arg( array( WPAM_PluginConfig::$RefKey => $aid ), home_url('/') );
        $aemail = $affiliate->email;
        $alink_email = add_query_arg( array( WPAM_PluginConfig::$RefKey => $aemail ), home_url('/') );
        $trackingKey = new WPAM_Tracking_TrackingKey();
        $trackingKey->setAffiliateRefKey($affiliate->uniqueRefKey);
        $trackingKey->setCreativeId($creative->creativeId);
        $alink = add_query_arg( array( WPAM_PluginConfig::$RefKey => $trackingKey->pack() ), home_url('/') );
    }
    ****/
    $db = new WPAM_Data_DataAccess();
    $currentUser = wp_get_current_user();
    $alink_id = '';
    $affiliateRepos = $db->getAffiliateRepository();
    $affiliate = $affiliateRepos->loadBy(array('userId' => $currentUser->ID, 'status' => 'active'));
    if ( $affiliate === NULL ) {  //affiliate with this WP User ID does not exist

    }
    else{
        $home_url = home_url('/');
        $aff_landing_page = get_option(WPAM_PluginConfig::$AffLandingPageURL);
        if(isset($aff_landing_page) && !empty($aff_landing_page)){
            $home_url = $aff_landing_page;
        }
        $alink_id = add_query_arg( array( WPAM_PluginConfig::$wpam_id => $affiliate->affiliateId ), $home_url );
    }
    ?>

    <div class="wrap">
        <?php
        if(!empty($alink_id)){
        ?>
        <h3><?php _e('Your Affiliate Link Using Affiliate ID', 'affiliates-manager') ?></h3>
        <textarea class="wpam-creative-code" rows="1"><?php echo $alink_id; ?></textarea>
        <?php
        }
        ?>
        <h3><?php _e('The following creatives are available for publication.', 'affiliates-manager') ?></h3>

        <table class="pure-table">
            <thead>
                <tr>
                    <th><?php _e('Type', 'affiliates-manager') ?></th>
                    <th><?php _e('Name', 'affiliates-manager') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->viewData['creatives'] as $creative) { ?>
                    <tr>
                        <td class="wpam-creative-type"><?php echo $creative->type ?></td>
                        <td class="wpam-creative-name"><a href="?page_id=<?php echo the_ID() ?>&sub=creatives&action=detail&creativeId=<?php echo $creative->creativeId ?>"><?php echo $creative->name ?></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>